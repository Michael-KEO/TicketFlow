#!/bin/bash

set -e

echo "=== Clonage du dépôt TicketFlow ==="
if [ -d "TicketFlow" ]; then
    echo "Le répertoire TicketFlow existe déjà. Clonage ignoré."
else
    git clone https://github.com/Michael-KEO/TicketFlow.git
fi

cd TicketFlow

echo "=== Configuration des fichiers d'environnement ==="
[ -f .env ] || cp .env.example .env
[ -f .env.local ] || cp .env.local.example.docker .env.local

echo "=== Vérification de la configuration de DATABASE_URL dans .env.local ==="
if grep -q 'DATABASE_URL=' .env.local; then
    echo "DATABASE_URL est déjà défini."
else
    echo 'DATABASE_URL="mysql://app:!ChangeMeUser!@database:3306/tickets_db?serverVersion=8.4&charset=utf8mb4"' >> .env.local
    echo "DATABASE_URL ajouté à .env.local."
fi

echo "=== Construction et démarrage des conteneurs Docker ==="

# Vérifie si le conteneur php est en cours d'exécution
if docker compose ps --status=running | grep -q php; then
    echo "Conteneur 'php' déjà en cours d'exécution. Aucun redémarrage nécessaire."
else
    echo "Conteneurs non actifs ou absents. Lancement complet..."
    docker compose up --build -d
fi

echo "=== Installation des dépendances PHP avec Composer dans le conteneur ==="
docker compose exec php composer install || {
    echo "Composer a échoué. Vérifiez que le conteneur 'php' fonctionne avec : docker compose ps"
    exit 1
}

echo "Installation terminée. Application disponible sur http://localhost:8000"
