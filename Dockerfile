# Usa un'immagine PHP ufficiale con estensioni necessarie
FROM php:8.2-fpm

# Installa dipendenze di sistema e estensioni PHP per Laravel
RUN apt-get update && apt-get install -y \
    libpng-dev libonig-dev libxml2-dev zip unzip git curl nginx

RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Imposta la cartella di lavoro
WORKDIR /var/www

# Copia i file del progetto
COPY . .

# Installa Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
RUN composer install --no-dev --optimize-autoloader

# Permessi per Laravel
RUN chown -R www-data:www-data /var/www/storage /var/www/cache

# Copia la configurazione di Nginx (opzionale, o usa quella di default)
# Nota: Su Render dovrai mappare la porta 10000
EXPOSE 10000

CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=10000"]