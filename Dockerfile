# Use official PHP 8.2 with Apache
FROM php:8.2-apache

# Install dependencies
RUN apt-get update && apt-get install -y \
    libpq-dev \
    zip \
    unzip \
    && docker-php-ext-install pdo pdo_pgsql \
    && a2enmod rewrite proxy_fcgi \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Configure Apache for Railway
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf && \
    sed -i 's/Listen 80/Listen 9000/' /etc/apache2/ports.conf

# Create and enable custom site config
COPY docker/apache.conf /etc/apache2/sites-available/laravel.conf
RUN a2dissite 000-default && \
    a2ensite laravel && \
    a2enmod rewrite

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- \
    --install-dir=/usr/local/bin --filename=composer

# Set working directory
WORKDIR /var/www/html

# Copy files
COPY . .

# Install PHP dependencies
RUN composer install --optimize-autoloader --no-dev

# Fix permissions
RUN chown -R www-data:www-data /var/www/html && \
    find /var/www/html -type d -exec chmod 755 {} \; && \
    find /var/www/html -type f -exec chmod 644 {} \; && \
    chmod -R 775 storage bootstrap/cache

# Use production PHP config
RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"

# Clear Laravel caches
RUN php artisan route:clear && \
    php artisan config:clear && \
    php artisan cache:clear

EXPOSE 9000

CMD ["apache2-foreground"]