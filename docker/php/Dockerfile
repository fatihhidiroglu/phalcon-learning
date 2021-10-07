FROM php:7.4.13-fpm

# Copy composer.lock and composer.json
# COPY composer.lock composer.json /var/www/

#Set user
USER root

# Set working directory
WORKDIR /var/www/

# Install dependencies
RUN apt-get update && apt-get install -y \
    build-essential \
    mariadb-client \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    libonig-dev \
    zlib1g-dev\
    locales \
    zip \
    jpegoptim optipng pngquant gifsicle \
    vim \
    unzip \
    git \
    curl

ARG PSR_VERSION=1.1.0
ARG PHALCON_VERSION=4.1.2
ARG PHALCON_EXT_PATH=php7/64bits

RUN set -xe && \
        # Download PSR, see https://github.com/jbboehr/php-psr
        curl -LO https://github.com/jbboehr/php-psr/archive/v${PSR_VERSION}.tar.gz && \
        tar xzf ${PWD}/v${PSR_VERSION}.tar.gz && \
        # Download Phalcon
        curl -LO https://github.com/phalcon/cphalcon/archive/v${PHALCON_VERSION}.tar.gz && \
        tar xzf ${PWD}/v${PHALCON_VERSION}.tar.gz && \
        docker-php-ext-install -j $(getconf _NPROCESSORS_ONLN) \
            ${PWD}/php-psr-${PSR_VERSION} \
            ${PWD}/cphalcon-${PHALCON_VERSION}/build/${PHALCON_EXT_PATH} \
        && \
        # Remove all temp files
        rm -r \
            ${PWD}/v${PSR_VERSION}.tar.gz \
            ${PWD}/php-psr-${PSR_VERSION} \
            ${PWD}/v${PHALCON_VERSION}.tar.gz \
            ${PWD}/cphalcon-${PHALCON_VERSION} \
        && \
        php -m

COPY docker-phalcon-* /usr/local/bin/

# phalcon dev tools
RUN curl -LOs https://github.com/phalcon/phalcon-devtools/releases/download/v4.2.0/phalcon.phar
RUN chmod +x phalcon.phar
RUN mv phalcon.phar /usr/local/bin/phalcon

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install extensions
RUN docker-php-ext-install pdo_mysql
RUN docker-php-ext-install exif
RUN docker-php-ext-install pcntl
RUN docker-php-ext-configure gd --with-freetype-dir --with-jpeg-dir
RUN docker-php-ext-install gd

# Install composer
RUN curl -sS https://getcomposer.org/installer | php && mv composer.phar /usr/local/bin/composer

# Copy existing application directory contents
COPY . /var/www

# Copy existing application directory permissions
COPY --chown=www:www . /var/www

#Composer update for /vendor/autoload.php
# RUN composer update

# Expose port 9000 and start php-fpm server
EXPOSE 9000
CMD ["php-fpm"]
