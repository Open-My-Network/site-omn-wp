FROM wordpress:latest

# Install necessary packages, including gnupg for adding keys
RUN apt-get update && \
    apt-get install -y lsb-release apt-transport-https ca-certificates wget gnupg && \
    wget -q https://packages.sury.org/php/apt.gpg -O- | apt-key add - && \
    echo "deb https://packages.sury.org/php/ $(lsb_release -sc) main" | tee /etc/apt/sources.list.d/php.list && \
    apt-get update && apt-get install -y php8.0-fpm && \
    apt-get clean && rm -rf /var/lib/apt/lists/*

# Copy your custom WordPress themes and plugins
COPY ./wordpress/wp-content/themes /var/www/html/wp-content/themes
COPY ./wordpress/wp-content/plugins /var/www/html/wp-content/plugins

# Set correct permissions (important for production)
RUN chown -R www-data:www-data /var/www/html && \
    find /var/www/html -type d -exec chmod 755 {} \; && \
    find /var/www/html -type f -exec chmod 644 {} \;

# Expose port 80 for Nginx
EXPOSE 80

# Start PHP-FPM
CMD ["php-fpm", "-F"]
