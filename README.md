# Lancer le projet

## Pré-requis

- [Docker](https://www.docker.com/products/docker-desktop) installé et démarré
- [Docker Compose](https://docs.docker.com/compose/) (inclus dans Docker Desktop)
- Git

---

## Installation

### 1. Cloner le dépôt

```bash
git clone https://github.com/<votre-utilisateur>/devoir-symfony.git
cd devoir-symfony
```

### 2. Démarrer les conteneurs

```bash
docker-compose up -d
```

Cela démarre deux conteneurs :
- **devoir-symfony** — application Symfony (Apache + PHP 8.2) sur le port `8888`
- **mysql-symfony** — base de données MySQL 8.0 sur le port `3306`

> Attendez quelques secondes que MySQL soit prêt avant de continuer.

### 3. Exécuter les migrations

```bash
docker exec devoir-symfony php bin/console doctrine:migrations:migrate --no-interaction
```

Cette commande crée toutes les tables nécessaires dans la base de données.

### 4. Vider le cache

```bash
docker exec devoir-symfony php bin/console cache:clear
```

---

## Accéder à l'application

Ouvrez votre navigateur et rendez-vous sur :

```
http://localhost:8888
```

---

## Informations de connexion à la base de données

| Paramètre | Valeur |
|-----------|--------|
| Hôte | `localhost` |
| Port | `3306` |
| Base de données | `app` |
| Utilisateur | `app` |
| Mot de passe | `password` |

---

## Commandes utiles

| Commande | Description |
|----------|-------------|
| `docker-compose up -d` | Démarrer les conteneurs en arrière-plan |
| `docker-compose down` | Arrêter les conteneurs |
| `docker-compose down -v` | Arrêter et supprimer les données MySQL |
| `docker exec devoir-symfony php bin/console cache:clear` | Vider le cache Symfony |
| `docker exec devoir-symfony php bin/console doctrine:migrations:migrate` | Exécuter les migrations |
| `docker logs devoir-symfony` | Voir les logs de l'application |
| `docker logs mysql-symfony` | Voir les logs MySQL |

---

## Réinitialiser la base de données

Pour repartir d'une base vide :

```bash
docker exec mysql-symfony mysql -uapp -ppassword app -e "SET FOREIGN_KEY_CHECKS=0; TRUNCATE TABLE note_links; TRUNCATE TABLE note; TRUNCATE TABLE user; SET FOREIGN_KEY_CHECKS=1;"
```

---

## Structure du projet

```
devoir-symfony/
├── app/                  # Code source Symfony
│   ├── src/
│   │   ├── Controller/   # Contrôleurs
│   │   ├── Entity/       # Entités Doctrine
│   │   ├── Form/         # Formulaires
│   │   ├── Repository/   # Requêtes base de données
│   │   └── Security/     # Authentification
│   ├── templates/        # Templates Twig
│   ├── migrations/       # Migrations de base de données
│   └── public/           # Point d'entrée (index.php)
├── docker-compose.yml    # Configuration Docker
├── Dockerfile            # Image PHP/Apache
└── DEMARRAGE.md          # Ce fichier
```
