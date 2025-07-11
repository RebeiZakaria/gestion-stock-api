FROM php:8.2-apache

# Install required extensions
RUN apt-get update && apt-get install -y \
    libzip-dev zip unzip git curl libpng-dev libonig-dev libxml2-dev \
    && docker-php-ext-install pdo_mysql mbstring zip exif pcntl

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Set working directory
WORKDIR /var/www

# Copy project files
COPY . /var/www

# Move public/ to html/ so Apache serves Laravel correctly
RUN rm -rf /var/www/html && mv /var/www/public /var/www/html

# Fix permissions
RUN chown -R www-data:www-data /var/www && chmod -R 755 /var/www

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Expose port 80
EXPOSE 80
