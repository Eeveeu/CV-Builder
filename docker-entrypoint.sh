#!/bin/sh
set -e

# Ensure runtime directories exist and are writable
mkdir -p bootstrap/cache
mkdir -p storage/framework/cache storage/framework/sessions storage/framework/views
mkdir -p database
chmod -R 0775 bootstrap/cache storage database || true

# If there is no .env, copy example
if [ ! -f .env ]; then
  if [ -f .env.example ]; then
    cp .env.example .env
  else
    touch .env
  fi
fi

# If APP_KEY is not set in env and not present in .env, generate one
if [ -z "${APP_KEY}" ]; then
  # Try to read APP_KEY from .env
  if ! grep -q "^APP_KEY=" .env 2>/dev/null || [ -z "$(grep "^APP_KEY=" .env | cut -d'=' -f2-)" ]; then
    php artisan key:generate --force || true
  fi
fi

# Run any pending migrations? (not required for this app)
# php artisan migrate --force || true

# Start the built-in PHP server
exec php -S 0.0.0.0:10000 -t public
