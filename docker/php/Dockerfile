FROM php:8.2-fpm

RUN apt-get update && apt-get install -y \
        openssl \
        git \
        zlib1g \
        zlib1g-dev \
        libfreetype6-dev \
        libssl-dev \
        libjpeg62-turbo-dev \
        libmagickwand-dev \
        libmcrypt-dev \
        libpng-dev \
        libicu-dev \
        libzip-dev \
        libpq-dev \
        zip \
        unzip \
        librabbitmq-dev \
    ;

# Install PHP extensions
RUN docker-php-ext-install pdo_pgsql gd intl zip

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \
    && mkdir /.composer/ \
    && chmod 777 /.composer/

# Install Symfony
RUN  curl -sS https://get.symfony.com/cli/installer | bash \
    && mv /root/.symfony5/bin/symfony /usr/local/bin/symfony \
    && mkdir /.symfony/ \
    && chmod 777 /.symfony/
