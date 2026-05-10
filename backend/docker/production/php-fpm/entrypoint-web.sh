#!/bin/sh
set -e

cd /var/www

# Storage init (safe, runs once)
mkdir -p storage
if [ ! -f storage/.initialized ]; then
    echo "Initializing storage..."
    cp -R storage-init/. storage
    touch storage/.initialized
    chown -R www-data:www-data storage
fi

# Removed php artisan migrate --force to avoid issues with multiple web instances running migrations simultaneously
# Migrations should be handled separately, e.g., via a dedicated migration job or manually.

# Cache config (optional)
php artisan optimize || true

exec php-fpm