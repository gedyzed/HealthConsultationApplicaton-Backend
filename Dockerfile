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

# Set Apache to listen on Railway's expected port (9000)
RUN sed -i 's/Listen 80/Listen 9000/' /etc/apache2/ports.conf && \
    sed -i 's/:80/:9000/' /etc/apache2/sites-enabled/000-default.conf && \
    echo "ServerName localhost" >> /etc/apache2/apache2.conf

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- \
    --install-dir=/usr/local/bin --filename=composer

# Set working directory
WORKDIR /var/www/html

# Copy files
COPY . .

# Install PHP dependencies
RUN composer install --optimize-autoloader --no-dev

# Set permissions
RUN mkdir -p storage bootstrap/cache && \
    chown -R www-data:www-data storage bootstrap/cache

# Use production PHP config
RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"

# Expose Railway's expected port
EXPOSE 9000

# Start Apache
CMD ["apache2-foreground"]
