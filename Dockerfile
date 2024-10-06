# Use the official WordPress image as a base
FROM wordpress:6.5.2-fpm-alpine

# Set the working directory to the root of the WordPress installation
WORKDIR /var/www/html

# Copy the entire wp-content directory (including themes, plugins, uploads, etc.)
COPY ./wp-content /var/www/html/wp-content

# Set proper permissions for WordPress
RUN chown -R www-data:www-data /var/www/html/wp-content

# Expose port 80 for HTTP traffic
EXPOSE 80

# Start the PHP-FPM process
CMD ["php-fpm"]