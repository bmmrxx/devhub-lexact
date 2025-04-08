FROM php:latest

RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libicu-dev \
    libzip-dev \
    git \
    curl \
    unzip \
    libcurl4-openssl-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install mysqli pdo_mysql gd curl intl zip \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Configure Git
RUN git config --global --add safe.directory /var/www/html \
    && git config --global user.email "mitzbo@outlook.com" \
    && git config --global user.name "bmmrxx"

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer