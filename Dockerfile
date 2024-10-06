FROM wordpress:latest

RUN apt-get update && apt-get install -y php8.0-fpm
CMD ["php-fpm", "-F"]

COPY ./wordpress/wp-content/themes /var/www/html/wp-content/themes
COPY ./wordpress/wp-content/plugins /var/www/html/wp-content/plugins

# Set correct permissions (important for production)
RUN chown -R www-data:www-data /var/www/html && \
    find /var/www/html -type d -exec chmod 755 {} \; && \
    find /var/www/html -type f -exec chmod 644 {} \;

EXPOSE 80