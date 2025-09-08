# Laravel Test Accelerator

[![Latest Version on Packagist](https://img.shields.io/packagist/v/gessyken/laravel-test-accelerator.svg?style=flat-square)](https://packagist.org/packages/gessyken/laravel-test-accelerator)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/gessyken/laravel-test-accelerator/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/gessyken/laravel-test-accelerator/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/gessyken/laravel-test-accelerator/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/gessyken/laravel-test-accelerator/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/gessyken/laravel-test-accelerator.svg?style=flat-square)](https://packagist.org/packages/gessyken/laravel-test-accelerator)

A powerful Laravel package to accelerate the development and testing process of your applications. Laravel Test Accelerator provides intelligent tools to automatically generate tests, analyze code coverage, and optimize your test performance.

## ✨ Features

-   🚀 **Automatic Test Generation** - Create unit and feature tests with just a few clicks
-   📊 **Code Coverage Analysis** - Identify untested areas of your application
-   ⚡ **Performance Optimization** - Detect and resolve slow tests
-   🤖 **AI Integration** - Use artificial intelligence to generate intelligent test cases
-   📈 **Detailed Reports** - Get comprehensive insights into your test quality
-   🔧 **Flexible Configuration** - Customize behavior according to your needs

## 📦 Installation

You can install the package via Composer:

```bash
composer require gessyken/laravel-test-accelerator
```

### Configuration

Publish the configuration file:

```bash
php artisan vendor:publish --tag="laravel-test-accelerator-config"
```

The configuration file will be published to `config/laravel-test-accelerator.php`:

```php
<?php

return [
    'ai_provider' => env('TEST_ACCELERATOR_AI_PROVIDER', 'openai'),
    'ai_api_key' => env('TEST_ACCELERATOR_AI_API_KEY'),
    'test_paths' => [
        'tests/Unit',
        'tests/Feature',
    ],
    'ignore_directories' => [
        'vendor',
        'node_modules',
        'storage',
    ],
    'benchmark' => [
        'slow_threshold' => 1000, // ms
        'memory_threshold' => 1024, // KB
    ],
];
```

### Environment Variables

Add these variables to your `.env` file:

```env
TEST_ACCELERATOR_AI_PROVIDER=openai
TEST_ACCELERATOR_AI_API_KEY=your_api_key_here
```

## 🚀 Usage

### Test Generation

Generate tests for a specific file:

```bash
php artisan test:generate app/Models/User.php
```

Generate tests for an entire directory:

```bash
php artisan test:generate app/Services/
```

Use AI to generate smarter tests:

```bash
php artisan test:generate app/Models/User.php --ai
```

### Coverage Analysis

Analyze your test coverage:

```bash
php artisan test:coverage
```

Generate a detailed HTML report:

```bash
php artisan test:coverage --report
```

Set a minimum coverage threshold:

```bash
php artisan test:coverage --threshold=80
```

### Performance Benchmark

Analyze your test performance:

```bash
php artisan test:benchmark
```

### Programmatic Usage

```php
use KENCODE\LaravelTestAccelerator\Facades\LaravelTestAccelerator;

// Generate tests
LaravelTestAccelerator::generateTests('app/Models/User.php');

// Analyze coverage
$coverage = LaravelTestAccelerator::analyzeCoverage();

// Get performance statistics
$stats = LaravelTestAccelerator::getPerformanceStats();
```

## 📋 Usage Examples

### Example 1: Test Generation for an Eloquent Model

```php
// app/Models/User.php
class User extends Authenticatable
{
    protected $fillable = ['name', 'email', 'password'];

    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    public function getFullNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }
}
```

After running `php artisan test:generate app/Models/User.php`, you'll get:

```php
// tests/Unit/UserTest.php
class UserTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_create_a_user()
    {
        $user = User::factory()->create();
        $this->assertNotNull($user);
    }

    /** @test */
    public function it_can_get_full_name_attribute()
    {
        $user = User::factory()->create([
            'first_name' => 'John',
            'last_name' => 'Doe'
        ]);

        $this->assertEquals('John Doe', $user->full_name);
    }

    /** @test */
    public function it_has_many_posts()
    {
        $user = User::factory()->create();
        $post = Post::factory()->create(['user_id' => $user->id]);

        $this->assertTrue($user->posts->contains($post));
    }
}
```

### Example 2: Coverage Analysis

```bash
$ php artisan test:coverage

+------------------+----------+---------+
| File             | Coverage | Status  |
+------------------+----------+---------+
| app/Models/User  | 95%      | ✅ Good |
| app/Services/... | 67%      | ⚠️ Low  |
| app/Http/...     | 89%      | ✅ Good |
+------------------+----------+---------+

Overall coverage: 84%
```

## 🧪 Testing

Run the package tests:

```bash
composer test
```

Run tests with coverage:

```bash
composer test-coverage
```

## 📊 Reports and Metrics

The package generates several types of reports:

-   **HTML Coverage Report** - Interactive coverage visualization
-   **Performance Report** - Identification of slow tests
-   **Analysis Report** - Improvement suggestions

## 🔧 Advanced Configuration

### Customizing Test Templates

You can customize test generation templates by publishing the views:

```bash
php artisan vendor:publish --tag="laravel-test-accelerator-views"
```

### CI/CD Integration

Add these commands to your CI pipeline:

```yaml
# .github/workflows/tests.yml
- name: Generate test coverage
  run: php artisan test:coverage --threshold=80

- name: Run performance benchmark
  run: php artisan test:benchmark
```

## 🤝 Contributing

Contributions are welcome! Please read our [contributing guide](CONTRIBUTING.md) for more details.

### Local Development

1. Clone the repository
2. Install dependencies: `composer install`
3. Run tests: `composer test`
4. Create your feature branch
5. Submit a Pull Request

## 📝 Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## 🔒 Security

If you discover a security vulnerability, please send an email to gessyken@gmail.com instead of using the bug tracker.

## 📄 License

The Laravel Test Accelerator package is open-sourced software licensed under the [MIT license](LICENSE.md).

## 👨‍💻 Author

**Aurel KENNE**

-   GitHub: [@gessyken](https://github.com/gessyken)
-   Email: gessyken@gmail.com
-   Website: [https://accelerator.kencode.dev](https://accelerator.kencode.dev)

## 🙏 Acknowledgments

-   [Spatie](https://spatie.be) for the Laravel package development tools
-   [Laravel](https://laravel.com) for the amazing framework
-   All contributors who make this project possible

---

⭐ If this package helps you, please give it a star on GitHub!
