FROM php:8.2-fpm-alpine

# 1. Installa dipendenze, estensioni PHP e strumenti per SSL
RUN apk add --no-cache \
    libpng-dev \
    libzip-dev \
    zip \
    unzip \
    git \
    oniguruma-dev \
    curl-dev \
    ca-certificates \
    wget && \
    update-ca-certificates && \
    docker-php-ext-install pdo_mysql bcmath gd zip

# 2. Scarica il certificato CA di Aiven direttamente nel container
RUN wget https://certs.aiven.io/download/ca.pem -O /var/www/html/ca.pem

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html
COPY . .

# 3. Ottimizzazione Composer
ENV COMPOSER_MEMORY_LIMIT=-1
RUN composer install --no-dev --optimize-autoloader --no-interaction --no-scripts --ignore-platform-reqs

# 4. Permessi per Laravel
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache /var/www/html/ca.pem

EXPOSE 10000

# 5. Comando finale completo (Pulisce la cache per evitare vecchi parametri DB)
CMD php artisan config:clear && \
    php artisan cache:clear && \
    php artisan storage:link && \
    php artisan migrate --force && \
    php artisan config:cache && \
    php artisan route:cache && \
    php artisan view:clear && \
    php artisan serve --host=0.0.0.0 --port=10000