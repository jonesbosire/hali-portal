# =============================================================================
# HALI Access Partner Portal — Railway Dockerfile
# Multi-stage: build assets → runtime image
# =============================================================================

# ── Stage 1: Node build (compile CSS/JS assets) ──────────────────────────────
FROM node:22-slim AS node-builder

WORKDIR /app

COPY package.json package-lock.json ./
RUN npm ci

COPY resources/ resources/
COPY vite.config.js tailwind.config.js postcss.config.js ./
COPY public/ public/

RUN npm run build

# ── Stage 2: PHP runtime ──────────────────────────────────────────────────────
FROM php:8.3-cli-bookworm

# System deps
RUN apt-get update && apt-get install -y --no-install-recommends \
    git curl zip unzip \
    libzip-dev libpng-dev libjpeg62-turbo-dev libfreetype6-dev \
    libxml2-dev libcurl4-openssl-dev libonig-dev libicu-dev \
    libgmp-dev && \
    rm -rf /var/lib/apt/lists/*

# PHP extensions
# tokenizer is bundled+enabled by default; opcache is bundled but needs enable
RUN docker-php-ext-configure gd --with-freetype --with-jpeg && \
    docker-php-ext-install -j$(nproc) \
        pdo pdo_mysql mbstring xml curl zip bcmath \
        intl gd fileinfo pcntl && \
    docker-php-ext-enable opcache

# PHP production config
RUN echo "opcache.enable=1\n\
opcache.memory_consumption=128\n\
opcache.max_accelerated_files=10000\n\
opcache.validate_timestamps=0\n\
memory_limit=256M\n\
upload_max_filesize=10M\n\
post_max_size=12M\n\
expose_php=Off" > /usr/local/etc/php/conf.d/production.ini

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /app

# PHP deps first (layer cache)
COPY composer.json composer.lock ./
RUN composer install \
    --no-dev \
    --optimize-autoloader \
    --no-interaction \
    --prefer-dist \
    --no-scripts

# Copy full app
COPY . .

# Copy built assets from node stage
COPY --from=node-builder /app/public/build public/build

# Post-install scripts + storage setup
RUN composer run-script post-autoload-dump 2>/dev/null || true && \
    mkdir -p storage/logs \
              storage/framework/cache \
              storage/framework/sessions \
              storage/framework/views \
              bootstrap/cache && \
    chmod -R 775 storage bootstrap/cache && \
    php artisan storage:link --force 2>/dev/null || true

# Pre-warm caches that don't need DB (config/routes/views)
# APP_KEY placeholder lets artisan run without crashing
RUN APP_KEY=base64:placeholder== php artisan config:cache  2>/dev/null || true && \
    APP_KEY=base64:placeholder== php artisan route:cache   2>/dev/null || true && \
    APP_KEY=base64:placeholder== php artisan view:cache    2>/dev/null || true

EXPOSE 8080

# Start: clear stale cache, migrate, serve
CMD ["sh", "-c", \
  "php artisan config:cache && \
   php artisan route:cache && \
   php artisan view:cache && \
   php artisan migrate --force && \
   php artisan serve --host=0.0.0.0 --port=${PORT:-8080}"]
