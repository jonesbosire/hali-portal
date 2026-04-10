#!/bin/bash
# =============================================================================
# HALI Access Partner Portal — AWS EC2 Setup Script
# Target: Amazon Linux 2023, t2.micro / t3.micro (Free Tier)
# Run as: sudo bash aws-ec2-setup.sh
# =============================================================================
set -euo pipefail

APP_NAME="hali-portal"
APP_DIR="/var/www/$APP_NAME"
APP_USER="www-data"
PHP_VERSION="8.3"
NODE_VERSION="20"
DB_NAME="hali_portal"
DB_USER="hali_user"

echo "=============================================="
echo "  HALI Portal — EC2 Setup"
echo "=============================================="

# ==============================================================================
# 1. SYSTEM UPDATE & BASIC TOOLS
# ==============================================================================
echo "[1/9] Updating system..."
dnf update -y
dnf install -y git curl unzip zip wget tar supervisor cronie

# ==============================================================================
# 2. PHP 8.3
# ==============================================================================
echo "[2/9] Installing PHP $PHP_VERSION..."
dnf install -y php8.3 php8.3-fpm php8.3-cli php8.3-common \
    php8.3-mysqlnd php8.3-pdo php8.3-mbstring php8.3-xml \
    php8.3-curl php8.3-zip php8.3-bcmath php8.3-intl \
    php8.3-tokenizer php8.3-fileinfo php8.3-gd php8.3-opcache

# Tune PHP-FPM for t2.micro (1 GB RAM)
cat > /etc/php-fpm.d/www.conf << 'PHP_FPM'
[www]
user = nginx
group = nginx
listen = /run/php-fpm/www.sock
listen.owner = nginx
listen.group = nginx
pm = dynamic
pm.max_children = 10
pm.start_servers = 2
pm.min_spare_servers = 1
pm.max_spare_servers = 3
pm.max_requests = 500
php_admin_value[error_log] = /var/log/php-fpm/www-error.log
php_admin_flag[log_errors] = on
PHP_FPM

# Tune PHP for production
cat > /etc/php.d/99-production.ini << 'PHP_INI'
; Production tuning for 1 GB RAM
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

systemctl enable php-fpm
systemctl start php-fpm

# ==============================================================================
# 3. NGINX
# ==============================================================================
echo "[3/9] Installing Nginx..."
dnf install -y nginx

cat > /etc/nginx/conf.d/$APP_NAME.conf << NGINX_CONF
server {
    listen 80;
    listen [::]:80;
    server_name _;                        # Replace _ with your domain
    root $APP_DIR/public;
    index index.php;
    charset utf-8;

    # Security headers
    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";
    add_header Referrer-Policy "strict-origin-when-cross-origin";

    # Gzip
    gzip on;
    gzip_types text/plain text/css application/json application/javascript text/xml application/xml image/svg+xml;
    gzip_min_length 1000;

    # Static asset caching
    location ~* \.(css|js|jpg|jpeg|png|gif|ico|svg|woff|woff2|ttf|eot|webp)$ {
        expires 1y;
        add_header Cache-Control "public, immutable";
        access_log off;
    }

    location / {
        try_files \$uri \$uri/ /index.php?\$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/run/php-fpm/www.sock;
        fastcgi_param SCRIPT_FILENAME \$realpath_root\$fastcgi_script_name;
        include fastcgi_params;
        fastcgi_hide_header X-Powered-By;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }

    client_max_body_size 12M;
}
NGINX_CONF

# Remove default config
rm -f /etc/nginx/conf.d/default.conf

systemctl enable nginx
systemctl start nginx

# ==============================================================================
# 4. MYSQL (on EC2 — avoids RDS cost after free tier)
# ==============================================================================
echo "[4/9] Installing MySQL..."
dnf install -y mysql-server
systemctl enable mysqld
systemctl start mysqld

# Generate a secure DB password and save it
DB_PASSWORD=$(openssl rand -base64 24 | tr -dc 'a-zA-Z0-9' | head -c 20)
echo "DB_PASSWORD=$DB_PASSWORD" >> /root/hali-credentials.txt

mysql -u root << SQL
CREATE DATABASE IF NOT EXISTS $DB_NAME CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER IF NOT EXISTS '$DB_USER'@'localhost' IDENTIFIED BY '$DB_PASSWORD';
GRANT ALL PRIVILEGES ON $DB_NAME.* TO '$DB_USER'@'localhost';
FLUSH PRIVILEGES;
SQL

echo "  MySQL: Database '$DB_NAME' created, user '$DB_USER'"
echo "  Credentials saved to /root/hali-credentials.txt"

# ==============================================================================
# 5. COMPOSER
# ==============================================================================
echo "[5/9] Installing Composer..."
curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
composer --version

# ==============================================================================
# 6. NODE.JS (for npm run build)
# ==============================================================================
echo "[6/9] Installing Node.js $NODE_VERSION..."
curl -fsSL https://rpm.nodesource.com/setup_${NODE_VERSION}.x | bash -
dnf install -y nodejs
node --version && npm --version

# ==============================================================================
# 7. SWAP FILE (critical for t2.micro with 1 GB RAM)
# ==============================================================================
echo "[7/9] Creating 1 GB swap file..."
if [ ! -f /swapfile ]; then
    fallocate -l 1G /swapfile
    chmod 600 /swapfile
    mkswap /swapfile
    swapon /swapfile
    echo '/swapfile none swap sw 0 0' >> /etc/fstab
    echo "  Swap enabled"
fi

# ==============================================================================
# 8. APP USER & DIRECTORY PERMISSIONS
# ==============================================================================
echo "[8/9] Setting up app directory..."
useradd -r -s /sbin/nologin $APP_USER 2>/dev/null || true
mkdir -p $APP_DIR
chown -R $APP_USER:nginx $APP_DIR
chmod -R 755 $APP_DIR

# ==============================================================================
# 9. SUPERVISOR (queue worker + scheduler)
# ==============================================================================
echo "[9/9] Configuring Supervisor..."

cat > /etc/supervisord.d/hali-worker.ini << SUPERVISOR
[program:hali-worker]
process_name=%(program_name)s_%(process_num)02d
command=php $APP_DIR/artisan queue:work database --sleep=3 --tries=3 --max-time=3600
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=nginx
numprocs=1
redirect_stderr=true
stdout_logfile=$APP_DIR/storage/logs/worker.log
stopwaitsecs=3600
SUPERVISOR

cat > /etc/supervisord.d/hali-scheduler.ini << SUPERVISOR
[program:hali-scheduler]
command=php $APP_DIR/artisan schedule:work
autostart=true
autorestart=true
user=nginx
redirect_stderr=true
stdout_logfile=$APP_DIR/storage/logs/scheduler.log
SUPERVISOR

systemctl enable supervisord
systemctl start supervisord

# ==============================================================================
# DONE — print summary
# ==============================================================================
echo ""
echo "=============================================="
echo "  Setup complete! Next: run deploy.sh"
echo "=============================================="
echo ""
echo "  App directory : $APP_DIR"
echo "  PHP           : $(php -r 'echo PHP_VERSION;')"
echo "  Nginx         : $(nginx -v 2>&1)"
echo "  MySQL user    : $DB_USER"
echo "  DB password   : saved to /root/hali-credentials.txt"
echo ""
echo "  Run next:"
echo "    sudo bash deploy/deploy.sh <your-git-repo-url> <your-domain>"
echo ""
