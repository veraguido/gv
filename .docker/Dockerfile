FROM php:7.1.8-apache

MAINTAINER Guido Vera

COPY . /var/www/gv
COPY .docker/vhost.conf /etc/apache2/sites-available/000-default.conf

WORKDIR /var/www/gv

RUN apt-get update && apt-get install -y \
		libfreetype6-dev \
		libjpeg62-turbo-dev \
		libmcrypt-dev \
		libpng-dev \
		cron \
    && docker-php-ext-install mbstring pdo pdo_mysql mcrypt \
    && docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/ \
    && docker-php-ext-install -j$(nproc) gd \
    && chown -R www-data:www-data /var/www/gv \
    && a2enmod rewrite \
    && ln -fsn /usr/local/bin/php /usr/bin/php