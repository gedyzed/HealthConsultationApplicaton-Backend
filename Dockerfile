# Use official PHP 8.2 with Apache
FROM php:8.2-apache

# Install system dependencies
RUN apt-get update && apt-get install -y \
    libpq-dev \
    zip \
    unzip \
    && docker-php-ext-install pdo pdo_pgsql \
    && a2enmod rewrite proxy_fcgi \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Configure Apache to listen on Railway's default port (9000)
RUN sed -i 's/Listen 80/Listen 9000/' /etc/apache2/ports.conf && \
    sed -i 's/:80/:9000/' /etc/apache2/sites-enabled/000-default.conf && \
    echo "ServerName localhost" >> /etc/apache2/apache2.conf

# Set working directory to Laravel project root
WORKDIR /var/www

# Copy all files into the container
COPY . .

# Set Apache to serve the Laravel public directory
RUN sed -i 's|DocumentRoot /var/www/html|DocumentRoot /var/www/public|' /etc/apache2/sites-enabled/000-default.conf

# Install Composer (reliable method)
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" && \
    php composer-setup.php --install-dir=/usr/local/bin --filename=composer && \
    php -r "unlink('composer-setup.php');"

# Install Laravel PHP dependencies
RUN composer install --optimize-autoloader --no-dev

# Set storage and cache permissions
RUN mkdir -p storage bootstrap/cache && \
    chown -R www-data:www-data storage bootstrap/cache

# Use PHP production configuration
RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"

# Expose port 9000 for Railway
EXPOSE 9000

# Start Apache in the foreground
CMD ["apache2-foreground"]
