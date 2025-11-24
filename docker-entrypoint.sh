#!/bin/sh
set -e

echo "[entrypoint] Creating runtime directories..."
mkdir -p bootstrap/cache
mkdir -p storage/framework/cache storage/framework/sessions storage/framework/views
mkdir -p database
chmod -R 0775 bootstrap/cache storage database || true

echo "[entrypoint] Checking .env file..."
if [ ! -f .env ]; then
  if [ -f .env.example ]; then
    cp .env.example .env
    echo "[entrypoint] Copied .env.example to .env"
  else
    touch .env
    echo "[entrypoint] Created empty .env"
  fi
fi

echo "[entrypoint] Generating APP_KEY if needed..."
if [ -z "${APP_KEY}" ]; then
  if ! grep -q "^APP_KEY=base64:" .env 2>/dev/null || [ -z "$(grep "^APP_KEY=" .env 2>/dev/null | cut -d'=' -f2-)" ]; then
    php artisan key:generate --force 2>/dev/null || echo "[entrypoint] Warning: artisan key:generate failed"
  fi
fi

echo "[entrypoint] Clearing caches..."
php artisan config:clear 2>/dev/null || true
php artisan route:clear 2>/dev/null || true
php artisan view:clear 2>/dev/null || true

echo "[entrypoint] Starting PHP built-in server on 0.0.0.0:10000..."
exec php -S 0.0.0.0:10000 -t public
