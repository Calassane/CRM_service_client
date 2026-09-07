# Bolli Rental CRM

Mini-CRM interne destiné au service client de **Bolli Rental**, une entreprise de location de véhicules basée à Abidjan. L’application centralise les clients, les réservations et les appels traités par les agents, puis restitue l’activité dans un tableau de bord analytique.

> Exercice technique Laravel — référence `BOLLI-DEV-2026-01`

## Liens

- Dépôt GitHub : <https://github.com/Calassane/CRM_service_client>
- Application : <https://crm-service-client-production-9wpzaa.laravel.cloud>
- État de santé : <https://crm-service-client-production-9wpzaa.laravel.cloud/up>

## Compte de démonstration

| Champ | Valeur |
| --- | --- |
| E-mail | `demo@bollirental.africa` |
| Mot de passe | `BolliDemo2026!` |

L’inscription publique est volontairement désactivée : les comptes représentent des agents internes et sont créés par les seeders.

## Fonctionnalités

### Authentification

- connexion et déconnexion des agents avec Laravel Breeze ;
- profil et changement de mot de passe ;
- inscription publique désactivée ;
- autorisations appliquées avec des policies Laravel.

### Clients et réservations

- CRUD complet des clients avec recherche par nom, e-mail ou téléphone ;
- détail d’un client et historique associé ;
- CRUD complet des réservations ;
- rattachement d’une réservation à un client ;
- statuts de réservation modélisés avec une enum PHP.

### Suivi des appels

- création, consultation, modification et suppression d’un appel ;
- rattachement à un client et, si nécessaire, à l’une de ses réservations ;
- sens, motif, date, durée, statut et notes ;
- étiquettes multiples avec synchronisation transactionnelle ;
- filtres par agent, statut, motif et période ;
- modification et suppression réservées à l’agent propriétaire de l’appel.

### Tableau de bord

- périodes de 7, 30 ou 90 jours ;
- volumes quotidien et hebdomadaire ;
- durée moyenne et taux de résolution ;
- répartitions par motif et par statut ;
- classement des agents par volume traité ;
- graphiques responsives avec Chart.js.

## Stack technique

- PHP 8.2 ou supérieur ;
- Laravel 12 ;
- Laravel Breeze et Blade ;
- Eloquent ORM ;
- Tailwind CSS et Alpine.js ;
- Chart.js 4 ;
- Vite 7 ;
- SQLite en local et pendant les tests ;
- MySQL recommandé sur Laravel Cloud ;
- PHPUnit 11.

## Installation locale

### Prérequis

- PHP 8.2+ avec les extensions usuelles de Laravel et `pdo_sqlite` ;
- Composer 2 ;
- Node.js 20+ et npm ;
- Git.

### Étapes

```bash
git clone git@github.com:Calassane/CRM_service_client.git
cd CRM_service_client

composer install
cp .env.example .env
php artisan key:generate

touch database/database.sqlite
php artisan migrate --seed

npm install
npm run build
php artisan serve
```

L’application est ensuite accessible sur <http://127.0.0.1:8000>. Utilisez le compte de démonstration indiqué plus haut.

Pour travailler avec le serveur Vite et les journaux en direct :

```bash
composer run dev
```

## Tests et qualité

Exécuter tous les tests :

```bash
php artisan test
```

Vérifier le formatage PHP et la compilation du front :

```bash
vendor/bin/pint --test
npm run build
php artisan view:cache
```

La suite couvre notamment les modèles et relations, l’authentification, les policies, les CRUD, les filtres, les seeders et les agrégations du tableau de bord.

## Données fictives

La commande `php artisan migrate --seed` prépare une démonstration immédiatement exploitable :

- 4 agents ;
- 15 clients ;
- 25 réservations ;
- 75 appels ;
- 6 étiquettes.

Pour reconstruire complètement la base locale :

```bash
php artisan migrate:fresh --seed
```

Cette commande détruit les données présentes dans la base ciblée. Elle ne doit pas être exécutée sur une base de production contenant des données utiles.

## Architecture

Les vues Blade sont limitées à la présentation. La validation, les autorisations, les requêtes et les opérations métier sont placées dans des classes dédiées.

