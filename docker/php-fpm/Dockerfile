FROM php:7.3-fpm

RUN docker-php-ext-install pdo pdo_mysql

RUN apt-get update && \
    apt-get install -y \
        libfreetype6-dev \
        libjpeg62-turbo-dev \
        libgd-dev 
RUN docker-php-ext-configure gd \ 
    --with-freetype-dir=/usr/include/ \ 
    --with-jpeg-dir=/usr/include/
RUN docker-php-ext-install gd
