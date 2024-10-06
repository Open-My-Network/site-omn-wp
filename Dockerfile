FROM wordpress:latest

RUN apt-get update && \
    apt-get install -y lsb-release apt-transport-https ca-certificates wget && \
    wget -q https://packages.sury.org/php/apt.gpg -O- | apt-key add - && \
    echo "deb https://packages.sury.org/php/ $(lsb_release -sc) main" | tee /etc/apt/sources.list.d/php.list && \
    apt-get update && apt-get install -y php8.0-fpm

CMD ["php-fpm", "-F"]

COPY ./wordpress/wp-content/themes /var/www/html/wp-content/themes
COPY ./wordpress/wp-content/plugins /var/www/html/wp-content/plugins

# Set correct permissions (important for production)
RUN chown -R www-data:www-data /var/www/html && \
    find /var/www/html -type d -exec chmod 755 {} \; && \
    find /var/www/html -type f -exec chmod 644 {} \;

EXPOSE 80