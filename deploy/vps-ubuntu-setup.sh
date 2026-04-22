#!/bin/bash
# =============================================================================
# HALI Access Partner Portal — Ubuntu VPS Setup
# Target : Ubuntu 22.04 LTS or 24.04 LTS
# Run as : sudo bash deploy/vps-ubuntu-setup.sh
#
# What this does:
#   1. Installs PHP 8.3, Nginx, MySQL 8, Composer, Node.js, Certbot
#   2. Creates the database and a dedicated DB user
#   3. Writes PHP-FPM and Nginx configs tuned for a 1–2 GB RAM VPS
#   4. Installs Supervisor to keep the queue worker and scheduler running
#   5. Adds a 1 GB swap file (essential if RAM ≤ 2 GB)
#
# After this script finishes, run:
#   sudo bash deploy/deploy.sh <git-repo-url> haliportal.tickooplug.co.ke
# =============================================================================
set -euo pipefail

DOMAIN="haliportal.tickooplug.co.ke"
APP_NAME="hali-portal"
APP_DIR="/var/www/$APP_NAME"
APP_USER="www-data"
PHP_VERSION="8.3"
DB_NAME="hali_portal"
DB_USER="hali_user"
CREDS_FILE="/root/hali-credentials.txt"

echo "=============================================="
echo "  HALI Portal — Ubuntu VPS Setup"
echo "  $(date)"
echo "=============================================="

# Must run as root
if [ "$(id -u)" -ne 0 ]; then
    echo "Run as root: sudo bash $0"
    exit 1
fi

# ── 1. System update ──────────────────────────────────────────────────────────
echo "[1/9] Updating system packages..."
export DEBIAN_FRONTEND=noninteractive
apt-get update -qq
apt-get upgrade -y -qq
apt-get install -y -qq \
    git curl unzip zip wget tar gnupg2 ca-certificates lsb-release \
    software-properties-common supervisor cron ufw

# ── 2. PHP 8.3 ───────────────────────────────────────────────────────────────
echo "[2/9] Installing PHP $PHP_VERSION..."
add-apt-repository -y ppa:ondrej/php 2>/dev/null || true
apt-get update -qq
apt-get install -y -qq \
    php${PHP_VERSION}-fpm php${PHP_VERSION}-cli php${PHP_VERSION}-common \
    php${PHP_VERSION}-mysql php${PHP_VERSION}-pdo php${PHP_VERSION}-mbstring \
    php${PHP_VERSION}-xml php${PHP_VERSION}-curl php${PHP_VERSION}-zip \
    php${PHP_VERSION}-bcmath php${PHP_VERSION}-intl php${PHP_VERSION}-tokenizer \
    php${PHP_VERSION}-fileinfo php${PHP_VERSION}-gd php${PHP_VERSION}-opcache \
    php${PHP_VERSION}-redis

# Tune PHP for production
cat > /etc/php/${PHP_VERSION}/fpm/conf.d/99-production.ini << 'PHP_INI'
memory_limit = 256M
upload_max_filesize = 10M
post_max_size = 12M
max_execution_time = 60
opcache.enable = 1
opcache.memory_consumption = 128
opcache.interned_strings_buffer = 8
opcache.max_accelerated_files = 10000
opcache.revalidate_freq = 60
opcache.validate_timestamps = 0
expose_php = Off
PHP_INI

# Tune PHP-FPM for 1–2 GB RAM
cat > /etc/php/${PHP_VERSION}/fpm/pool.d/www.conf << PHP_FPM
[www]
user = ${APP_USER}
group = ${APP_USER}
listen = /run/php/php${PHP_VERSION}-fpm.sock
listen.owner = ${APP_USER}
listen.group = ${APP_USER}
pm = dynamic
pm.max_children = 15
pm.start_servers = 2
pm.min_spare_servers = 1
pm.max_spare_servers = 4
pm.max_requests = 500
PHP_FPM

systemctl enable php${PHP_VERSION}-fpm
systemctl restart php${PHP_VERSION}-fpm

# ── 3. Nginx ─────────────────────────────────────────────────────────────────
echo "[3/9] Installing Nginx..."
apt-get install -y -qq nginx

# Remove default site
rm -f /etc/nginx/sites-enabled/default

# Nginx tuning
cat > /etc/nginx/conf.d/performance.conf << 'NGINX_PERF'
gzip on;
gzip_vary on;
gzip_proxied any;
gzip_comp_level 6;
gzip_types
    text/plain text/css text/xml text/javascript
    application/json application/javascript application/xml
    application/rss+xml application/atom+xml image/svg+xml;
gzip_min_length 1000;
client_max_body_size 12M;
server_tokens off;
NGINX_PERF

