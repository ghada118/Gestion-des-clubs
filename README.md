Application de Gestion des Clubs
  Description

Cette application web développée avec Symfony permet la gestion complète des clubs universitaires :
création de clubs, gestion des formations, événements, inscriptions et responsables.
Elle facilite la coordination entre les étudiants et les responsables, tout en offrant une interface claire et intuitive.

🚀 Fonctionnalités principales

🧩 Gestion des clubs : création, modification, suppression, types de clubs.

🎓 Gestion des formations : ajout de formations et types de formation.

🎉 Gestion des événements : planification, suivi et catégorisation.

🧑‍💼 Gestion des responsables et étudiants : inscription, affectation et suivi.

📨 Envoi automatique de notifications par e-mail (pour rappels et validations).

🗓️ Suivi des inscriptions et demandes (étudiants ↔ clubs).

🛠️ Technologies utilisées

Backend : Symfony (PHP 8+)

Frontend : Twig, Bootstrap, HTML5, CSS3

Base de données : MySQL

Serveur local : XAMPP / WAMP

Gestion de dépendances : Composer

Contrôle de version : Git & GitHub

⚙️ Installation et exécution

Cloner le projet :

git clone https://github.com/ghada118/Gestion-des-clubs.git
cd Gestion-des-clubs


Installer les dépendances :

composer install


Configurer la base de données :

Dupliquer le fichier .env → le renommer .env.local

Modifier la ligne suivante avec vos identifiants :

DATABASE_URL="mysql://user:password@127.0.0.1:3306/gestion_clubs"


Créer la base et exécuter les migrations :

php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate


Lancer le serveur Symfony :

symfony server:start


Accéder à l’application via :
👉 http://localhost:8000
