FROM php:8.2.0-fpm

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN apt-get update
RUN apt-get install -y git zip zlib1g-dev libzip-dev zip && docker-php-ext-install zip pdo pdo_mysql

RUN \
    pecl install apcu && \
    pecl install redis && \
    pecl install xdebug

RUN \
    docker-php-ext-enable apcu && \
    docker-php-ext-enable redis && \
    docker-php-ext-enable xdebug
