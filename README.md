# Application de prise de notes en Symfony

## Table des matières

<!--toc:start-->
- [Application de prise de notes en Symfony](#application-de-prise-de-notes-en-symfony)
  - [Table des matières](#table-des-matières)
  - [À propos de cet exercice](#à-propos-de-cet-exercice)
  - [Pré-requis](#pré-requis)
  - [Objectif](#objectif)
    - [Comment envoyer votre solution](#comment-envoyer-votre-solution)
    - [Cahier des charges](#cahier-des-charges)
      - [Liste des pages](#liste-des-pages)
        - [Page d'accueil](#page-daccueil)
        - [Connexion](#connexion)
        - [Création de compte](#création-de-compte)
        - [Voir son profil](#voir-son-profil)
        - [Éditer son profil](#éditer-son-profil)
        - [Créer une note](#créer-une-note)
        - [Voir une note](#voir-une-note)
        - [Éditer une note](#éditer-une-note)
        - [Supprimer une note](#supprimer-une-note)
<!--toc:end-->

## À propos de cet exercice

Cet exercice a pour but de tester vos connaissances de Symfony pour créer une application web simple. Vous pouvez faire cet exercice à deux ou individuellement.

## Pré-requis

- avoir de solides bases en PHP
- avoir des bases en BDD (mysql ou postgres)
- savoir manipuler les controllers, entities, repositories, forms et templates twig de Symfony
- avoir docker et docker-compose installés sur votre machine

## Objectif

### Comment envoyer votre solution

Vous créerez un repository github contenant votre code ainsi que les fichiers dockers nécessaires pour conteneuriser votre application. Les commits, branches et pull requests seront analysés et compteront pour l'évaluation de vos compétences.

### Cahier des charges

- les données de l'application sont enregistrées dans une base de donnée MySQL, Postgresql ou SQLite (au choix)

#### Liste des pages

##### Page d'accueil

- permet de voir toutes les notes publiques

##### Connexion

- permet à un visiteur de se connecter à son compte
- la connexion se fait via un email et un mot de passe
- l'accès doit être refusé si l'utilisateur est déjà connecté

##### Création de compte

- permet à un visiteur de créer son compte
- un compte est constitué
  - d'un email (obligatoire)
  - d'un mot de passe (obligatoire)
  - d'un pseudo (obligatoire)
- le mot de passe doit être hashé pour assurer la sécurité du compte
- dans le formulaire de création de compte, il faut demander 2 fois le mot de passe au visiteur
  - pour s'assurer qu'il n'a pas fait d'erreurs en le renseignant
  - si les 2 mots de passes sont différents, afficher une erreur
- l'accès doit être refusé si l'utilisateur est déjà connecté

##### Voir son profil

- permet d'afficher les informations de son compte
- affiche également toutes les notes que l'utilisateur a écrites
- l'accès doit être refusé si l'utilisateur n'est pas connecté

##### Éditer son profil

- permet de modifier les informations de son compte
- l'accès doit être refusé si l'utilisateur n'est pas connecté

##### Créer une note

- afficher un formulaire permettant de créer une note
- le formulaire doit permettre de
  - renseigner le titre de la note (obligatoire)
  - renseigner le contenu de la note (obligatoire)
  - choisir si la note est publique ou privée (par défaut publique)
    - si la note est privée, renseigner renseigner un mot de passe permettant d'en débloquer l'accès pour les visiteurs
    - le mot de passe doit être constitué de 2 lettres et de 2 chiffres (exemples : AA00, ER12, TG32, ...)
  - choisir de lier la note à d'autres notes existantes (optionnel)
    - utiliser le lien d'une autre note existante
- l'accès doit être refusé si l'utilisateur n'est pas connecté

##### Voir une note

- si la note est publique, affiche le contenu de la note
- si la note est privée, débloquer l'accès avec un mot de passe puis afficher le contenu de la note
- si la note est liée à d'autres notes, afficher les liens de ces notes là en bas de page, permettant au visiteur de naviguer vers ces notes

##### Éditer une note

- l'accès doit être refusé si l'utilisateur n'est pas connecté
- l'accès doit être refusé si on essaye d'éditer une note d'un autre utilisateur
- réutiliser le même formulaire que celui de la création de note

##### Supprimer une note

- l'accès doit être refusé si l'utilisateur n'est pas connecté
- l'accès doit être refusé si on essaye de supprimer une note d'un autre utilisateur


## Barême

Chaque page compte pour 2 points (18 points au total).

La mise en place de la base de données et la conteneurisation du code compte pour 2 points

BONUS :

- Git repository +2
  - travailler avec des branches (gitflow ou trunk based development)
  - faire des commits atomiques en utilisant les [conventional commits](https://www.conventionalcommits.org/en/v1.0.0/)
  - faire des PR ou des merges depuis une branche de dev faire une branche principale dès qu'une tâche a été réalisée
  - ...
