#!/bin/sh
set -e

cd /var/www

exec php artisan queue:work \
  --verbose \
  --tries=3 \
  --timeout=90