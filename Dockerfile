# Use the official WordPress image as a base
FROM wordpress:6.5.2

# Set the working directory to the root of the WordPress installation
WORKDIR /var/www/html

# Copy only the wp-content directory to avoid overwriting core WordPress files
COPY ./wordpress/wp-content /var/www/html/wp-content/

# Set proper permissions for WordPress files
RUN if [ -f /var/www/html/wp-config.php ]; then chmod 644 /var/www/html/wp-config.php; fi && \
    chown -R www-data:www-data /var/www/html && \
    find /var/www/html/ -type d -exec chmod 755 {} \; && \
    find /var/www/html/ -type f -exec chmod 644 {} \;


# Expose ports 80 for HTTP and 443 for HTTPS
EXPOSE 80 443