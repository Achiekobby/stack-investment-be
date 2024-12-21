# Use Ubuntu as the base image
FROM ubuntu:latest

# Install system dependencies
RUN apt-get update && apt-get install -y \
    apache2 \
    php \
    libapache2-mod-php \
    php-mysql \
    php-cli \
    php-fpm \
    php-json \
    php-common \
    php-mysql \
    php-zip \
    php-gd \
    php-mbstring \
    php-curl \
    php-xml \
    php-pear \
    php-bcmath \
    curl \
    unzip

# Install MySQL
RUN DEBIAN_FRONTEND=noninteractive apt-get install -y mysql-server

# Secure MySQL installation
RUN service mysql start && \
    mysql -e "ALTER USER 'root'@'localhost' IDENTIFIED WITH mysql_native_password BY 'rootpassword';FLUSH PRIVILEGES;"

# Install Composer globally
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Set working directory
WORKDIR /var/www/html

# Copy existing application directory contents
COPY . .

COPY .docker/000-default.conf /etc/apache2/sites-available/000-default.conf

# Set permissions
RUN chown -R www-data:www-data /var/www/html && chmod -R 755 /var/www/html/storage && chmod -R 755 /var/www/html/bootstrap/cache

# Expose port 80 and 3306
EXPOSE 80 3306

# Start Apache and MySQL services
CMD service mysql start && apachectl -D FOREGROUND