FROM php:latest

# Update apt-get en installeer de MariaDB-compatibele MySQL-extensie
RUN apt-get update && \
    apt-get install -y libmariadb-dev-compat libmariadb-dev && \
    docker-php-ext-install pdo pdo_mysql
