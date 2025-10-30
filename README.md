# Omsy-Source
Un système de gestion de contenu (CMS) basé sur **PHP**, sans l'interface de panneau d’administration.

## 📚 Table des matières

* [À propos](#à-propos)
* [Fonctionnalités](#fonctionnalités)
* [Prérequis](#prérequis)
* [Installation](#installation)
* [Configuration](#configuration)
* [Structure du projet](#structure-du-projet)
* [Utilisation](#utilisation)
* [Pile technologique](#pile-technologique)
* [Contribution](#contribution)
* [Licence](#licence)

## ℹ️ À propos

**Omsy-Source** est un système de gestion de contenu (CMS) en PHP conçu pour créer et administrer des applications web.
Le système propose une architecture modulaire avec un panneau d’administration dédié à la gestion du contenu, des utilisateurs et de la configuration du système.

## ✨ Fonctionnalités

* **Architecture modulaire** : structure organisée avec séparation des responsabilités
* **Panneau d’administration** : interface dédiée à la gestion du système (Omsy-admin-panel)
* **Sécurité des sessions** : gestion sécurisée des sessions avec cookies HTTP-only et SECURE
* **Réécriture d’URL** : prise en charge de la réécriture d’URL personnalisée via `.htaccess` et `rewrite.json`
* **Application Web Progressive (PWA)** : manifeste PWA pour une expérience semblable à une application mobile
* **Accès basé sur les rôles** : système de gestion des rôles basé sur les sessions et une base de Bits

## 🧾 Prérequis

* PHP 7.4 ou supérieur
* Serveur web Apache avec `mod_rewrite` activé
* Base de données MySQL/MariaDB (si applicable)
* Prise en charge des sessions PHP activée

## ⚙️ Installation

1. **Cloner le dépôt**

   ```bash
   git clone https://github.com/is-omini/Omsy-Source.git
   cd Omsy-Source
   ```

2. **Configurer votre serveur web**

   * Pointez la racine du document vers le répertoire **Omsy-Source**
   * Assurez-vous que `mod_rewrite` est activé dans Apache
   * Autorisez les substitutions via `.htaccess`

3. **Définir les permissions appropriées**

   ```bash
   chmod 755 .
   chmod 644 .htaccess
   ```

4. **Configurer la base de données** (si nécessaire)

   * Mettez à jour les paramètres de connexion dans le fichiers de configuration Core.xml

## ⚙️ Configuration

### Configuration de l’environnement

Le système utilise par défaut le fuseau horaire `Europe/Paris`.
Pour le modifier, éditez le fichier `index.php` :

```php
date_default_timezone_set('Your/Timezone');
```

### Configuration des sessions

La sécurité des sessions est configurée dans `index.php` :

* Les cookies HTTP-only sont activés
* La limite de mémoire est fixée à 800 Mo

### Réécriture d’URL

Le projet inclut :

* `.htaccess` pour les règles de réécriture Apache
* `rewrite.json` pour les règles de réécriture personnalisées Omsy-Rewrite

## 🗂️ Structure du projet

```
Omsy-Source/
├── index.php          # Point d’entrée principal
├── manifest.json      # Fichier manifeste PWA
├── Core.xml           # Configuration principale
├── rewrite.json       # Règles de réécriture d’URL
├── .htaccess          # Configuration Apache
├── .gitignore         # Règles Git d’exclusion
├── .gitattributes     # Attributs Git
├── panel/             # Répertoire du panneau d’administration
│   ├── index.php
│   ├── getter.php
│   └── interface/
├── sys/               # Fichiers du noyau système
│   └── class/
│       └── CMS.php    # Classe principale du CMS
├── usr/               # Fichiers liés aux utilisateurs
└── share/             # Ressources partagées
```

## 🚀 Utilisation

### Utilisation de base

1. Accédez à l’application via votre navigateur web
2. Le point d’entrée principal (`index.php`) initialise le CMS
3. Rendez-vous sur `/panel` pour accéder à l’interface d’administration

### Initialisation du CMS

Le système s’initialise via le fichier principal `index.php` :

```php
include "./sys/class/CMS.php";
new CMS();
```

## 💻 Pile technologique

* **Backend** : PHP
* **Frontend** : HTML, CSS, JavaScript
* **Serveur web** : Apache
* **Supplémentaire** : Hack (dialecte de PHP)

### Répartition des langages

* PHP : ~157 Ko
* CSS : ~22 Ko
* JavaScript : ~15 Ko
* Hack : ~640 octets

## 🤝 Contribution

Les contributions sont les bienvenues ! Vous pouvez soumettre une **Pull Request**.
Pour les modifications majeures, ouvrez d’abord une **issue** pour discuter des changements proposés.

1. Forkez le dépôt
2. Créez votre branche de fonctionnalité (`git checkout -b feature/AmazingFeature`)
3. Validez vos modifications (`git commit -m 'Ajout de AmazingFeature'`)
4. Poussez votre branche (`git push origin feature/AmazingFeature`)
5. Ouvrez une Pull Request

## 📜 Licence

Ce projet ne spécifie pas de licence.
Veuillez contacter le propriétaire du dépôt pour plus d’informations à ce sujet.

## 🔗 Liens

* **Dépôt** : [https://github.com/is-omini/Omsy-Source](https://github.com/is-omini/Omsy-Source)
* **Problèmes / Issues** : [https://github.com/is-omini/Omsy-Source/issues](https://github.com/is-omini/Omsy-Source/issues)

## 👤 Auteur

**is-omini**

* GitHub : [@is-omini](https://github.com/is-omini)

## 📝 Notes

* Il s’agit du dépôt du code source du CMS **Omsy**
* Assurez-vous de configurer correctement votre environnement serveur avant le déploiement
* Vérifiez les exigences de `.htaccess` pour une réécriture d’URL correcte
* Passez en revue les paramètres de sécurité avant le déploiement en production

* File name : ce_si_est_un_nom
* Variable name : ceSiEstUnNom
* File class name : CeSiEstUneClass
* Variable class name : CeSiEstUneClass
* Variable constance name : \_\_ce_si_est_une_constance\_\_

---

**Dernière mise à jour** : basée sur les métadonnées du dépôt de **mai 2025**

---
