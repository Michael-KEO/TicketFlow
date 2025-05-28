
# Application de Gestion de Tickets d’Incidents

## git Introduction
Ce projet est une application web développée avec **Symfony (PHP)**, **Twig**, et **MySQL**, destinée à la gestion des tickets d’incidents au sein d’une entreprise éditrice de logiciels.

Elle permet :
- aux **clients** de signaler des bugs,
- aux **développeurs** de les résoudre efficacement,
- et aux **administrateurs** de superviser l’ensemble des projets et utilisateurs.

Le développement a été mené en équipe avec GitHub (travail par **branches fonctionnelles**) et synchronisation via **Tailscale**.

---

## ⚙️ Technologies utilisées
- Framework : Symfony 7
- Langage : PHP 8.2+
- Base de données : MySQL
- Frontend : Twig + Bootstrap
- ORM : Doctrine
- Sécurité : Symfony Security
- E-mail : Symfony Mailer

---

##  Installation & Lancement

### Prérequis
- PHP ≥ 8.2
- Composer
- Symfony CLI
- MySQL Server

### Étapes

1. **Cloner le projet**
   ```bash
   git clone https://github.com/<votre-org>/Projet-Dev-Web.git
   cd Projet-Dev-Web/tickets-manager
    ```

2. **Installer les dépendances**

   ```bash
   composer install
   ```

3. **Configurer `.env.local`**

   ```dotenv
   DATABASE_URL="mysql://root:password@127.0.0.1:3306/tickets_db"
   MAILER_DSN=smtp://localhost
   ```

4. **Créer la base et charger les fixtures**

   ```bash
   php bin/console doctrine:database:create
   php bin/console doctrine:migrations:migrate
   php bin/console doctrine:fixtures:load
   ```

5. **Lancer le serveur Symfony**

   ```bash
   symfony serve
   ```

---

## Fonctionnalités principales

### 🔐 Authentification et sécurité 
* Connexion sécurisée
* Rôles : Rapporteur, Développeur, Administrateur
* Redirections & protections des routes

###  Gestion des tickets 

* Création, consultation, modification
* Attribution à un développeur
* Statuts, filtres, tri, pagination
* Système de commentaires

###  Gestion des utilisateurs
* CRUD utilisateurs
* Attribution des rôles
* Accès restreint selon rôle

### Gestion des clients & projets

* Création des entités `Client` et `Projet`
* Liaison avec les tickets

### Dashboard 

* Statistiques :

  * Tickets par période
  * Par développeur
  * Délai moyen de traitement

### Notifications e-mail

* Envoi automatique à chaque changement de statut
* Templates de mails personnalisés
* Configuration sécurisée via `.env`

### UX et interface utilisateur 

* Interface responsive
* Barre de recherche
* Tri dynamique et pagination

---

## Sécurité et bonnes pratiques 
* Hashage des mots de passe
* Système de rôles sécurisé
* Gestion de l’environnement et HTTPS

---

## 📎 Annexes

* Schéma de la base de données : `/docs/schema.pdf`
* Captures d’écran disponibles dans `/screenshots`

---

## Équipe projet

| Membre         | Rôle                                |
| -------------- | ----------------------------------- |
| Seynabou       | Authentification, sécurité          |
| Michael        | BDD, projets, clients, dashboard    |
| Lucas The Goat | Tickets, UX avancée                 |
| Codou          | Utilisateurs, notifications, README |

---

## Conclusion

Ce projet nous a permis d'explorer toutes les dimensions d'un développement web professionnel : de la conception à la sécurité, en passant par la collaboration Git, les tests, l’intégration continue et l’expérience utilisateur.

Il a été mené de façon collaborative avec des outils modernes et dans le respect des bonnes pratiques Symfony. Chaque membre de l'équipe a pu se spécialiser sur une composante clé, et le résultat est une application stable, robuste et prête pour un usage réel en entreprise.
