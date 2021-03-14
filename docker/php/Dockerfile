FROM php:7.4-fpm

ENV DEBIAN_FRONTEND="noninteractive" \
    SYMFONY_ALLOW_APPDEV=1 \
    COMPOSER_ALLOW_SUPERUSER=1

# Set timezone
RUN rm /etc/localtime && ln -s /usr/share/zoneinfo/Europe/Paris /etc/localtime

RUN apt-get update && apt-get install -y \
    git \
    unzip \
    zlib1g-dev \
    libfontconfig1 \
    libxrender1 \
    libbz2-dev \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libmcrypt-dev \
    libpng-dev \
    libicu-dev \
    libz-dev \
    libmemcached-dev \
    libxml2-dev \
    libssl-dev \
    libcurl4-gnutls-dev \
    wkhtmltopdf \
    xvfb \
    libzip-dev \
    libonig-dev

RUN docker-php-ext-install pdo pdo_mysql mysqli bcmath zip bz2 exif intl pcntl shmop  gettext mbstring ftp curl opcache sockets

# install xdebug
RUN pecl install -f xdebug-2.9.8 \
    && docker-php-ext-enable xdebug \
    && echo "xdebug.remote_enable=1" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo "xdebug.remote_connect_back=0" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo "xdebug.idekey=\"PHPSTORM\"" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo "xdebug.remote_autostart=1" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo "xdebug.default_enable=1" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo "xdebug.remote_handler=dbgp" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo "xdebug.profiler_enable=0" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo "xdebug.remote_host=10.254.254.254" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini

RUN rm -rf /var/lib/apt/list*

# user
RUN useradd -ms /bin/bash symfony
USER symfony

WORKDIR /symfony

COPY conf/php-fpm.conf /etc/php-fpm.conf
COPY conf/php.ini /usr/local/etc/php/conf.d/100-php.ini
