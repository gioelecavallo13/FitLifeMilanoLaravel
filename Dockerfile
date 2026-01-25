# 1. Usa l'immagine base ufficiale di PHP con FPM
FROM php:8.2-fpm

# 2. Installa le dipendenze di sistema (il tuo pezzo di codice)
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    libzip-dev \
    unzip \
    git \
    curl

# 3. Installa le estensioni PHP
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip

# 4. Installa Composer scaricandolo dall'immagine ufficiale
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 5. Imposta la cartella di lavoro
WORKDIR /var/www

# 6. Copia i file del tuo progetto Laravel
COPY . .

# 7. Installa le dipendenze di Laravel
# Aggiungiamo --no-interaction per evitare blocchi nel terminale di Render
RUN composer install --no-dev --optimize-autoloader --no-interaction

# 8. Permessi per le cartelle di Laravel (fondamentale!)
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

# 9. Espone la porta e avvia il server
EXPOSE 10000
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=10000"]