# Write the portal vhost (HTTP only — certbot will add HTTPS)
cat > /etc/nginx/sites-available/${APP_NAME} << NGINX_CONF
server {
    listen 80;
    listen [::]:80;
    server_name ${DOMAIN};

    root ${APP_DIR}/public;
    index index.php;
    charset utf-8;

    # Static assets — 1 year cache, immutable
    location ~* \.(css|js|jpg|jpeg|png|gif|ico|svg|woff|woff2|ttf|eot|webp|avif|map)$ {
        expires 1y;
        add_header Cache-Control "public, immutable";
        access_log off;
        try_files \$uri =404;
    }

    location / {
        try_files \$uri \$uri/ /index.php?\$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php\$ {
        fastcgi_pass unix:/run/php/php${PHP_VERSION}-fpm.sock;
        fastcgi_param SCRIPT_FILENAME \$realpath_root\$fastcgi_script_name;
        include fastcgi_params;
        # Tell Laravel the request came over HTTPS — prevents mixed-content
        # and ensures asset() / url() generate https:// links
        fastcgi_param HTTPS on;
        fastcgi_param HTTP_X_FORWARDED_PROTO https;
        fastcgi_hide_header X-Powered-By;
        fastcgi_read_timeout 60;
    }

    # Block dot-files (except Let's Encrypt ACME challenge)
    location ~ /\.(?!well-known).* {
        deny all;
    }

    access_log /var/log/nginx/${APP_NAME}-access.log;
    error_log  /var/log/nginx/${APP_NAME}-error.log warn;
}
NGINX_CONF

ln -sf /etc/nginx/sites-available/${APP_NAME} /etc/nginx/sites-enabled/${APP_NAME}
nginx -t
systemctl enable nginx
systemctl restart nginx

# ── 4. MySQL 8 ───────────────────────────────────────────────────────────────
echo "[4/9] Installing MySQL 8..."
apt-get install -y -qq mysql-server

# Generate a secure random DB password
DB_PASSWORD=$(openssl rand -base64 32 | tr -dc 'a-zA-Z0-9' | head -c 28)

# Save credentials (deploy.sh reads this)
cat > "${CREDS_FILE}" << EOF
DB_NAME=${DB_NAME}
DB_USER=${DB_USER}
DB_PASSWORD=${DB_PASSWORD}
EOF
chmod 600 "${CREDS_FILE}"

mysql -u root << SQL
CREATE DATABASE IF NOT EXISTS \`${DB_NAME}\`
    CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER IF NOT EXISTS '${DB_USER}'@'localhost'
    IDENTIFIED BY '${DB_PASSWORD}';
GRANT ALL PRIVILEGES ON \`${DB_NAME}\`.* TO '${DB_USER}'@'localhost';
FLUSH PRIVILEGES;
SQL

echo "  MySQL: database '${DB_NAME}', user '${DB_USER}'"
echo "  Credentials saved to ${CREDS_FILE}"

# ── 5. Composer ───────────────────────────────────────────────────────────────
echo "[5/9] Installing Composer..."
curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
composer --version

# ── 6. Node.js 22 ─────────────────────────────────────────────────────────────
echo "[6/9] Installing Node.js 22..."
curl -fsSL https://deb.nodesource.com/setup_22.x | bash - 2>/dev/null
apt-get install -y -qq nodejs
node --version && npm --version

# ── 7. Swap file (critical for 1 GB RAM VPS) ──────────────────────────────────
echo "[7/9] Setting up swap..."
if [ ! -f /swapfile ]; then
    fallocate -l 1G /swapfile
    chmod 600 /swapfile
    mkswap /swapfile
    swapon /swapfile
    echo '/swapfile none swap sw 0 0' >> /etc/fstab
    echo '  vm.swappiness=10' >> /etc/sysctl.conf
    sysctl -p
    echo "  1 GB swap enabled"
else
    echo "  Swap already exists — skipping"
fi

# ── 8. App directory ──────────────────────────────────────────────────────────
echo "[8/9] Creating app directory..."
mkdir -p "${APP_DIR}"
chown -R "${APP_USER}:${APP_USER}" "${APP_DIR}"
chmod -R 755 "${APP_DIR}"

# ── 9. Supervisor (queue worker + scheduler) ───────────────────────────────────
echo "[9/9] Configuring Supervisor..."

cat > /etc/supervisor/conf.d/hali-worker.conf << SUPERVISOR
[program:hali-worker]
process_name=%(program_name)s_%(process_num)02d
command=php ${APP_DIR}/artisan queue:work database --sleep=3 --tries=3 --max-time=3600
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=${APP_USER}
numprocs=1
redirect_stderr=true
stdout_logfile=${APP_DIR}/storage/logs/worker.log
stopwaitsecs=3600
SUPERVISOR

cat > /etc/supervisor/conf.d/hali-scheduler.conf << SUPERVISOR
[program:hali-scheduler]
command=php ${APP_DIR}/artisan schedule:work
autostart=true
autorestart=true
user=${APP_USER}
redirect_stderr=true
stdout_logfile=${APP_DIR}/storage/logs/scheduler.log
SUPERVISOR

systemctl enable supervisor
systemctl restart supervisor

# ── Firewall ──────────────────────────────────────────────────────────────────
echo "Configuring firewall..."
ufw --force reset
ufw default deny incoming
ufw default allow outgoing
ufw allow ssh
ufw allow http
ufw allow https
ufw --force enable
echo "  UFW enabled: SSH, HTTP, HTTPS allowed"

# ── Done ─────────────────────────────────────────────────────────────────────
echo ""
echo "=============================================="
echo "  Server setup complete!"
echo "  $(date)"
echo "=============================================="
echo ""
echo "  Next step — deploy the app:"
echo ""
echo "    sudo bash ${APP_DIR}/deploy/deploy.sh \\"
echo "      <your-git-repo-url> \\"
echo "      ${DOMAIN}"
echo ""
echo "  Or, if the repo is already cloned:"
echo "    sudo bash deploy/deploy.sh"
echo ""
echo "  MySQL credentials: ${CREDS_FILE}"
echo ""
