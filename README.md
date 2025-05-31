# TicketFlow - Application de Gestion de Tickets d’Incidents

## 🌟 Introduction
TicketFlow est une application web développée avec **Symfony (PHP)**, **Twig**, et **MySQL**, conteneurisée avec **Docker**. Elle est destinée à la gestion des tickets d’incidents au sein d’une entreprise éditrice de logiciels.

Elle permet :
- aux **rapporteurs** (pouvant être des clients ou des testeurs internes) de signaler des bugs ou des demandes d'évolution,
- aux **développeurs** de prendre en charge et de résoudre ces tickets,
- et aux **administrateurs** de superviser l’ensemble des projets, utilisateurs et configurations.

Le développement a été mené en équipe avec GitHub (travail par **branches fonctionnelles**).

---

## ⚙️ Technologies Utilisées
- **Backend**: Symfony 7 / PHP 8.2+
- **Base de Données**: MySQL (via Docker)
- **Frontend**: Twig (CSS via AssetMapper)
- **ORM**: Doctrine
- **Sécurité**: Symfony Security
- **Emails**: Symfony Mailer
- **Conteneurisation**: Docker, Docker Compose
- **Serveur de développement (via Docker)**: Symfony CLI (`symfony serve`)

---

### INPORTANT:

 Les identifiants par défaut de connection sont : **admin@example.com** / **admin**

---

## 🐳 Installation & Lancement avec Docker (Recommandé)

Cette méthode est la plus simple pour démarrer rapidement sur n'importe quel système d'exploitation supportant Docker.

### Prérequis
- Docker Desktop (Windows, macOS) ou Docker Engine + Docker Compose (Linux)
- Git

### Étapes

1.  **Cloner le projet** :
    Ouvrez un terminal ou une invite de commande.
    ```bash
    git clone https://github.com/Michael-KEO/TicketFlow.git
    cd TicketFlow
    ```
    **Pour une installation rapide sur Linux, macOS, ou Windows via WSL, vous pouvez utiliser le script `setup.sh` qui se trouve à la racine du projet apès avoir cloné le projet.**

    ```bash
    # Pour Linux/macOS/Windows (WSL)
    chmod +x setup.sh
    ./setup.sh
    ```
    **Sinon, suivez les étapes manuelles ci-dessous.**
    


2.  **Configuration de l'environnement** :
    Copiez les fichiers d'environnement exemples.
    ```bash
    # Pour Linux/macOS
    cp .env.example .env
    cp .env.local.example.docker .env.local

    # Pour Windows (Command Prompt)
    # copy .env.example .env
    # copy .env.local.example .env.local
    ```
    Modifiez le fichier `.env` si nécessaire, notamment les variables `MYSQL_ROOT_PASSWORD`, `MYSQL_USER`, `MYSQL_PASSWORD` si vous souhaitez des identifiants différents de ceux par défaut dans `compose.yaml`.
    Le `DATABASE_URL` dans `.env.local` devrait être configuré pour pointer vers le service Docker de la base de données (`database` comme nom d'hôte) :
    ```dotenv
    # .env.local (exemple pour Docker)
    DATABASE_URL="mysql://app:!ChangeMeUser!@database:3306/tickets_db?serverVersion=8.4&charset=utf8mb4"
    # Assurez-vous que 'app', '!ChangeMeUser!', 'database', et 'tickets_db' correspondent
    # à MYSQL_USER, MYSQL_PASSWORD, nom du service de la base de données dans compose.yaml, et MYSQL_DATABASE.
    # Le serverVersion=8.4 correspond à l'image mysql:8.4
    ```

3.  **Construire et Lancer les Conteneurs Docker** :
    Depuis la racine du projet `TicketFlow` :
    ```bash
    docker compose up --build -d
    ```
    Cette commande va :
    * Construire l'image PHP avec toutes les dépendances (y compris Symfony CLI, `wget`, `pdo_mysql`).
    * Démarrer les services définis dans `compose.yaml` (php, database, mailer, mercure) en arrière-plan (`-d`).

4.  **Installer les Dépendances PHP (Composer)** :
    Une fois les conteneurs lancés, exécutez Composer à l'intérieur du conteneur PHP :
    ```bash
    docker compose exec php composer install
    ```
    Cela installera les vendors PHP et exécutera les scripts `post-install-cmd` (comme `importmap:install`).

5.  **Préparer la Base de Données** :
    Le dump SQL présent dans `docker/mysql/initdb.d/` est automatiquement importé lors de la première création du conteneur de base de données, initialisant ainsi le schéma et les données.


6.  **Accéder à l'application** :
    Ouvrez votre navigateur et allez à l'adresse : `http://localhost:8000` (ou l'IP de votre machine Docker si vous êtes sur une VM Linux sans interface graphique).
    Le serveur Symfony CLI (`symfony serve`) est configuré pour s'exécuter à l'intérieur du conteneur `php` et est accessible via le port `8000` de votre machine hôte.

