# Use the official WordPress image
FROM wordpress:latest

# Copy your WordPress files into the container
COPY . /var/www/html

# Expose ports
EXPOSE 80