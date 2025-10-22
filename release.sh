#!/bin/bash

# Script de release pour Laravel Test Accelerator
# Usage: ./release.sh [version] [message]

set -e

# Couleurs pour les messages
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Fonction pour afficher les messages colorés
print_message() {
    echo -e "${BLUE}[INFO]${NC} $1"
}

print_success() {
    echo -e "${GREEN}[SUCCESS]${NC} $1"
}

print_warning() {
    echo -e "${YELLOW}[WARNING]${NC} $1"
}

print_error() {
    echo -e "${RED}[ERROR]${NC} $1"
}

# Vérifier que nous sommes dans le bon répertoire
if [ ! -f "composer.json" ]; then
    print_error "composer.json non trouvé. Assurez-vous d'être dans le répertoire du projet."
    exit 1
fi

# Récupérer la version actuelle
CURRENT_VERSION=$(grep '"version"' composer.json | sed 's/.*"version": *"\([^"]*\)".*/\1/')
print_message "Version actuelle: $CURRENT_VERSION"

# Demander la nouvelle version si non fournie
if [ -z "$1" ]; then
    echo -n "Entrez la nouvelle version (ex: 1.0.1): "
    read NEW_VERSION
else
    NEW_VERSION=$1
fi

# Valider le format de version (semver)
if ! [[ $NEW_VERSION =~ ^[0-9]+\.[0-9]+\.[0-9]+$ ]]; then
    print_error "Format de version invalide. Utilisez le format semver (ex: 1.0.1)"
    exit 1
fi

# Message de commit par défaut
COMMIT_MESSAGE=${2:-"Release version $NEW_VERSION"}

print_message "Préparation de la release $NEW_VERSION..."

# 1. Mettre à jour la version dans composer.json
print_message "Mise à jour de la version dans composer.json..."
sed -i.bak "s/\"version\": \"$CURRENT_VERSION\"/\"version\": \"$NEW_VERSION\"/" composer.json
rm composer.json.bak

# 2. Mettre à jour le CHANGELOG.md
print_message "Mise à jour du CHANGELOG.md..."
if [ -f "CHANGELOG.md" ]; then
    # Ajouter la nouvelle version au début du changelog
    sed -i.bak "1i\\
## [$NEW_VERSION] - $(date +%Y-%m-%d)\\
\\
### Added\\
- Release $NEW_VERSION\\
\\
" CHANGELOG.md
    rm CHANGELOG.md.bak
else
    print_warning "CHANGELOG.md non trouvé, création d'un nouveau fichier..."
    cat > CHANGELOG.md << EOF
# Changelog

All notable changes to this project will be documented in this file.

## [$NEW_VERSION] - $(date +%Y-%m-%d)

### Added
- Release $NEW_VERSION

EOF
fi

# 3. Exécuter les tests
print_message "Exécution des tests..."
if ! composer test; then
    print_error "Les tests ont échoué. Annulation de la release."
    git checkout composer.json CHANGELOG.md
    exit 1
fi

# 4. Exécuter l'analyse statique
print_message "Exécution de l'analyse statique..."
if ! composer analyse; then
    print_error "L'analyse statique a échoué. Annulation de la release."
    git checkout composer.json CHANGELOG.md
    exit 1
fi

# 5. Formater le code
print_message "Formatage du code..."
composer format

# 6. Commiter les changements
print_message "Commit des changements..."
git add composer.json CHANGELOG.md
git commit -m "$COMMIT_MESSAGE"

# 7. Créer le tag
print_message "Création du tag v$NEW_VERSION..."
git tag -a "v$NEW_VERSION" -m "Release version $NEW_VERSION"

# 8. Pousser vers GitHub
print_message "Push vers GitHub..."
git push origin main
git push origin "v$NEW_VERSION"

print_success "Release $NEW_VERSION créée avec succès!"
print_message "Le workflow GitHub Actions va maintenant créer la release automatiquement."
print_message "Vous pouvez vérifier le statut sur: https://github.com/gessyken/laravel-test-accelerator/actions"

# 9. Instructions pour Packagist
print_message ""
print_warning "N'oubliez pas de mettre à jour Packagist:"
print_message "1. Allez sur https://packagist.org/packages/gessyken/laravel-test-accelerator"
print_message "2. Cliquez sur 'Update' pour synchroniser avec GitHub"
print_message "3. Ou configurez le webhook GitHub pour la mise à jour automatique"

print_success "Release terminée! 🎉"
