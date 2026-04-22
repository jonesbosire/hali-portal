#!/bin/bash
# =============================================================================
# HALI Access Partner Portal — Deploy / Re-deploy Script
# Usage: sudo bash deploy/deploy.sh <git-repo-url> <domain>
# Re-deploy (update): sudo bash deploy/deploy.sh  (from inside $APP_DIR)
# =============================================================================
set -euo pipefail

APP_NAME="hali-portal"
APP_DIR="/var/www/$APP_NAME"
GIT_REPO="${1:-}"
DOMAIN="${2:-haliportal.tickooplug.co.ke}"
DEPLOY_USER="www-data"

# Detect re-deploy (no args, already cloned)
if [ -z "$GIT_REPO" ] && [ -f "$APP_DIR/artisan" ]; then
    echo "[deploy] Re-deploying existing installation..."
    cd "$APP_DIR"
    REDEPLOY=true
else
    REDEPLOY=false
    if [ -z "$GIT_REPO" ]; then
        echo "Usage: bash deploy.sh <git-repo-url> <domain>"
        echo "  e.g. bash deploy.sh https://github.com/yourorg/portal.git portal.haliaccess.org"
        exit 1
    fi
fi

echo "=============================================="
echo "  HALI Portal — Deploy"
echo "  $(date)"
echo "=============================================="

# ==============================================================================
# CLONE OR PULL
# ==============================================================================
if [ "$REDEPLOY" = false ]; then
    echo "[1] Cloning repository..."
    rm -rf "$APP_DIR"
    git clone "$GIT_REPO" "$APP_DIR"
    cd "$APP_DIR"
else
    echo "[1] Pulling latest code..."
    cd "$APP_DIR"
    git fetch origin
    git reset --hard origin/main      # adjust branch name if needed
fi

# ==============================================================================
# ENVIRONMENT FILE
# ==============================================================================
echo "[2] Setting up .env..."

if [ ! -f "$APP_DIR/.env" ]; then
    if [ ! -f "$APP_DIR/.env.production" ]; then
        cp "$APP_DIR/.env.example" "$APP_DIR/.env"
        echo "  WARNING: .env copied from .env.example — fill in secrets before continuing"
        echo "  Edit: nano $APP_DIR/.env"
        echo "  Then re-run: sudo bash $APP_DIR/deploy/deploy.sh"
        exit 1
    fi
    cp "$APP_DIR/.env.production" "$APP_DIR/.env"
fi

# Patch .env for production
sed -i "s|APP_ENV=.*|APP_ENV=production|"                               "$APP_DIR/.env"
sed -i "s|APP_DEBUG=.*|APP_DEBUG=false|"                                "$APP_DIR/.env"
sed -i "s|SESSION_DRIVER=.*|SESSION_DRIVER=database|"                   "$APP_DIR/.env"
sed -i "s|SESSION_ENCRYPT=.*|SESSION_ENCRYPT=true|"                     "$APP_DIR/.env"
sed -i "s|SESSION_SECURE_COOKIE=.*|SESSION_SECURE_COOKIE=true|"         "$APP_DIR/.env"
sed -i "s|CACHE_STORE=.*|CACHE_STORE=database|"                         "$APP_DIR/.env"
sed -i "s|QUEUE_CONNECTION=.*|QUEUE_CONNECTION=database|"               "$APP_DIR/.env"
sed -i "s|FILESYSTEM_DISK=.*|FILESYSTEM_DISK=local|"                    "$APP_DIR/.env"
sed -i "s|LOG_CHANNEL=.*|LOG_CHANNEL=daily|"                            "$APP_DIR/.env"
sed -i "s|LOG_LEVEL=.*|LOG_LEVEL=error|"                                "$APP_DIR/.env"

# Patch DB credentials (written by vps-ubuntu-setup.sh or aws-ec2-setup.sh)
if [ -f /root/hali-credentials.txt ]; then
    DB_PASS=$(grep DB_PASSWORD /root/hali-credentials.txt | cut -d= -f2)
    sed -i "s|DB_HOST=.*|DB_HOST=127.0.0.1|"              "$APP_DIR/.env"
    sed -i "s|DB_CONNECTION=.*|DB_CONNECTION=mysql|"       "$APP_DIR/.env"
    sed -i "s|DB_DATABASE=.*|DB_DATABASE=hali_portal|"    "$APP_DIR/.env"
    sed -i "s|DB_USERNAME=.*|DB_USERNAME=hali_user|"      "$APP_DIR/.env"
    sed -i "s|DB_PASSWORD=.*|DB_PASSWORD=$DB_PASS|"       "$APP_DIR/.env"
fi

