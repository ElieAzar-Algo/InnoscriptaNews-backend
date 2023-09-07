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
	&& docker-php-ext-configure gd --with-freetype --with-jpeg \
	&& docker-php-ext-install -j$(nproc) gd

#Define working directory inside the container
WORKDIR /var/www/html

# Install Composer globally
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Copy the Laravel application code into the container
COPY . .

#Increase memory limmit in the container
RUN echo "memory_limit=512M" > /usr/local/etc/php/conf.d/memory-limit.ini

#allow Composer to run as a superuser in the container
ENV COMPOSER_ALLOW_SUPERUSER 1

#update composer
RUN composer self-update --2

# Install Laravel dependencies using Composer
RUN composer install

# Check if .env file exists and if APP_KEY is empty, then generate a new key
RUN if [ ! -f .env ] || [ -z "$(grep 'APP_KEY=' .env)" ]; then \
    php artisan key:generate; \
fi
# Expose port 9000 for PHP-FPM
EXPOSE 8000

# Start PHP-FPM using php nuilt in dev server instead of configuring a web server (nginx or apache) to expose the php-fpm container
CMD ["php", "-S", "0.0.0.0:8000", "-t", "public"]

