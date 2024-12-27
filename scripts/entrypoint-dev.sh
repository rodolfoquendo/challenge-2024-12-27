#!/usr/bin/env sh
set -e


MIGRATE_NEEDED=$([ -f "/platform/vendor/autoload.php" ] && [ -f "/platform/composer.json" ] && [ -f "/afluenta-platform/artisan" ] && [ ! -f "/platform/.nodb" ] && echo "1" || echo "0")

php-fpm -D
mkdir -p /var/log/nginx
touch /var/log/nginx/access.log /var/log/nginx.error.log
chmod 777 /var/log/nginx/access.log /var/log/nginx.error.log

composer install

if [ "$MIGRATE_NEEDED" == "1" ] ; then
    php artisan migrate
    php artisan config:cache
    php artisan route:cache
fi

nginx -g 'daemon off;'