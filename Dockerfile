FROM php:8.2-cli

# Installation des dépendances système
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    libpq-dev

# Installation des extensions PHP
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Récupération de Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Définition du dossier de travail
WORKDIR /var/www
COPY . .

# Installation des dépendances Laravel
RUN composer install --no-dev --optimize-autoloader

# Droits d'accès pour Laravel
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

# Correction de la commande CMD
# On utilise une syntaxe simple pour éviter l'erreur "not found"
CMD php artisan migrate --force && php artisan serve --host=0.0.0.0 --port=10000