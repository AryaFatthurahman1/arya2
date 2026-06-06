FROM php:8.3-fpm-alpine AS base

LABEL maintainer="DevOps Team"
LABEL description="HR Management System - Hardened Docker Image"

RUN apk add --no-cache \
    nginx \
    git \
    curl \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    libzip-dev \
    zip \
    unzip \
    icu-dev \
    oniguruma-dev \
    supervisor \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo pdo_mysql mbstring exif pcntl bcmath gd zip intl opcache

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

RUN addgroup -g 1000 laravel && \
    adduser -u 1000 -G laravel -h /home/laravel -s /bin/sh -D laravel

FROM base AS builder

COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader --no-ansi --no-interaction

COPY . .

RUN chown -R laravel:laravel /var/www/html && \
    chmod -R 755 /var/www/html && \
    chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

FROM base AS production

COPY --from=builder --chown=laravel:laravel /var/www/html /var/www/html

COPY docker/nginx/nginx.conf /etc/nginx/nginx.conf
COPY docker/supervisor/supervisord.conf /etc/supervisord.conf

USER laravel

EXPOSE 8080

CMD ["/usr/bin/supervisord", "-c", "/etc/supervisord.conf"]
