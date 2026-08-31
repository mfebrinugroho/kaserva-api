FROM php:8.2-apache

RUN apt-get update && apt-get install -y \
  libpq-dev \
  libicu-dev \
  libzip-dev \
  libonig-dev \
  libxml2-dev \
  unzip \
  git \
  && docker-php-ext-install \
  pdo_pgsql \
  mbstring \
  bcmath \
  intl \
  zip \
  xml \
  && rm -rf /var/lib/apt/lists/*

RUN a2enmod rewrite

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY composer.json composer.lock ./

RUN composer install \
  --no-dev \
  --no-interaction \
  --prefer-dist \
  --no-scripts

COPY . .

RUN composer dump-autoload --optimize \
  && php artisan package:discover --ansi

RUN chown -R www-data:www-data storage bootstrap/cache \
  && chmod -R 775 storage bootstrap/cache

RUN sed -ri \
  -e 's!/var/www/html!/var/www/html/public!g' \
  /etc/apache2/sites-available/*.conf

RUN sed -ri \
  -e 's/AllowOverride None/AllowOverride All/g' \
  /etc/apache2/apache2.conf

RUN sed -i 's/Listen 80/Listen 8080/' /etc/apache2/ports.conf \
  && sed -i 's/:80>/:8080>/' /etc/apache2/sites-available/000-default.conf

EXPOSE 8080

CMD ["apache2-foreground"]