### Commandes Docker Utiles
* Voir les logs d'un service (ex: php) : `docker compose logs -f php`
* Arrêter les services : `docker compose down`
* Relancer les services : `docker compose up -d`
* Exécuter une commande dans le conteneur php : `docker compose exec php <commande>` (ex: `php bin/console list`)

---

## 🛠️ Installation & Lancement Manuel (Développement local sans Docker)

### Prérequis
- PHP ≥ 8.2 (avec les extensions `intl`, `pdo_mysql`, `opcache`, `zip`, `gd`, `onig`, `icu`)
- Composer
- Symfony CLI
- Serveur MySQL (local ou distant)

### Étapes

1.  **Cloner le projet** (idem Docker)
    ```bash
    git clone https://github.com/Michael-KEO/TicketFlow.git
    cd TicketFlow
    ```

2.  **Installer les dépendances PHP**
    ```bash
    composer install
    ```

3.  **Configurer `.env.local`**
    Copiez `.env.example` vers `.env` et `.env.local.example` vers `.env.local`.
    Modifiez `.env.local` pour pointer vers votre serveur MySQL local :
    ```dotenv
    # .env.local (exemple pour MySQL local)
    DATABASE_URL="mysql://VOTRE_USER_BDD:VOTRE_MOT_DE_PASSE_BDD@127.0.0.1:3306/tickets_db?serverVersion=8.4&charset=utf8mb4"
    MAILER_DSN=smtp://localhost:1025 # Si vous utilisez Mailpit localement par exemple
    ```

4.  **Créer la base de données et appliquer les migrations/fixtures**
    ```bash
    php bin/console doctrine:database:create --if-not-exists
    php bin/console doctrine:migrations:migrate
    php bin/console doctrine:fixtures:load
    ```

5.  **Lancer le serveur de développement Symfony**
    ```bash
    symfony serve
    ```
    L'application sera généralement accessible à `http://127.0.0.1:8000`.

---

## ✨ Fonctionnalités Principales

### 🔐 Authentification et Sécurité
* Système de connexion sécurisé avec gestion des sessions.
* Différents rôles utilisateurs (Rapporteur, Développeur, Administrateur) avec permissions spécifiques.
* Protection des routes en fonction des rôles.
* Hashage des mots de passe (via Symfony Security).

### 🎫 Gestion des Tickets
* Création, consultation, modification et suppression (pour admin) des tickets.
* Attribution des tickets à des développeurs (par les admins).
* Gestion des statuts (Nouveau, Ouvert, En cours, Résolu, Fermé, etc.).
* Système de commentaires pour chaque ticket.
* Filtrage des tickets par rôle (le rapporteur voit ses tickets, le développeur ceux qui lui sont assignés, l'admin voit tout).

### 👥 Gestion des Utilisateurs (Admin)
* CRUD (Create, Read, Update, Delete) pour les utilisateurs.
* Attribution et modification des rôles des utilisateurs.

### 📂 Gestion des Clients & Projets (Admin)
* CRUD pour les entités `Client` et `Projet`.
* Association des tickets à des projets, et des projets à des clients.

### 📊 Dashboards Spécifiques par Rôle
* **Administrateur**: Vue d'ensemble, gestion des utilisateurs, clients, projets.
* **Développeur**: Tableau de bord avec les tickets assignés, statistiques personnelles.
* **Rapporteur**: Tableau de bord avec les tickets créés, statistiques personnelles.
* Statistiques générales sur les tickets (par statut, priorité).

### 📧 Notifications par E-mail
* Envoi de notifications lors de changements importants sur les tickets (ex: changement de statut, assignation, nouveau commentaire).
* Utilisation de Symfony Mailer avec templates Twig pour les e-mails.

### 🎨 Interface Utilisateur
* Interface utilisateur construite avec Twig et stylisée avec CSS (via AssetMapper).
* Conception responsive pour une utilisation sur différents appareils.

---

## 🛡️ Sécurité et Bonnes Pratiques
* Utilisation du composant Symfony Security pour l'authentification et les autorisations.
* Protection contre les failles CSRF.
* Validation des données des formulaires.
* Utilisation de variables d'environnement pour les configurations sensibles.

---

## 🧑‍💻 Équipe Projet

| Membre         | Rôle Principal Attribué (exemple basé sur le README original) |
| -------------- |---------------------------------------------------------------|
| Seynabou       | Authentification, Sécurité                                    |
| Michael        | Docker, Base de Données, Projets, Clients, Dashboard          |
| Lucas The Goat | Tickets, UX Avancée                                           |
| Codou          | Utilisateurs, Notifications, README                           |

---

## ✅ Conclusion
Ce projet illustre la mise en œuvre d'une application web complète avec Symfony et Docker, couvrant des aspects essentiels tels que la gestion de données, l'authentification, les rôles utilisateurs, et la conteneurisation pour faciliter le déploiement et la collaboration.
