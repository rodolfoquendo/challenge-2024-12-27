#!/usr/bin/env sh
set -e

mkdir -p /var/log/nginx
touch /var/log/nginx/access.log /var/log/nginx.error.log
chmod 777 /var/log/nginx/access.log /var/log/nginx.error.log
COMPOSER_NEEDED=$([ ! -f "/platform/vendor/autoload.php" ] && [ -f "/platform/composer.json" ] && echo "1" || echo "0")
if [ "$COMPOSER_NEEDED" == "1" ] ; then
    composer install --no-dev -o
fi
MIGRATE_NEEDED=$([ -f "/platform/vendor/autoload.php" ] && [ -f "/platform/composer.json" ] && [ -f "/platform/artisan" ] && [ ! -f "/platform/.nodb" ] && echo "1" || echo "0")

if [ "$MIGRATE_NEEDED" == "1" ] ; then
    php artisan migrate
    php artisan config:cache
    php artisan route:cache
fi

php-fpm