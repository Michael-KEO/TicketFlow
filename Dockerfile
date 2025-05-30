FROM php:8.3-fpm

# Installe les extensions nécessaires à Symfony
RUN apt-get update && apt-get install -y \
    git unzip zip libicu-dev libpq-dev libonig-dev libzip-dev libjpeg-dev libpng-dev libfreetype6-dev \
    && docker-php-ext-install intl pdo pdo_mysql opcache zip gd

# Installe Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Définir le répertoire de travail
WORKDIR /var/www

# Copie du projet (optionnel si bind mount)
# COPY . /var/www
