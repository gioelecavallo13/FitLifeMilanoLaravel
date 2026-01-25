FROM php:8.2-apache

# Abilita il modulo rewrite di Apache (fondamentale per Laravel)
RUN a2enmod rewrite

# Installa dipendenze
RUN apt-get update && apt-get install -y libpng-dev libjpeg-dev libfreetype6-dev libzip-dev zip unzip git && rm -rf /var/lib/apt/lists/*

# Installa estensioni PHP
RUN docker-php-ext-configure gd --with-freetype --with-jpeg
RUN docker-php-ext-install pdo_mysql gd zip

# Installa Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html
COPY . .

# Installa dipendenze Laravel senza script pesanti
RUN composer install --no-dev --optimize-autoloader --no-interaction --no-scripts

# Cambia la DocumentRoot di Apache alla cartella /public di Laravel
RUN sed -ri -e 's!/var/www/html!/var/www/html/public!g' /etc/apache2/sites-available/*.conf

# Permessi
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Porta standard Render
EXPOSE 80