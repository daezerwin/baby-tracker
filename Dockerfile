# syntax=docker/dockerfile:1

# ---------------------------------------------------------------------------
# Stage 1: build frontend assets (Tailwind + Chart.js via Vite)
# ---------------------------------------------------------------------------
FROM node:20-alpine AS frontend

WORKDIR /app

COPY package.json package-lock.json ./
RUN npm ci

COPY vite.config.js tailwind.config.js postcss.config.js ./
COPY resources ./resources
RUN npm run build

# ---------------------------------------------------------------------------
# Stage 2: install PHP dependencies
# ---------------------------------------------------------------------------
FROM composer:2 AS vendor

WORKDIR /app

COPY database ./database
COPY composer.json composer.lock ./
RUN composer install \
    --no-dev \
    --no-scripts \
    --no-interaction \
    --prefer-dist \
    --optimize-autoloader

# ---------------------------------------------------------------------------
# Stage 3: runtime image (Apache + PHP)
# ---------------------------------------------------------------------------
FROM php:8.4-apache AS app

RUN apt-get update && apt-get install -y --no-install-recommends \
        libsqlite3-dev \
        libzip-dev \
        libonig-dev \
        libxml2-dev \
        libcurl4-openssl-dev \
        unzip \
    && docker-php-ext-install -j"$(nproc)" \
        pdo \
        pdo_sqlite \
        mbstring \
        exif \
        bcmath \
        zip \
        xml \
        dom \
        curl \
    && a2enmod rewrite \
    && rm -rf /var/lib/apt/lists/*

COPY docker/apache-vhost.conf /etc/apache2/sites-available/000-default.conf

WORKDIR /var/www/html

COPY . .
COPY --from=vendor /app/vendor ./vendor
COPY --from=frontend /app/public/build ./public/build

# Regenerate the package-discovery cache against this (--no-dev) vendor
# install — a stale cache copied in from local dev would reference dev-only
# packages (e.g. laravel/pail) that don't exist here and crash on boot.
RUN rm -f bootstrap/cache/packages.php bootstrap/cache/services.php \
    && php artisan package:discover --ansi

RUN mkdir -p database \
        storage/framework/cache \
        storage/framework/sessions \
        storage/framework/views \
        storage/logs \
        storage/app/public \
        bootstrap/cache \
    && touch database/database.sqlite \
    && chown -R www-data:www-data database storage bootstrap/cache \
    && chmod -R 775 database storage bootstrap/cache

COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

EXPOSE 80

ENTRYPOINT ["entrypoint.sh"]
CMD ["apache2-foreground"]
