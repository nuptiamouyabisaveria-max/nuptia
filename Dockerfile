FROM php:8.2-fpm

# Installation des dépendances système nécessaires
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    libpq-dev

# Installation des extensions PHP pour MySQL et Laravel
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Récupération de Composer (le gestionnaire de paquets PHP)
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Définition du dossier de travail
WORKDIR /var/www
COPY . .

# Installation des dépendances du projet
RUN composer install --no-dev --optimize-autoloader

# On donne les droits d'accès pour que Laravel puisse écrire dans les logs et le cache
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

# Commande pour démarrer le serveur sur le port que Render nous donne
CMD php artisan serve --host=0.0.0.0 --port=$PORT