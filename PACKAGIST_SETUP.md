# Configuration Packagist pour Laravel Test Accelerator

## Instructions pour publier sur Packagist

### 1. Créer un compte Packagist

-   Aller sur https://packagist.org/
-   Se connecter avec votre compte GitHub

### 2. Soumettre le package

-   Cliquer sur "Submit"
-   Entrer l'URL du repository: `https://github.com/gessyken/laravel-test-accelerator`
-   Packagist va analyser le composer.json automatiquement

### 3. Configuration du webhook GitHub (recommandé)

Pour que Packagist se mette à jour automatiquement à chaque push:

1. Aller sur https://packagist.org/packages/gessyken/laravel-test-accelerator
2. Cliquer sur "Settings"
3. Copier l'URL du webhook
4. Aller sur GitHub: https://github.com/gessyken/laravel-test-accelerator/settings/hooks
5. Ajouter un nouveau webhook:
    - Payload URL: [URL copiée de Packagist]
    - Content type: application/json
    - Events: Just the push event
    - Active: ✓

### 4. Vérification

Après la première release, vérifier que le package est disponible:

```bash
composer show gessyken/laravel-test-accelerator
```

## Configuration du composer.json pour Packagist

Le fichier composer.json est déjà correctement configuré avec:

-   ✅ name: "gessyken/laravel-test-accelerator"
-   ✅ description: Description claire
-   ✅ license: "MIT"
-   ✅ authors: Informations de l'auteur
-   ✅ keywords: Mots-clés pertinents
-   ✅ homepage: URL du repository
-   ✅ autoload: Configuration PSR-4
-   ✅ extra.laravel: Configuration Laravel

## Tags et versions

Le système de versioning suit Semantic Versioning (SemVer):

-   MAJOR.MINOR.PATCH (ex: 1.0.0)
-   Les tags Git doivent commencer par 'v' (ex: v1.0.0)
-   Chaque release crée automatiquement un tag Git

## Workflow de release

1. Exécuter `./release.sh [version] [message]`
2. Le script va:
    - Mettre à jour composer.json
    - Mettre à jour CHANGELOG.md
    - Exécuter les tests
    - Créer le commit et le tag
    - Pousser vers GitHub
3. GitHub Actions va créer la release automatiquement
4. Packagist se mettra à jour via le webhook
