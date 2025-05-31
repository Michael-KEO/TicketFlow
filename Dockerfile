FROM php:8.3-fpm

# Mettre à jour la liste des paquets et installer les dépendances système nécessaires
# y compris wget pour télécharger Symfony CLI et ca-certificates pour les connexions HTTPS
# ainsi que les dépendances pour les extensions PHP
RUN apt-get update && apt-get install -y --no-install-recommends \
    git \
    unzip \
    zip \
    # Dépendances pour l'extension intl
    libicu-dev \
    # Dépendances pour l'extension onig (mbstring)
    libonig-dev \
    # Dépendances pour l'extension zip
    libzip-dev \
    # Dépendances pour l'extension gd
    libjpeg-dev \
    libpng-dev \
    libfreetype6-dev \
    # Utilitaires requis
    wget \
    ca-certificates \
    && rm -rf /var/lib/apt/lists/*

# Installer les extensions PHP courantes pour Symfony
# pdo_mysql est crucial pour la connexion à votre base de données MySQL
# intl pour le support de l'internationalisation
# opcache pour améliorer les performances PHP en production
# zip pour la gestion des archives
# gd pour la manipulation d'images
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) intl pdo pdo_mysql opcache zip gd

# Installer Composer (copié depuis l'image officielle de Composer)
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Installer Symfony CLI
RUN wget https://get.symfony.com/cli/installer -O - | bash && \
    mv /root/.symfony5/bin/symfony /usr/local/bin/symfony

# Définir le répertoire de travail pour les commandes suivantes
WORKDIR /var/www

