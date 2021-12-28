FROM php:8.1-fpm-alpine as base

RUN apk add --no-cache \
		acl \
		fcgi \
		file \
        git \
		gettext \
        gnu-libiconv \
        libzip \
        zip \
	;

RUN set -eux; \
	apk add --no-cache --virtual .build-deps \
        $PHPIZE_DEPS \
        icu-dev \
        libzip-dev \
		zlib-dev \
	; \
	docker-php-ext-configure zip; \
	docker-php-ext-install -j$(nproc) \
		pdo_mysql \
		zip \
	; \
	apk del .build-deps

FROM base as dev

ARG APP_ENV=dev
ARG XDEBUG_VERSION=3.1.2

RUN set -eux; \
    apk add --no-cache --virtual .build-deps \
        $PHPIZE_DEPS \
        icu-dev \
        libzip-dev \
        zlib-dev \
    ; \
    pecl install \
        xdebug-$XDEBUG_VERSION \
    ; \
    docker-php-ext-enable \
        xdebug \
    ; \
    apk del .build-deps

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

FROM base as prod

ARG APP_ENV=prod

RUN docker-php-ext-enable \
    	opcache \
    ;
