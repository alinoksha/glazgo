FROM php:8.1-fpm

RUN apt update && \
    apt install -y zip unzip git curl libzip-dev zlib1g-dev libpng-dev

RUN docker-php-ext-install gd zip

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
