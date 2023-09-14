#The official PHP runtime as the main image and instal dependencies
FROM php:8.2-fpm
RUN apt-get update && apt-get install -y \
		libfreetype-dev \
		libjpeg62-turbo-dev \
		libpng-dev \
        zip \
        vim \
        unzip \
        git \
        curl \
        default-mysql-client \
	&& docker-php-ext-configure gd --with-freetype --with-jpeg \
	&& docker-php-ext-install -j$(nproc) gd \
    && docker-php-ext-install pdo pdo_mysql 

#Define working directory inside the container
WORKDIR /var/www/html

# Install Composer globally
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Copy just the composer files first and install dependencies
COPY composer.json composer.lock ./
RUN composer install --no-scripts --no-autoloader

# Copy the Laravel application code into the container
COPY . .

# Set execution permission for docker-entrypoint.sh
# RUN chmod +x /var/www/html/docker-entrypoint.sh

# # Set the custom entrypoint script as the entrypoint for the container
# ENTRYPOINT ["/var/www/html/docker-entrypoint.sh"]

#Increase memory limmit in the container
RUN echo "memory_limit=512M" > /usr/local/etc/php/conf.d/memory-limit.ini

#allow Composer to run as a superuser in the container
ENV COMPOSER_ALLOW_SUPERUSER 1

#update composer
# RUN composer self-update --2

# Install Laravel dependencies using Composer
# RUN composer install

# Check if .env file exists and if APP_KEY is empty, then generate a new key
RUN if [ ! -f .env ] || [ -z "$(grep 'APP_KEY=' .env)" ]; then \
    php artisan key:generate; \
fi

# Expose port 9000 for PHP-FPM
EXPOSE 8000

# Start PHP-FPM using php nuilt in dev server instead of configuring a web server (nginx or apache) to expose the php-fpm container
CMD php artisan migrate && php artisan schedule:run && php -S 0.0.0.0:8000 -t public

