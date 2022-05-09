FROM php:8.1-fpm

RUN apt-get update \
    && apt-get install -y zip nano git gcc make autoconf libc-dev pkg-config zlib1g-dev

RUN apt-get install -y \
        libfreetype6-dev \
        libjpeg62-turbo-dev \
        libmcrypt-dev \
        libxml2-dev \
        libzip-dev \
        file \
        zip \
    && docker-php-ext-install -j$(nproc) \
        pdo_mysql \
        soap \
        zip

# Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN docker-php-ext-configure gd --with-freetype=/usr/include/ --with-jpeg=/usr/include/ \
    && docker-php-ext-install -j$(nproc) gd

# xDebug
RUN yes | pecl install xdebug
RUN docker-php-ext-enable xdebug

COPY ./php.ini $PHP_INI_DIR/
COPY ./xdebug.ini $PHP_INI_DIR/conf.d/

RUN echo "pm.max_children = 15" >> /usr/local/etc/php-fpm.conf
RUN echo "pm.max_requests = 500" >> /usr/local/etc/php-fpm.conf

RUN usermod -u 1000 www-data \
    && usermod -G staff www-data

RUN apt-get clean autoclean \
    && apt-get autoremove --yes

WORKDIR "/code"
