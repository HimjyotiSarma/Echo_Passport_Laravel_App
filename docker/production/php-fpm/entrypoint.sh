#!/bin/sh
set -e

# Create Storage folder if not Initialized
mkdir -p /var/www/storage

# Initialize Storage if empty
if [ -z "$(ls -A /var/www/storage 2>/dev/null)" ]; then
	echo "Initializing storage directory..."
	cp -R /var/www/storage-init/. /var/www/storage
	chown -R  www-data:www-data /var/www/storage
fi

# Remove storage-init directory
rm -rf /var/www/storage-init

# If the first argument is "php-fpm", run migrations and optimize config before starting php-fpm otherwise, execute the provided command (e.g., for artisan commands)
if [ "$1" = "php-fpm" ]; then
	# Run Laravel Migration
	php artisan migrate --force

	# Clear and Cache configuration
	php artisan optimize
fi

exec "$@"
