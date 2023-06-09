# Base image
FROM php:8.1-fpm as base

# Install system dependencies
RUN apt-get update && apt-get install --no-install-recommends -y \
    mariadb-server \
    mariadb-client \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    nginx && \
    rm -rf /var/lib/apt/lists/*

# Add docker-php-extension-installer script
ADD https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/

# Install php extensions
RUN chmod +x /usr/local/bin/install-php-extensions && \
    install-php-extensions \
    @composer \
    bcmath \
    imagick  \
    hash  \
    fileinfo  \
    mbstring \
    gd \
    dom \
    exif \
    intl \
    curl \
    pdo_mysql \
    mysqli \
    pcntl \
    zip \
    simplexml \
    iconv

# Install WP-CLI
RUN curl -O https://raw.githubusercontent.com/wp-cli/builds/gh-pages/phar/wp-cli.phar && \
    chmod +x wp-cli.phar && \
    mv wp-cli.phar /usr/local/bin/wp

# Copy configuration files
COPY docker/config/nginx/nginx.conf /etc/nginx/nginx.conf
COPY docker/config/nginx/default.conf /etc/nginx/sites-enabled/default.conf
COPY docker/config/php/fpm-pool.conf /usr/local/etc/php-fpm.d/www.conf
COPY docker/config/php/php.ini /usr/local/etc/php/php.ini

# Remove the default Nginx index.html
RUN rm -rf /var/www/html/*

# Set working directory
WORKDIR /var/www/html

# Start MariaDB
RUN service mariadb start && \
    mysql -e "SET PASSWORD FOR 'root'@'localhost' = PASSWORD('root');" && \
    mysql -e "CREATE DATABASE currency_wp;" && \
    mysql -e "FLUSH PRIVILEGES;"

COPY docker/config/mysql/.my.cnf /root

RUN service mariadb start && \
    wp core download --allow-root && \
    wp config create --dbname=currency_wp --dbhost=127.0.0.1 --dbuser=root --dbpass=root --allow-root --skip-check && \
    wp core install --url=localhost:8085 --title="Currency Exchange" --admin_user=admin --admin_password=admin --admin_email=admin@example.com --allow-root  && \
    wp plugin install https://github.com/igorhrcek/exchange-wp/archive/refs/tags/v1.0.0.zip --activate --allow-root && \
    wp user create testuser tesuser@example.com --role=subscriber --user_pass=pass --first_name=Toby --last_name=Tobius --allow-root

# Set ownership and permissions
RUN chown -R www-data:www-data /var/www/html && \
    chmod -R 775 /var/www/html && \
    find /var/www/html -type d -exec chmod 755 {} \;

# Expose ports
EXPOSE 8085

# Start Nginx, MySQL, and PHP-FPM
CMD service nginx start && service mariadb start && php-fpm
