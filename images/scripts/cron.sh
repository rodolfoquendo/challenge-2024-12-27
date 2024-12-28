#!/bin/sh
if [ -f /platform/.env ]; then
    source /platform/.env
fi
if [ -f /platform/artisan ]; then
    /usr/local/bin/php /platform/artisan schedule:run >> /var/log/cron.log
fi




