FROM 368539636127.dkr.ecr.us-east-2.amazonaws.com/roquendo/php8-nginx
# If cron is needed
# FROM 368539636127.dkr.ecr.us-east-2.amazonaws.com/roquendo/php8-nginx-cron
COPY configs/nginx.conf /etc/nginx/http.d/default.conf
COPY src /platform/
COPY scripts/entrypoint.sh /scripts/entrypoint.sh

RUN composer install --no-dev
RUN rm -rf /platform/.env
RUN chmod -R 777 /afluenta-platform/storage /scripts/entrypoint.sh
