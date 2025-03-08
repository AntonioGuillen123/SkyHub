# Usa una imagen base de Ubuntu
FROM php:8.4-fpm-alpine

RUN docker-php-ext-install pdo pdo_mysql

COPY ./tasks/cron/crontab /etc/crontabs/root

CMD ["crond", "-f"]