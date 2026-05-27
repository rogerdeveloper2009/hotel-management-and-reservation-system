FROM php:8.4-apache

RUN docker-php-ext-install pdo pdo_mysql

WORKDIR /var/www/html
COPY . .

RUN apt-get update && apt-get install -y unzip curl git
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN composer install --no-dev --optimize-autoloader

EXPOSE 80
