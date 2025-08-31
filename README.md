# Application Touche Pas Au Klaxon

Application dédiée aux salariés d'une grande entreprise pour favoriser le covoiturage. 

## Contexte du projet

De nombreux trajets sont réalisés entre les différents sites (mobilité inter-sites) de l'entreprise.  
Parfois, plusieurs véhicules font le même trajet le même jour avec un taux d’occupation très faible (uniquement le conducteur dans de nombreux cas). 

Cette application a pour but de remédier à cette situation en diffusant au sein de l'entreprise les trajets prévus, afin de favoriser le covoiturage. 

## Fonctionnalités

### Utilisateur non connecté

L'utilisateur non connecté aura uniquement accès à la page d'accueil avec la liste des trajets avec des places disponible. 

### Utilisateur connecté

L'utilisateur connecté pourra, en plus des fonctionnalités précédentes : 

- consulter les détails d'un trajet (auteur, coordonnées de contact et nombre de places total)
- créer un nouveau trajet
- modifier un trajet dont il est l'auteur
- supprimer un trajet dont il est l'auteur

### Administrateur connecté

L'administrateur connecté pourra, en plus des fonctionnalités précédentes : 

- supprimer un trajet
- consulter la liste des utilisateurs
- créer une nouvelle agence
- modifier une agence existante
- supprimer une agence

## Technologies utilisées

- Base de données :
    - MySQL
    - PhpMyAdmin
- Back-End :
    - PHP >= 8.2
    - Symfony 7.3
    - Composer
    - Doctrine
    - PHPStan
    - PHPUnit
- Front-End : 
    - Twig / HTML
    - Bootstrap
    - SCSS compilé via Yarn + Webpack Encore
    - Javascript (via Bootstrap)
- Versionning : 
    - Git
    - GitHub

## Installation et lancement de l'application

### Pré-requis

- PHP >= 8.2
- Composer
- Node.js + Yarn
- MySQL
- PhpMyAdmin
- XAMPP
- Symfony CLI

### Installation

#### Cloner le projet

Dans le terminal : se placer dans le dossier htdocs de XAMPP
`git clone https://github.com/Manoon89/touche_pas_au_klaxon`

#### Installer les dépendances PHP

`composer install`

#### Configurer et créer la base de données

Démarrer dans XAMPP Apache & MySQL

Créer à la racine du projet un fichier .env avec 
DATABASE_URL="mysql://root:@127.0.0.1:3306/touche_pas_au_klaxon?serverVersion=8.0"

Dans le terminal : 
`symfony console doctrine:database:create`
`symfony console doctrine:migrations:migrate`
`symfony console import:users`
`symfony console import:agencies`

#### Lancer le serveur local

`symfony server:start`

#### Compiler le fichier styles/app.scss

`yarn install`
`yarn encore dev`

#### Accéder à l'application

On accédera à l'application via :
http://127.0.0.1:8000
ou
http://localhost:8000

Compte administrateur : 
- identifiant : alexandre.martin@email.fr
- mot de passe : defaultPassword

Compte utilisateur : 
- identifiant : sophie.dubois@email.fr
- mot de passe : defaultPassword

#### Réaliser les tests via PHPUnit

Créer un fichier .env.test avec un nom de base de données différent de la base de données réelle

Dans le terminal : 
`symfony console doctrine:database:create --env=test`
`symfony console doctrine:migrations:migrate --env=test`

`symfony php -d APP_ENV=test bin/phpunit`

#### Vérifier la qualité du code avec PHPStan

`vendor\bin\phpstan analyse src --level=9`