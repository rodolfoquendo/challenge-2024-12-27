#!/usr/bin/env sh
set -e

MIGRATE_NEEDED=$([ -f "/afluenta-platform/vendor/autoload.php" ] && [ -f "/afluenta-platform/composer.json" ] && [ -f "/afluenta-platform/artisan" ] && [ ! -f "/afluenta-platform/.nodb" ] && echo "1" || echo "0")
COMPOSER_NEEDED=$([ ! -f "/platform/vendor/autoload.php" ] && [ -f "/platform/composer.json" ] && echo "1" || echo "0")


php-fpm -D
mkdir -p /var/log/nginx
touch /var/log/nginx/access.log /var/log/nginx.error.log
chmod 777 /var/log/nginx/access.log /var/log/nginx.error.log


if [ "$COMPOSER_NEEDED" == "1" ] ; then
    composer install --no-dev -o
fi

if [ "$MIGRATE_NEEDED" == "1" ] ; then
    php artisan migrate
    php artisan config:cache
    php artisan route:cache
fi

nginx -g 'daemon off;'