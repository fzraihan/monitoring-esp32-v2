FROM php:8.2-apache

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Copy semua file ke server
COPY . /var/www/html/

# Set permission
RUN chown -R www-data:www-data /var/www/html

EXPOSE 80