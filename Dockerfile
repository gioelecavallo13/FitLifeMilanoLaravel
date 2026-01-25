FROM php:8.2-fpm-alpine

# Installa le estensioni necessarie e Composer
RUN apk add --no-cache \
    libpng-dev \
    libzip-dev \
    zip \
    unzip \
    git \
    oniguruma-dev \
    curl-dev \
    && docker-php-ext-install pdo_mysql bcmath gd zip

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html
COPY . .

# RIMEDIO PER MEMORIA
ENV COMPOSER_MEMORY_LIMIT=-1
RUN composer install --no-dev --optimize-autoloader --no-interaction --no-scripts --ignore-platform-reqs

# Permessi corretti
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

EXPOSE 10000

# UNICO COMANDO FINALE: Link, Migrazioni, Cache e avvio Server
CMD php artisan storage:link && \
    php artisan migrate --force && \
    php artisan config:cache && \
    php artisan route:cache && \
    php artisan view:clear && \
    php artisan serve --host=0.0.0.0 --port=10000