# 🚀 Guide de Publication sur Packagist

## ✅ Ce qui a été configuré

### 1. Système de Versioning

-   ✅ Version ajoutée dans `composer.json` (1.0.0)
-   ✅ Tag Git créé (`v1.0.0`)
-   ✅ Workflow GitHub Actions configuré
-   ✅ Script de release automatisé (`release.sh`)

### 2. Configuration Packagist

-   ✅ `composer.json` correctement configuré
-   ✅ Repository GitHub configuré
-   ✅ Documentation Packagist créée

## 📋 Étapes pour publier sur Packagist

### Étape 1: Créer un compte Packagist

1. Aller sur https://packagist.org/
2. Cliquer sur "Log in"
3. Se connecter avec votre compte GitHub

### Étape 2: Soumettre le package

1. Cliquer sur "Submit" dans Packagist
2. Entrer l'URL du repository: `https://github.com/gessyken/laravel-test-accelerator`
3. Cliquer sur "Check" pour valider
4. Cliquer sur "Submit" pour publier

### Étape 3: Configurer le webhook GitHub (Recommandé)

Pour que Packagist se mette à jour automatiquement:

1. **Sur Packagist:**

    - Aller sur https://packagist.org/packages/gessyken/laravel-test-accelerator
    - Cliquer sur "Settings"
    - Copier l'URL du webhook

2. **Sur GitHub:**
    - Aller sur https://github.com/gessyken/laravel-test-accelerator/settings/hooks
    - Cliquer sur "Add webhook"
    - Coller l'URL du webhook
    - Content type: `application/json`
    - Events: Sélectionner "Just the push event"
    - Active: ✓
    - Cliquer sur "Add webhook"

## 🧪 Test de l'installation

Une fois publié sur Packagist, tester l'installation:

```bash
# Dans un nouveau projet Laravel
composer require gessyken/laravel-test-accelerator --dev

# Publier la configuration
php artisan vendor:publish --tag="laravel-test-accelerator-config"

# Tester les commandes
php artisan test:generate --help
php artisan test:coverage --help
php artisan test:benchmark --help
```

## 🔄 Workflow de Release Future

Pour les prochaines versions:

```bash
# Utiliser le script de release
./release.sh 1.0.1 "Fix bug in test generation"

# Ou manuellement:
# 1. Mettre à jour la version dans composer.json
# 2. git add composer.json
# 3. git commit -m "Release version 1.0.1"
# 4. git tag -a v1.0.1 -m "Release version 1.0.1"
# 5. git push origin main
# 6. git push origin v1.0.1
```

## 📊 Vérification du Statut

### Vérifier que le package est disponible:

```bash
composer show gessyken/laravel-test-accelerator
```

### Vérifier les releases GitHub:

-   https://github.com/gessyken/laravel-test-accelerator/releases

### Vérifier le workflow GitHub Actions:

-   https://github.com/gessyken/laravel-test-accelerator/actions

## 🎯 Prochaines Étapes

1. **Publier sur Packagist** (suivre les étapes ci-dessus)
2. **Tester l'installation** dans un projet Laravel
3. **Configurer le webhook** pour les mises à jour automatiques
4. **Documenter l'utilisation** dans le README
5. **Créer des exemples** d'utilisation

## 🚨 Résolution de Problèmes

### Erreur "Could not find a version"

-   Vérifier que le tag Git existe: `git tag`
-   Vérifier que le tag est poussé: `git push origin v1.0.0`
-   Attendre quelques minutes pour la synchronisation Packagist

### Erreur "Package not found"

-   Vérifier que le package est publié sur Packagist
-   Vérifier l'orthographe du nom: `gessyken/laravel-test-accelerator`

### Erreur de configuration

-   Vérifier que `composer.json` est valide: `composer validate`
-   Vérifier que tous les champs requis sont présents

## 📞 Support

Si vous rencontrez des problèmes:

1. Vérifier les logs GitHub Actions
2. Consulter la documentation Packagist
3. Vérifier la configuration du webhook
4. Contacter le support Packagist si nécessaire
