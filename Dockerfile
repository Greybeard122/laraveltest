# Use official PHP-FPM image
FROM php:8.2-fpm

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

# Set working directory inside the container
WORKDIR /var/www

# Copy Laravel project files into the container
COPY . .

# Install Composer globally
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Ensure artisan exists before running composer
RUN ls -la /var/www

# Create a new non-root user for security
RUN useradd -m laravel
USER laravel

# Install dependencies as a non-root user
RUN composer install --no-dev --optimize-autoloader

# Switch back to root for permissions
USER root
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

# Expose PHP-FPM port
EXPOSE 9000

# Start PHP-FPM
CMD ["php-fpm"]
