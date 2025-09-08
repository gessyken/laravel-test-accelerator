# Website Documentation - Laravel Test Accelerator

This document contains the content structure and pages for the official documentation website at [https://accelerator.kencode.dev](https://accelerator.kencode.dev).

## 🏠 Homepage Content

### Hero Section

```html
<h1>Laravel Test Accelerator</h1>
<p class="lead">
    Accelerate your Laravel development with intelligent test generation,
    coverage analysis, and performance optimization.
</p>

<div class="cta-buttons">
    <a href="/docs/installation" class="btn btn-primary">Get Started</a>
    <a href="/docs/examples" class="btn btn-outline">View Examples</a>
</div>

<div class="stats">
    <div class="stat">
        <span class="number">10k+</span>
        <span class="label">Downloads</span>
    </div>
    <div class="stat">
        <span class="number">95%</span>
        <span class="label">Test Coverage</span>
    </div>
    <div class="stat">
        <span class="number">50ms</span>
        <span class="label">Avg Generation Time</span>
    </div>
</div>
```

### Features Section

```html
<section class="features">
    <div class="feature">
        <div class="icon">🚀</div>
        <h3>Automatic Test Generation</h3>
        <p>
            Generate comprehensive unit and feature tests with just a few
            clicks. Support for Eloquent models, controllers, services, and
            more.
        </p>
    </div>

    <div class="feature">
        <div class="icon">📊</div>
        <h3>Code Coverage Analysis</h3>
        <p>
            Identify untested areas of your application with detailed coverage
            reports and interactive visualizations.
        </p>
    </div>

    <div class="feature">
        <div class="icon">⚡</div>
        <h3>Performance Optimization</h3>
        <p>
            Detect and resolve slow tests with comprehensive performance
            analysis and benchmarking tools.
        </p>
    </div>

    <div class="feature">
        <div class="icon">🤖</div>
        <h3>AI Integration</h3>
        <p>
            Use artificial intelligence to generate intelligent test cases with
            OpenAI, Anthropic, and other providers.
        </p>
    </div>

    <div class="feature">
        <div class="icon">📈</div>
        <h3>Detailed Reports</h3>
        <p>
            Get comprehensive insights into your test quality with HTML, XML,
            and Clover report formats.
        </p>
    </div>

    <div class="feature">
        <div class="icon">🔧</div>
        <h3>Flexible Configuration</h3>
        <p>
            Customize behavior according to your needs with extensive
            configuration options and templates.
        </p>
    </div>
</section>
```

## 📚 Documentation Pages

### 1. Installation (`/docs/installation`)

````markdown
# Installation

## Prerequisites

-   PHP 8.4 or higher
-   Laravel 11.x or 12.x
-   Composer

## Quick Install

```bash
composer require gessyken/laravel-test-accelerator
```
````

## Configuration

Publish the configuration file:

```bash
php artisan vendor:publish --tag="laravel-test-accelerator-config"
```

## Environment Variables

Add these to your `.env` file:

```env
TEST_ACCELERATOR_AI_PROVIDER=openai
TEST_ACCELERATOR_AI_API_KEY=your_api_key_here
```

## Next Steps

-   [Basic Usage](/docs/usage)
-   [Configuration](/docs/configuration)
-   [Examples](/docs/examples)

````

### 2. Basic Usage (`/docs/usage`)

```markdown
# Basic Usage

## Generating Tests

### For a Single File

```bash
php artisan test:generate app/Models/User.php
````

### For a Directory

```bash
php artisan test:generate app/Services/
```

### With AI

```bash
php artisan test:generate app/Models/User.php --ai
```

## Coverage Analysis

```bash
# Basic analysis
php artisan test:coverage

# With HTML report
php artisan test:coverage --report

# With threshold
php artisan test:coverage --threshold=80
```

## Performance Benchmark

```bash
php artisan test:benchmark
```

## Programmatic Usage

```php
use KENCODE\LaravelTestAccelerator\Facades\LaravelTestAccelerator;

// Generate tests
LaravelTestAccelerator::generateTests('app/Models/User.php');

// Analyze coverage
$coverage = LaravelTestAccelerator::analyzeCoverage();

// Get performance stats
$stats = LaravelTestAccelerator::getPerformanceStats();
```

````

### 3. Configuration (`/docs/configuration`)

```markdown
# Configuration

## Basic Configuration

The package configuration is located in `config/laravel-test-accelerator.php`:

```php
return [
    'ai_provider' => env('TEST_ACCELERATOR_AI_PROVIDER', 'openai'),
    'ai_api_key' => env('TEST_ACCELERATOR_AI_API_KEY'),
    'test_paths' => [
        'tests/Unit',
        'tests/Feature',
    ],
    // ... more options
];
````

## AI Configuration

### Supported Providers

-   **OpenAI**: GPT-4, GPT-3.5-turbo
-   **Anthropic**: Claude-3-sonnet, Claude-3-haiku
-   **Google**: Gemini Pro

### Environment Variables

```env
TEST_ACCELERATOR_AI_PROVIDER=openai
TEST_ACCELERATOR_AI_API_KEY=your_api_key
TEST_ACCELERATOR_AI_MODEL=gpt-4
```

## Coverage Configuration

```php
'coverage' => [
    'threshold' => 80, // %
    'html_reports' => true,
    'xml_reports' => false,
    'clover_reports' => false,
],
```

## Performance Configuration

```php
'benchmark' => [
    'slow_threshold' => 1000, // ms
    'memory_threshold' => 1024, // KB
    'enable_profiling' => true,
],
```

````

### 4. Examples (`/docs/examples`)

```markdown
# Examples

## Eloquent Model Testing

### Input: User Model

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

    public function isAdmin()
    {
        return $this->role === 'admin';
    }
}
````

### Generated Tests

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

    /** @test */
    public function it_can_check_if_user_is_admin()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $user = User::factory()->create(['role' => 'user']);

        $this->assertTrue($admin->isAdmin());
        $this->assertFalse($user->isAdmin());
    }
}
```

## Controller Testing

### Input: UserController

```php
// app/Http/Controllers/UserController.php
class UserController extends Controller
{
    public function index()
    {
        return User::paginate(15);
    }

    public function store(Request $request)
    {
        $user = User::create($request->validated());
        return response()->json($user, 201);
    }

    public function show(User $user)
    {
        return response()->json($user);
    }
}
```

### Generated Tests

```php
// tests/Feature/UserControllerTest.php
class UserControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_list_users()
    {
        User::factory()->count(3)->create();

        $response = $this->getJson('/api/users');

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'data' => [
                        '*' => ['id', 'name', 'email']
                    ]
                ]);
    }

    /** @test */
    public function it_can_create_a_user()
    {
        $userData = [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'password'
        ];

        $response = $this->postJson('/api/users', $userData);

        $response->assertStatus(201)
                ->assertJsonFragment(['name' => 'John Doe']);

        $this->assertDatabaseHas('users', ['email' => 'john@example.com']);
    }

    /** @test */
    public function it_can_show_a_user()
    {
        $user = User::factory()->create();

        $response = $this->getJson("/api/users/{$user->id}");

        $response->assertStatus(200)
                ->assertJsonFragment(['id' => $user->id]);
    }
}
```

## Service Testing

### Input: PaymentService

```php
// app/Services/PaymentService.php
class PaymentService
{
    public function processPayment($amount, $currency = 'USD')
    {
        if ($amount <= 0) {
            throw new InvalidArgumentException('Amount must be positive');
        }

        return [
            'transaction_id' => Str::uuid(),
            'amount' => $amount,
            'currency' => $currency,
            'status' => 'completed'
        ];
    }
}
```

### Generated Tests

```php
// tests/Unit/PaymentServiceTest.php
class PaymentServiceTest extends TestCase
{
    private PaymentService $paymentService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->paymentService = new PaymentService();
    }

    /** @test */
    public function it_can_process_a_valid_payment()
    {
        $result = $this->paymentService->processPayment(100, 'USD');

        $this->assertArrayHasKey('transaction_id', $result);
        $this->assertEquals(100, $result['amount']);
        $this->assertEquals('USD', $result['currency']);
        $this->assertEquals('completed', $result['status']);
    }

    /** @test */
    public function it_throws_exception_for_invalid_amount()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Amount must be positive');

        $this->paymentService->processPayment(-100);
    }

    /** @test */
    public function it_uses_default_currency()
    {
        $result = $this->paymentService->processPayment(50);

        $this->assertEquals('USD', $result['currency']);
    }
}
```

````

### 5. API Reference (`/docs/api`)

```markdown
# API Reference

## Facades

### LaravelTestAccelerator

The main facade for interacting with the package.

```php
use KENCODE\LaravelTestAccelerator\Facades\LaravelTestAccelerator;
````

#### Methods

##### generateTests(string $path, array $options = [])

Generate tests for a file or directory.

**Parameters:**

-   `$path` (string): Path to the file or directory
-   `$options` (array): Generation options

**Returns:** `bool`

**Example:**

```php
LaravelTestAccelerator::generateTests('app/Models/User.php');
LaravelTestAccelerator::generateTests('app/Services/', ['ai' => true]);
```

##### analyzeCoverage(array $options = [])

Analyze code coverage.

**Parameters:**

-   `$options` (array): Analysis options

**Returns:** `array`

**Example:**

```php
$coverage = LaravelTestAccelerator::analyzeCoverage(['threshold' => 80]);
```

##### getPerformanceStats()

Get performance statistics.

**Returns:** `array`

**Example:**

```php
$stats = LaravelTestAccelerator::getPerformanceStats();
```

## Services

### TestGenerator

Main service for test generation.

```php
use KENCODE\LaravelTestAccelerator\Services\TestGenerator;

$generator = new TestGenerator();
```

#### Methods

##### generateBasicTests(string $path): bool

Generate basic tests for a file or directory.

##### generateForModel(string $modelPath): bool

Generate tests specifically for an Eloquent model.

##### generateWithAI(string $path, string $prompt): bool

Generate tests using AI.

### CoverageAnalyzer

Service for coverage analysis.

```php
use KENCODE\LaravelTestAccelerator\Services\CoverageAnalyzer;

$analyzer = new CoverageAnalyzer();
```

#### Methods

##### analyze(array $options = []): array

Analyze code coverage.

##### generateHtmlReport(string $outputPath): bool

Generate HTML coverage report.

##### getCoverageStats(): array

Get coverage statistics.

### PerformanceAnalyzer

Service for performance analysis.

```php
use KENCODE\LaravelTestAccelerator\Services\PerformanceAnalyzer;

$analyzer = new PerformanceAnalyzer();
```

#### Methods

##### analyze(array $options = []): array

Analyze test performance.

##### getSlowTests(): array

Get list of slow tests.

##### getMemoryUsage(): array

Get memory usage statistics.

````

### 6. Advanced Usage (`/docs/advanced`)

```markdown
# Advanced Usage

## Custom Templates

### Creating Custom Templates

1. Publish the views:
```bash
php artisan vendor:publish --tag="laravel-test-accelerator-views"
````

2. Edit the templates in `resources/views/vendor/laravel-test-accelerator/stubs/`

### Template Variables

-   `{{CLASS}}` - Class name
-   `{{NAMESPACE}}` - Class namespace
-   `{{TEST_CLASS}}` - Test class name
-   `{{METHODS}}` - Class methods
-   `{{PROPERTIES}}` - Class properties

### Example Custom Template

```php
<?php

namespace {{NAMESPACE}};

use Tests\TestCase;
use {{CLASS}};

class {{TEST_CLASS}} extends TestCase
{
    /** @test */
    public function it_can_be_instantiated()
    {
        $instance = new {{CLASS}}();
        $this->assertNotNull($instance);
    }

    {{METHODS}}
}
```

## CI/CD Integration

### GitHub Actions

```yaml
name: Tests

on: [push, pull_request]

jobs:
    test:
        runs-on: ubuntu-latest

        steps:
            - uses: actions/checkout@v3

            - name: Setup PHP
              uses: shivammathur/setup-php@v2
              with:
                  php-version: "8.4"
                  extensions: mbstring, xml, curl, xdebug

            - name: Install dependencies
              run: composer install

            - name: Run tests
              run: composer test

            - name: Generate coverage
              run: php artisan test:coverage --html --threshold=80

            - name: Upload coverage
              uses: codecov/codecov-action@v3
```

### GitLab CI

```yaml
test:
    stage: test
    script:
        - composer install
        - composer test
        - php artisan test:coverage --html --threshold=80
    artifacts:
        reports:
            coverage_report:
                coverage_format: cobertura
                path: coverage/cobertura.xml
```

## Custom Commands

### Creating Custom Commands

```php
<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use KENCODE\LaravelTestAccelerator\Services\TestGenerator;

class CustomTestGenerator extends Command
{
    protected $signature = 'custom:generate-tests {path}';
    protected $description = 'Generate tests with custom logic';

    public function handle(TestGenerator $generator)
    {
        $path = $this->argument('path');

        // Custom logic here
        $result = $generator->generateBasicTests($path);

        if ($result) {
            $this->info("Tests generated successfully for {$path}");
        } else {
            $this->error("Failed to generate tests for {$path}");
        }
    }
}
```

## Event Listeners

### Test Generation Events

```php
use KENCODE\LaravelTestAccelerator\Events\TestsGenerated;

Event::listen(TestsGenerated::class, function ($event) {
    Log::info("Tests generated for: {$event->path}");
    // Custom logic here
});
```

### Coverage Analysis Events

```php
use KENCODE\LaravelTestAccelerator\Events\CoverageAnalyzed;

Event::listen(CoverageAnalyzed::class, function ($event) {
    if ($event->coverage < 80) {
        // Send notification
        Mail::to('team@example.com')->send(new LowCoverageMail($event->coverage));
    }
});
```

````

### 7. Troubleshooting (`/docs/troubleshooting`)

```markdown
# Troubleshooting

## Common Issues

### 1. API Key Error

**Error:** `Invalid API key`

**Solution:** Check your API key in the `.env` file

```env
TEST_ACCELERATOR_AI_API_KEY=your_actual_api_key_here
````

### 2. Tests Not Generated

**Error:** `No tests generated for path: app/Models/User.php`

**Solutions:**

-   Verify the file exists
-   Check write permissions
-   Use the `--force` option

```bash
php artisan test:generate app/Models/User.php --force
```

### 3. Coverage Analysis Failed

**Error:** `Coverage analysis failed`

**Solutions:**

-   Install Xdebug or PCOV
-   Check PHPUnit configuration
-   Run `composer test-coverage`

### 4. Memory Issues

**Error:** `Fatal error: Allowed memory size exhausted`

**Solutions:**

-   Increase PHP memory limit
-   Use `--memory-analysis` option
-   Check for memory leaks in tests

### 5. Slow Test Generation

**Issue:** Test generation is very slow

**Solutions:**

-   Check AI API response times
-   Use local generation instead of AI
-   Optimize your code structure

## Debug Mode

Enable debug mode for detailed logging:

```bash
php artisan test:generate --verbose
```

Check logs in `storage/logs/laravel.log`:

```bash
tail -f storage/logs/laravel.log
```

## Performance Issues

### Slow Tests

Identify slow tests:

```bash
php artisan test:benchmark --slow-threshold=1000
```

### Memory Usage

Check memory usage:

```bash
php artisan test:benchmark --memory-analysis
```

## Getting Help

-   [GitHub Issues](https://github.com/gessyken/laravel-test-accelerator/issues)
-   [GitHub Discussions](https://github.com/gessyken/laravel-test-accelerator/discussions)
-   Email: gessyken@gmail.com

````

## 🎨 Website Design Suggestions

### Color Scheme

```css
:root {
    --primary-color: #3b82f6;
    --secondary-color: #1e40af;
    --accent-color: #f59e0b;
    --success-color: #10b981;
    --warning-color: #f59e0b;
    --error-color: #ef4444;
    --text-color: #1f2937;
    --text-light: #6b7280;
    --bg-color: #ffffff;
    --bg-light: #f9fafb;
    --border-color: #e5e7eb;
}
````

### Typography

```css
body {
    font-family: "Inter", -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
    line-height: 1.6;
    color: var(--text-color);
}

h1,
h2,
h3,
h4,
h5,
h6 {
    font-weight: 600;
    line-height: 1.25;
}

code {
    font-family: "Fira Code", "Monaco", "Consolas", monospace;
    background: var(--bg-light);
    padding: 0.25rem 0.5rem;
    border-radius: 0.25rem;
}
```

### Components

#### Code Block Component

```html
<div class="code-block">
    <div class="code-header">
        <span class="language">php</span>
        <button class="copy-btn">Copy</button>
    </div>
    <pre><code class="language-php">// Your code here</code></pre>
</div>
```

#### Feature Card Component

```html
<div class="feature-card">
    <div class="feature-icon">🚀</div>
    <h3>Feature Title</h3>
    <p>Feature description goes here...</p>
    <a href="/docs/feature" class="feature-link">Learn More →</a>
</div>
```

## 📱 Mobile Responsiveness

### Breakpoints

```css
/* Mobile First */
@media (min-width: 640px) {
    /* sm */
}
@media (min-width: 768px) {
    /* md */
}
@media (min-width: 1024px) {
    /* lg */
}
@media (min-width: 1280px) {
    /* xl */
}
```

### Navigation

```html
<nav class="mobile-nav">
    <button class="menu-toggle">☰</button>
    <div class="menu-items">
        <a href="/docs">Documentation</a>
        <a href="/examples">Examples</a>
        <a href="/api">API Reference</a>
    </div>
</nav>
```

## 🔍 SEO Optimization

### Meta Tags

```html
<meta
    name="description"
    content="Laravel Test Accelerator - Generate tests, analyze coverage, and optimize performance for your Laravel applications"
/>
<meta
    name="keywords"
    content="laravel, testing, php, test generation, code coverage, performance"
/>
<meta property="og:title" content="Laravel Test Accelerator" />
<meta
    property="og:description"
    content="Accelerate your Laravel development with intelligent test generation"
/>
<meta property="og:url" content="https://accelerator.kencode.dev" />
<meta property="og:type" content="website" />
```

### Structured Data

```json
{
    "@context": "https://schema.org",
    "@type": "SoftwareApplication",
    "name": "Laravel Test Accelerator",
    "description": "A powerful Laravel package for test generation and analysis",
    "url": "https://accelerator.kencode.dev",
    "applicationCategory": "DeveloperApplication",
    "operatingSystem": "PHP",
    "offers": {
        "@type": "Offer",
        "price": "0",
        "priceCurrency": "USD"
    }
}
```

---

This documentation structure provides a comprehensive foundation for your website at https://accelerator.kencode.dev. Each section can be developed into full pages with interactive examples, code highlighting, and responsive design.
