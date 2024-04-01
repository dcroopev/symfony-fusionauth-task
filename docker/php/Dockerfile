FROM php:8.3-fpm-alpine

WORKDIR "/var/www/html/symfony-fusionauth-task"

RUN apk update && apk add libgcrypt-dev libxslt-dev

RUN apk add icu-dev
RUN docker-php-ext-configure intl


RUN docker-php-ext-install pdo pdo_mysql intl xsl
ENV COMPOSER_ALLOW_SUPERUSER=1
COPY --from=composer:2.4 /usr/bin/composer /usr/bin/composer

COPY ./composer.* ./

RUN composer install --prefer-dist --no-dev --no-scripts --no-progress --no-interaction
#
# copy application files to the working directory
COPY ./ ./

RUN composer dump-autoload --optimize