```text
app/
├── Actions/         # opérations ciblées et réutilisables
├── Enums/           # statuts, motifs et sens d’appel
├── Http/
│   ├── Controllers/ # orchestration HTTP
│   └── Requests/    # validation et autorisation des entrées
├── Models/          # entités et relations Eloquent
├── Policies/        # règles d’accès
├── Queries/         # recherche et filtrage des listes
└── Services/        # agrégations du tableau de bord
```

Quelques choix structurants :

- les enums PHP évitent la dispersion de chaînes de caractères métier ;
- les `FormRequest` portent la validation, y compris la cohérence client/réservation ;
- les policies empêchent un agent de modifier les appels d’un autre agent ;
- les mutations multi-tables utilisent des transactions ;
- les objets Query isolent les filtres des contrôleurs ;
- `DashboardAnalytics` prépare toutes les statistiques : la vue n’effectue aucun calcul métier ;
- les regroupements analytiques restent compatibles avec SQLite, MySQL et PostgreSQL.

## Déploiement sur Laravel Cloud

Le projet ne nécessite aucun fichier de déploiement spécifique. La configuration suivante correspond au flux recommandé par Laravel Cloud.

### 1. Créer l’application

1. Connecter GitHub à Laravel Cloud.
2. Sélectionner le dépôt `Calassane/CRM_service_client` et la branche `main`.
3. Choisir PHP 8.5 et Node.js 24.
4. Créer et attacher une base MySQL 8.4 depuis le canevas d’infrastructure. Les variables de connexion seront injectées automatiquement.

### 2. Variables d’environnement

Générer la clé localement sans modifier le fichier `.env` :

```bash
php artisan key:generate --show
```

Ajouter au minimum les variables suivantes dans l’environnement Laravel Cloud :

```dotenv
APP_NAME="Bolli Rental CRM"
APP_KEY=base64:CLE_GENEREE_A_REMPLACER
APP_URL=https://crm-service-client-production-9wpzaa.laravel.cloud
APP_LOCALE=fr
APP_FALLBACK_LOCALE=fr
LOG_LEVEL=warning
```

Laravel Cloud injecte déjà `APP_ENV=production` et `APP_DEBUG=false`. Ne pas recopier les variables `DB_*` si la base est attachée depuis Laravel Cloud.

### 3. Commandes Cloud

Commande de build :

```bash
composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader
npm ci --audit false
npm run build
```

Commande de déploiement :

```bash
php artisan migrate --force
```

Après le premier déploiement uniquement, ouvrir l’outil de commandes Laravel Cloud et exécuter :

```bash
php artisan db:seed --force
```

Les déploiements suivants ne doivent exécuter que les migrations afin de ne pas dupliquer les données fictives.

### 4. Recette publique

- vérifier que `/up` répond correctement ;
- ouvrir l’URL racine et se connecter avec le compte démo ;
- tester la création d’un client, d’une réservation et d’un appel ;
- vérifier les filtres et les quatre graphiques ;
- contrôler l’affichage sur mobile ;
- vérifier que les liens publics de ce README correspondent au domaine Cloud actif.

## Périmètre et simplifications

Le MVP demandé est réalisé. Les CRUD clients et réservations ont été ajoutés au-delà du strict besoin de données simplifiées.

Les bonus suivants ne sont pas implémentés :

- résumé ou analyse de sentiment par IA ;
- notification automatique pour une étiquette urgente ;
- API REST pour une future application mobile ;
- import ou stockage de fichiers audio.

Pour rester cohérent avec le temps imparti, les véhicules sont représentés par un libellé dans une réservation plutôt que par un catalogue complet. L’envoi d’e-mails est également configuré en journal local par défaut.

## Utilisation de l’IA

OpenAI Codex a été utilisé comme partenaire de développement tout au long de l’exercice : analyse du brief, proposition de l’architecture, génération initiale de certaines classes et vues, rédaction de tests, revue des relations Eloquent et préparation de la documentation.

Le travail a été conduit par petites étapes fonctionnelles avec des commits séparés. Les propositions générées ont été adaptées aux règles du domaine, notamment la cohérence entre client et réservation, la propriété des appels, l’absence de logique métier dans les vues et la compatibilité des statistiques avec plusieurs moteurs SQL. Chaque étape a été validée avec PHPUnit, Laravel Pint, la compilation Blade et le build Vite.

## Licence

Projet réalisé exclusivement dans le cadre de l’exercice technique Bolli Rental.
