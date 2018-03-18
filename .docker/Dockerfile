FROM php:7.1.8-apache

COPY . /var/www/gv
COPY .docker/vhost.conf /etc/apache2/sites-available/000-default.conf

WORKDIR /var/www/gv

RUN apt-get update && apt-get install -y \
		libfreetype6-dev \
		libjpeg62-turbo-dev \
		libmcrypt-dev \
		libpng-dev \
        apt-utils \
		cron \
	&& apt-get install --no-install-recommends --assume-yes --quiet ca-certificates curl git \
	&& rm -rf /var/lib/apt/lists/* \
    && docker-php-ext-install mbstring pdo pdo_mysql mcrypt \
    && docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/ \
    && docker-php-ext-install -j$(nproc) gd \
    && chown -R www-data:www-data /var/www/gv \
    && a2enmod rewrite \
    && ln -fsn /usr/local/bin/php /usr/bin/php

RUN pecl install xdebug
ADD .docker/php.ini /usr/local/etc/php/php.ini

EXPOSE 80

ENV COMPOSER_HOME /var/www/.composer
RUN curl -sS https://getcomposer.org/installer | php -- \
    --install-dir=/usr/bin \
    --filename=composer

RUN chown -R www-data:www-data /var/www/
RUN mkdir -p $COMPOSER_HOME/cache
VOLUME $COMPOSER_HOME