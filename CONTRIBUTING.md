# Contributing Guide

Thank you for your interest in contributing to the Laravel Test Accelerator package! This document will guide you through the contribution process.

## 🚀 Quick Start

### Prerequisites

-   PHP 8.4 or higher
-   Composer
-   Git
-   A Laravel application for testing

### Development Setup

1. **Fork the repository** on GitHub

2. **Clone your fork locally**:

    ```bash
    git clone https://github.com/your-username/laravel-test-accelerator.git
    cd laravel-test-accelerator
    ```

3. **Install dependencies**:

    ```bash
    composer install
    ```

4. **Configure test environment**:
    ```bash
    cp .env.example .env
    # Configure your environment variables
    ```

## 🧪 Testing

### Running Tests

```bash
# All tests
composer test

# Tests with coverage
composer test-coverage

# Specific tests
./vendor/bin/pest tests/Unit/TestGeneratorTest.php
```

### Code Quality

```bash
# Static analysis with PHPStan
composer analyse

# Code formatting with Laravel Pint
composer format
```

## 📝 Contribution Process

### 1. Create a Branch

```bash
git checkout -b feature/my-new-feature
# or
git checkout -b fix/bug-fix
```

### 2. Develop

-   Write clean and well-documented code
-   Add tests for your new features
-   Follow PSR-12 coding standards
-   Update documentation if necessary

### 3. Test

```bash
# Run all tests
composer test

# Check code quality
composer analyse
composer format
```

### 4. Commit

Use clear and descriptive commit messages:

```bash
git add .
git commit -m "feat: add test generation for Eloquent models"
```

**Commit Conventions**:

-   `feat:` : New feature
-   `fix:` : Bug fix
-   `docs:` : Documentation
-   `style:` : Formatting, missing semicolons, etc.
-   `refactor:` : Code refactoring
-   `test:` : Adding or modifying tests
-   `chore:` : Maintenance, dependencies, etc.

### 5. Push and Pull Request

```bash
git push origin feature/my-new-feature
```

Then create a Pull Request on GitHub with:

-   Clear description of changes
-   Reference to related issues (if applicable)
-   Screenshots (if UI)
-   Checklist of tests performed

## 📋 Pre-submission Checklist

-   [ ] Code tested locally
-   [ ] Unit tests added/modified
-   [ ] Documentation updated
-   [ ] Code formatted with Laravel Pint
-   [ ] PHPStan analysis without errors
-   [ ] Clear commit messages
-   [ ] Well-documented Pull Request

## 🏗️ Package Architecture

### Folder Structure

```
src/
├── Commands/           # Artisan Commands
├── Services/           # Business Logic
├── Facades/            # Laravel Facades
├── Resources/          # Templates and Stubs
└── LaravelTestAcceleratorServiceProvider.php
```

### Adding a New Command

1. Create the class in `src/Commands/`
2. Register it in the Service Provider
3. Add corresponding tests
4. Document the usage

### Adding a New Service

1. Create the class in `src/Services/`
2. Register it in the Service Provider
3. Create a facade if necessary
4. Add unit tests

## 🐛 Reporting Bugs

Use the bug report template on GitHub with:

-   Detailed description of the problem
-   Steps to reproduce
-   PHP/Laravel version
-   Error logs (if applicable)
-   Screenshots (if UI)

## 💡 Proposing Features

Use the feature request template on GitHub with:

-   Clear description of the feature
-   Concrete use cases
-   Usage examples
-   Impact on existing API

## 📚 Useful Resources

-   [Laravel Documentation](https://laravel.com/docs)
-   [Laravel Package Guide](https://laravelpackage.training)
-   [PSR-12 Standards](https://www.php-fig.org/psr/psr-12/)
-   [Pest PHP Guide](https://pestphp.com/docs)

## 🤝 Code of Conduct

We are committed to providing a welcoming and inclusive environment. Please:

-   Be respectful and constructive
-   Show empathy
-   Accept constructive criticism
-   Focus on what's best for the community

## 📞 Support

-   **Issues** : [GitHub Issues](https://github.com/gessyken/laravel-test-accelerator/issues)
-   **Discussions** : [GitHub Discussions](https://github.com/gessyken/laravel-test-accelerator/discussions)
-   **Email** : gessyken@gmail.com
-   **Website** : [https://accelerator.kencode.dev](https://accelerator.kencode.dev)

## 🙏 Acknowledgments

Thank you to all contributors who make this project possible!

---

**Note** : This guide is living and evolves with the project. Feel free to suggest improvements!
