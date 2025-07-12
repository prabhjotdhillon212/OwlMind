# Use official PHP Apache image
FROM php:8.2-apache

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Install dependencies (like mysqli, pdo, etc. — optional, add as needed)
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Copy all project files into Apache's root directory
COPY . /var/www/html/

# Fix permissions
RUN chown -R www-data:www-data /var/www/html

# Apache config to allow .htaccess and clean URLs
RUN sed -i '/<Directory \/var\/www\/>/,/<\/Directory>/ s/AllowOverride None/AllowOverride All/' /etc/apache2/apache2.conf

# Expose port 80
EXPOSE 80
