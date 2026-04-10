#!/bin/bash
# =============================================================================
# HALI Portal — Railway / Render Build Script
# This runs automatically on the platform during each deploy.
# Railway:  set this as the Build Command
# Render:   set this as the Build Command (in render.yaml or dashboard)
# =============================================================================
set -euo pipefail

echo "=============================================="
echo "  HALI Portal — Platform Build"
echo "  $(date)"
echo "=============================================="

# ==============================================================================
# PHP DEPENDENCIES
# ==============================================================================
echo "[1] Installing PHP dependencies (no-dev)..."
composer install \
    --no-dev \
    --optimize-autoloader \
    --no-interaction \
    --prefer-dist

# ==============================================================================
# FRONTEND ASSETS
# ==============================================================================
echo "[2] Building frontend assets..."
npm ci
npm run build

# ==============================================================================
# LARAVEL BOOTSTRAP
# ==============================================================================
echo "[3] App setup..."
php artisan key:generate --force 2>/dev/null || true
php artisan storage:link --force 2>/dev/null || true

echo "[4] Caching for production..."
php artisan optimize

# ==============================================================================
# DATABASE
# ==============================================================================
echo "[5] Running migrations..."
php artisan migrate --force

echo ""
echo "  Build complete."
