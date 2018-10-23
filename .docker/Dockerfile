FROM php:7.1-fpm

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
    && docker-php-ext-install mbstring pdo pdo_mysql mcrypt opcache \
    && docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/ \
    && docker-php-ext-install -j$(nproc) gd \
    && chown -R www-data:www-data /var/www/html \
    && ln -fsn /usr/local/bin/php /usr/bin/php

RUN pecl install xdebug
RUN pecl install swoole
ADD php.ini /usr/local/etc/php/conf.d/99-gv.ini

EXPOSE 80
EXPOSE 8080