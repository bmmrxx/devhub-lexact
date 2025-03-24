FROM php:latest

# Install PHP extentions
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    git \
    curl \
    libcurl4-openssl-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install mysqli pdo_mysql gd curl intl \
    && apt-get clean

# Configure Git with user email and name (auto sign-in)
RUN git config --global user.email "mitzbo@outlook.com" \
    && git config --global user.name "bmmrxx"

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
