# FROM php:8.2-cli

# RUN apt-get update && apt-get install -y \
#   git \
#   curl \
#   unzip \
#   libpq-dev \
#   libzip-dev \
#   && docker-php-ext-install pdo_pgsql \
#   && rm -rf /var/lib/apt/lists/*

# COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# WORKDIR /var/www/html

# COPY . .

# RUN composer install \
#   --no-dev \
#   --optimize-autoloader \
#   --no-interaction

# RUN chmod -R 775 storage bootstrap/cache

# CMD ["sh", "-c", "php artisan serve --host=0.0.0.0 --port=${PORT:-8000}"]

FROM richarvey/nginx-php-fpm:3.1.6

COPY . .

ENV SKIP_COMPOSER 1
ENV WEBROOT /var/www/html/public
ENV PHP_ERRORS_STDERR 1
ENV RUN_SCRIPTS 1
ENV REAL_IP_HEADER 1

ENV APP_ENV production
ENV APP_DEBUG false
ENV LOG_CHANNEL stderr

ENV COMPOSER_ALLOW_SUPERUSER 1

CMD ["/start.sh"]