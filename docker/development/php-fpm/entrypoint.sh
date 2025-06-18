#!/bin/sh
set -e

# Check if $UID and $GID are set, else fallback to default (1000:1000)
USER_ID=${UID:-1000}
GROUP_ID=${GID:-1000}

# Fix file ownership and permissions using the passed UID and GID
echo "Fixing file permissions with UID=${USER_ID} and GID=${GROUP_ID}..."
chown -R ${USER_ID}:${GROUP_ID} /var/www || echo "Some files could not be changed"

# Composer dependencies
if [ ! -d /var/www/vendor ]; then
  echo "Installing Composer dependencies…"
  composer install --no-dev --optimize-autoloader --no-interaction
fi

# NPM dependencies
if [ -f /var/www/package.json ] && [ ! -d /var/www/node_modules ]; then
  echo "Installing NPM dependencies…"
  npm install
fi

# Generate app key if missing
if ! grep -q "^APP_KEY=" /var/www/.env || [ -z "$(grep "^APP_KEY=" /var/www/.env | cut -d'=' -f2)" ]; then
  echo "Generating APP_KEY…"
  php artisan key:generate --ansi --force
fi

# Clear configurations to avoid caching issues in development
echo "Clearing configurations..."
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Run the default command (e.g., php-fpm or bash)
exec "$@"
