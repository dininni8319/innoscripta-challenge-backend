# Use the official PHP-FPM base image with the desired version of PHP
FROM php:8.2-apache

WORKDIR /var/www/html

# Install dependencies
RUN apt-get update -y && apt-get install -y \ 
  libonig-dev \
  libzip-dev \
  unzip 

RUN docker-php-ext-install pdo_mysql mbstring zip exif pcntl

RUN a2enmod rewrite

COPY vhost.conf /etc/apache2/sites-available/000-default.conf

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Copy the Laravel files
COPY . .

RUN composer install --no-scripts --no-autoloader

RUN composer dump-autoload --optimize

RUN chown -R www-data:www-data storage bootstrap/cache

EXPOSE 8000

CMD ["apache2-foreground"]