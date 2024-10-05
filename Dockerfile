FROM wordpress:latest

COPY ./wordpress/wp-content /var/www/html/wp-content

EXPOSE 80
