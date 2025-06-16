# Application SaaS de Facturation 🚀

**Projet développé intégralement en [Symfony](https://symfony.com/) et réalisé en collaboration avec [Nashouille](https://github.com/Nashouille).**

---

## 💡 Description du projet

Cette application SaaS de facturation en ligne permet de gérer l’ensemble du cycle financier d’une entreprise :

* **Factures de vente et d’achat**
* **Gestion des notes de frais**
* **Génération de bilans comptables**
* **Visualisation des indicateurs financiers** (marges, dépenses, chiffre d’affaires)

L’interface utilisateur est réalisée en **Symfony/Twig** avec du **CSS** (ou **Bootstrap**), le back‑end est en **PHP avec Symfony**, et la persistance des données se fait via **MySQL** (Docker).

> 🎨 Le design de l’application est disponible sur Figma : [https://www.figma.com/design/F6bJb0OZjGC8lwEsVoPHQT/Projet-DWWM?node-id=42-1034\&t=MxPGAKHABKh2UVAf-1](https://www.figma.com/design/F6bJb0OZjGC8lwEsVoPHQT/Projet-DWWM?node-id=42-1034&t=MxPGAKHABKh2UVAf-1)

---

## 📑 Table des matières

- [Application SaaS de Facturation 🚀](#application-saas-de-facturation-)
  - [💡 Description du projet](#-description-du-projet)
  - [📑 Table des matières](#-table-des-matières)
  - [✨ Fonctionnalités](#-fonctionnalités)
  - [🏗️ Architecture et design](#️-architecture-et-design)
    - [1. Front‑end](#1-frontend)
    - [2. Back‑end Back‑end](#2-backend-backend)
    - [3. Base de données](#3-base-de-données)
    - [4. Déploiement Déploiement](#4-déploiement-déploiement)
    - [5. Tests \& maintenance](#5-tests--maintenance)
  - [🔄 État d’avancement](#-état-davancement)
    - [Objectifs de maîtrise](#objectifs-de-maîtrise)
  - [📥 Installation](#-installation)
  - [⚙️ Configuration](#️-configuration)
  - [▶️ Lancement](#️-lancement)
  - [🤝 Contribuer](#-contribuer)
  - [📝 Licence](#-licence)

---

## ✨ Fonctionnalités

* 👤 **Gestion des utilisateurs** : inscription, authentification (JWT/OAuth), gestion des rôles
* 🧑‍💼 **Clients** : CRUD complet
* 🧾 **Factures** : création, édition, suppression (ventes & achats), export PDF
* 💸 **Notes de frais** : saisie, validation, remboursement, suivi
* 💳 **Paiement en ligne** : intégration Stripe & PayPal
* 📑 **Bilan comptable** : génération de bilans et comptes de résultat
* 📊 **Tableau de bord** : graphiques interactifs (marges, CA, dépenses)
* 📧 **Emails automatisés** : envoi de documents financiers

---

## 🏗️ Architecture et design

Chaque volet du projet suit une phase de conception formalisée avec des points clés à valider.

### 1. Front‑end

**Technologies** : Symfony/Twig, CSS natif ou Bootstrap

**Étapes** :

1. Recueil des besoins UX/UI et définition des personas.
2. Conception des maquettes et prototypes sur Figma.
3. Intégration du design via Twig et Bootstrap.

**Points clés** :

* **Templating** : Symfony Twig.
* **Styling** : CSS sur mesure ou Bootstrap.
* **Asset Management** : Webpack Encore ou AssetMapper.

### 2. Back‑end Back‑end

**Technologies** : PHP 8+, Symfony 6, API Platform, Messenger

**Étapes** :

1. Définition des cas d’usage et modélisation des entités.
2. Création des services métiers (authentification, facturation, paiements, PDF).
3. Documentation de l’API via OpenAPI/Swagger.

**Points clés** :

* **Authentification** : JWT (lexik/jwt-authentication) ou OAuth2 (Symfony Security).
* **Architecture** : monolithe modulable ou microservices via Symfony Messenger.
* **Traitements asynchrones** : Messenger avec RabbitMQ ou Amazon SQS.

### 3. Base de données

**Technologies** : MySQL, Doctrine ORM

**Étapes** :

1. Conception du schéma relationnel (ERD).
2. Choix des types de données et normalisation.
3. Mise en place de la stratégie de sauvegarde et de sécurité via Docker.

**Points clés** :

* **Migrations** : Doctrine Migrations.
* **Indexation** : champs de recherche et tris fréquents (date, client, statut).
* **Chiffrement** : protection des données sensibles.

### 4. Déploiement Déploiement

**Technologies** : Docker, Docker Compose, Kubernetes, Helm, Terraform

**Étapes** :

1. Rédaction des Dockerfiles pour chaque service.
2. Choix de l’orchestration (Compose vs Kubernetes + Helm).
3. Configuration du pipeline CI/CD.

**Points clés** :

* **CI/CD** : GitHub Actions ou GitLab CI.
* **Infrastructure as Code** : Terraform.
* **Scalabilité** : autoscaling Kubernetes.

### 5. Tests & maintenance

**Technologies** : PHPUnit, Behat, Cypress, Prometheus, Grafana, ELK Stack

**Étapes** :

1. Définition de la stratégie de tests (unitaires, intégration, e2e).
2. Mise en place du monitoring et de la collecte de logs.
3. Politique de sauvegarde et restauration.

**Points clés** :

* **Tests** : PHPUnit, Behat, Cypress.
* **Monitoring** : Prometheus + Grafana.
* **Logs** : ELK (Elasticsearch, Logstash, Kibana).
* **Backups** : automatisation des dumps PostgreSQL.

---

## 🔄 État d’avancement

Cette section présente les compétences et éléments critiques à maîtriser avant de démarrer le développement.

### Objectifs de maîtrise

* Workflow de facturation (ventes/achats), notes de frais et bilans.
* Maîtrise de React (router, gestion d’état, styling).
* Utilisation de Symfony (entités, contrôleurs, sécurité, Messenger).
* Conception de schéma de données relationnel.
* Génération de PDF (FPDI/TCPDF ou Dompdf).
* Intégration de Stripe et PayPal.
* Pipeline CI/CD et déploiement Docker/Kubernetes.
* Configuration de tests et monitoring.

---

## 📥 Installation

```bash
# Cloner le dépôt
git clone https://github.com/votre-org/facturation-saas.git
cd facturation-saas

# Installer les dépendances back‑end
cd backend
composer install

# Lancer Docker (MySQL et services)
docker-compose up -d
```

## ⚙️ Configuration

Dupliquez le fichier d’exemple et renseignez vos paramètres :

* `backend/.env.local`

## ▶️ Lancement

```bash
# Démarrer le serveur Symfony
cd backend
symfony server:start
```

## 🤝 Contribuer

1. Forkez le projet.
2. Créez une branche feature (`git checkout -b feature/ma-fonctionnalité`).
3. Commitez vos changements (`git commit -m "feat: description"`).
4. Ouvrez une Pull Request.

---

## 📝 Licence

Ce projet est distribué sous la licence MIT. Voir le fichier [LICENSE](LICENSE) pour plus de détails.