# Set APP_URL, ASSET_URL and session domain
if [ -n "$DOMAIN" ]; then
    sed -i "s|APP_URL=.*|APP_URL=https://$DOMAIN|"             "$APP_DIR/.env"
    # ASSET_URL must match APP_URL exactly — this is what @vite() and asset() use
    # to prefix CSS/JS hrefs. Wrong value = blank page / no styles.
    if grep -q "^ASSET_URL=" "$APP_DIR/.env"; then
        sed -i "s|ASSET_URL=.*|ASSET_URL=https://$DOMAIN|"     "$APP_DIR/.env"
    else
        echo "ASSET_URL=https://$DOMAIN"                     >> "$APP_DIR/.env"
    fi
    sed -i "s|SESSION_DOMAIN=.*|SESSION_DOMAIN=$DOMAIN|"       "$APP_DIR/.env"
fi

# ==============================================================================
# COMPOSER (production — no dev dependencies)
# ==============================================================================
echo "[3] Installing PHP dependencies..."
cd "$APP_DIR"
sudo -u $DEPLOY_USER composer install \
    --no-dev \
    --optimize-autoloader \
    --no-interaction \
    --prefer-dist

# ==============================================================================
# NODE / BUILD ASSETS
# ==============================================================================
echo "[4] Building frontend assets..."
npm ci --prefer-offline
npm run build
rm -rf node_modules   # free RAM after build

# ==============================================================================
# APP KEY
# ==============================================================================
echo "[5] App key..."
if ! grep -q "^APP_KEY=base64:" "$APP_DIR/.env"; then
    php artisan key:generate --force
    echo "  Generated new APP_KEY"
fi

# ==============================================================================
# STORAGE & PERMISSIONS
# ==============================================================================
echo "[6] Storage & permissions..."
php artisan storage:link --force 2>/dev/null || true
mkdir -p storage/logs storage/framework/{cache,sessions,views} bootstrap/cache
chown -R $DEPLOY_USER:$DEPLOY_USER "$APP_DIR/storage" "$APP_DIR/bootstrap/cache"
chmod -R 775 "$APP_DIR/storage" "$APP_DIR/bootstrap/cache"

# ==============================================================================
# DATABASE
# ==============================================================================
echo "[7] Running migrations..."
php artisan migrate --force

# ==============================================================================
# LARAVEL OPTIMISE (production caches)
# ==============================================================================
echo "[8] Caching config / routes / views..."
php artisan optimize

# ==============================================================================
# QUEUE SESSIONS TABLE (if using database driver)
# ==============================================================================
echo "[9] Ensuring sessions & queue tables exist..."
php artisan queue:table 2>/dev/null || true
php artisan session:table 2>/dev/null || true
php artisan migrate --force

# ==============================================================================
# RESTART SERVICES
# ==============================================================================
echo "[10] Restarting services..."
systemctl restart php-fpm
systemctl reload nginx
supervisorctl reread
supervisorctl update
supervisorctl restart hali-worker:*
supervisorctl restart hali-scheduler 2>/dev/null || true

# ==============================================================================
# SSL — Let's Encrypt (only on first deploy with a real domain)
# ==============================================================================
if [ "$REDEPLOY" = false ] && [ -n "$DOMAIN" ] && [ "$DOMAIN" != "localhost" ]; then
    echo "[11] Setting up SSL (Let's Encrypt)..."
    # Install certbot — works on both Ubuntu (apt) and Amazon Linux (dnf)
    if command -v apt-get &>/dev/null; then
        apt-get install -y -qq certbot python3-certbot-nginx
    elif command -v dnf &>/dev/null; then
        dnf install -y certbot python3-certbot-nginx 2>/dev/null || true
    fi

    if command -v certbot &>/dev/null; then
        certbot --nginx \
            -d "$DOMAIN" \
            --non-interactive \
            --agree-tos \
            --email "portal@haliaccess.org" \
            --redirect || echo "  SSL setup failed — DNS must point to this server's IP first"
        systemctl reload nginx
    else
        echo "  WARNING: certbot not found. Install manually and run:"
        echo "    certbot --nginx -d $DOMAIN --email portal@haliaccess.org --agree-tos --redirect"
    fi

    # Auto-renew cron (certbot usually installs a systemd timer, this is a fallback)
    (crontab -l 2>/dev/null; echo "0 3 * * * certbot renew --quiet --post-hook 'systemctl reload nginx'") \
        | sort -u | crontab -
fi

# ==============================================================================
# DONE
# ==============================================================================
echo ""
echo "=============================================="
echo "  Deploy complete!"
echo "  $(date)"
echo "=============================================="
if [ -n "$DOMAIN" ]; then
    echo "  URL : https://$DOMAIN"
else
    EC2_IP=$(curl -s http://169.254.169.254/latest/meta-data/public-ipv4 2>/dev/null || echo "unknown")
    echo "  URL : http://$EC2_IP"
fi
echo "  Logs: $APP_DIR/storage/logs/"
echo ""
echo "  Queue worker: supervisorctl status"
echo "  Nginx logs  : /var/log/nginx/error.log"
echo ""
