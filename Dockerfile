FROM wordpress:latest

COPY ./wordpress /var/www/html

# Expose the WordPress port
EXPOSE 80