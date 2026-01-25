# Usa Apache, che è più semplice da gestire su Render per PHP
FROM php:8.2-apache

# 1. Installa SOLO le dipendenze essenziali per far lavorare Composer
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libzip-dev \
    zip \
    unzip \
    git \
    && docker-php-ext-install pdo_mysql bcmath gd zip

# 2. Abilita rewrite per Laravel
RUN a2enmod rewrite

# 3. Copia Composer dall'immagine ufficiale
COPY --from=composer:2.6 /usr/bin/composer /usr/bin/composer

# 4. Imposta la cartella e copia i file
WORKDIR /var/www/html
COPY . .

# 5. FIX PER LA MEMORIA: Aumentiamo il limite per Composer
ENV COMPOSER_MEMORY_LIMIT=-1

# 6. Installazione dipendenze (senza scripts e senza dev)
RUN composer install --no-dev --optimize-autoloader --no-interaction --no-scripts

# 7. Sistema i permessi (senza questi Laravel dà errore 500)
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# 8. Punta Apache alla cartella /public di Laravel
RUN sed -ri -e 's!/var/www/html!/var/www/html/public!g' /etc/apache2/sites-available/*.conf

# 9. Render usa la porta 80 o 10000 di default per Apache
EXPOSE 80