#!/bin/sh
set -e

cd /var/www/html

mkdir -p database storage/app/public storage/framework/cache storage/framework/sessions storage/framework/views storage/logs bootstrap/cache
touch database/database.sqlite

if [ -z "$APP_KEY" ]; then
    echo "ERROR: APP_KEY is not set. Generate one with:"
    echo "  docker run --rm \$(docker build -q .) php artisan key:generate --show"
    echo "and set it as the APP_KEY environment variable."
    exit 1
fi

php artisan migrate --force

if [ ! -e public/storage ]; then
    php artisan storage:link
fi

chown -R www-data:www-data database storage bootstrap/cache

exec "$@"
