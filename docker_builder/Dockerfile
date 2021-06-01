# Install PHP + apache
FROM php:7.4-apache
RUN apt-get update -y && apt-get upgrade -y
RUN apt-get -y install openssl libssl-dev \
    && docker-php-ext-install mysqli pdo pdo_mysql

# Install unzip utility and libs needed by zip PHP extension
RUN apt-get update && apt-get install -y \
    zlib1g-dev \
    libzip-dev \
    unzip
RUN docker-php-ext-install zip

# Install git
RUN apt-get install -y git

# Install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Install redis
RUN pecl install redis-5.1.1 && docker-php-ext-enable redis
