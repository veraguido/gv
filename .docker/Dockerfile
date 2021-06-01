FROM php:8.0-fpm

WORKDIR /var/www/html

RUN apt-get update && apt-get install -y \
		libfreetype6-dev \
		libjpeg62-turbo-dev \
		libmcrypt-dev \
		libpng-dev \
        apt-utils \
		cron \
	&& apt-get install --no-install-recommends --assume-yes --quiet ca-certificates curl git \
	&& rm -rf /var/lib/apt/lists/* \
    && docker-php-ext-install pdo pdo_mysql opcache \
    && docker-php-ext-configure gd  \
    && docker-php-ext-install -j$(nproc) gd \
    && chown -R www-data:www-data /var/www/html \
    && ln -fsn /usr/local/bin/php /usr/bin/php

RUN pecl install xdebug
#RUN pecl install swoole
RUN pecl install redis && docker-php-ext-enable redis

ADD php.ini /usr/local/etc/php/conf.d/99-gv.ini
EXPOSE 80