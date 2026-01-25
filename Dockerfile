FROM php:8.2-fpm-alpine

# Installazione dipendenze
RUN apk add --no-cache \
    libpng-dev \
    libzip-dev \
    zip \
    unzip \
    git \
    oniguruma-dev \
    curl-dev && \
    docker-php-ext-install pdo_mysql bcmath gd zip

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Copiamo tutto (incluso il file ca.pem che hai appena aggiunto)
COPY . .

ENV COMPOSER_MEMORY_LIMIT=-1
RUN composer install --no-dev --optimize-autoloader --no-interaction --no-scripts --ignore-platform-reqs

# Permessi: assicurati che www-data possa leggere il certificato
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache /var/www/html/ca.pem

EXPOSE 10000

# Usa config:clear invece di config:cache per forzare la lettura delle variabili di Render
CMD php artisan config:clear && \
    php artisan cache:clear && \
    php artisan storage:link && \
    php artisan migrate --force && \
    php artisan serve --host=0.0.0.0 --port=10000