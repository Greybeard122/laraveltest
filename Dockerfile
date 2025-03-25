# Use official PHP-FPM image
FROM php:8.2-fpm

# Set working directory inside the container
WORKDIR /var/www

# Install system dependencies
RUN apt-get update && apt-get install -y \
    unzip \
    git \
    curl \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libonig-dev \
    libzip-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd mbstring zip pdo_mysql

# Ensure correct permissions
RUN chown -R www-data:www-data /var/www

# Copy Laravel project files into the container
COPY . .

# Download and install Composer safely
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
    && php composer-setup.php --install-dir=/usr/local/bin --filename=composer \
    && php -r "unlink('composer-setup.php');"

# Install PHP dependencies (prevents permission errors)
RUN composer install --no-dev --optimize-autoloader --no-interaction --no-progress

# Set correct permissions for storage and cache
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

# Expose PHP-FPM port
EXPOSE 9000

# Start PHP-FPM
CMD ["php-fpm"]
