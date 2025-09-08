<?php

namespace KENCODE\LaravelTestAccelerator\Services;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use ReflectionClass;
use ReflectionMethod;
use ReflectionProperty;

class TestGenerator
{
    protected array $config;
    protected array $templates;

    public function __construct()
    {
        $this->config = config('laravel-test-accelerator', []);
        $this->templates = $this->config['templates'] ?? [];
    }

    /**
     * Generate basic tests for a file or directory
     */
    public function generateBasicTests(string $path, array $options = []): bool
    {
        if (is_dir($path)) {
            return $this->generateForDirectory($path, $options);
        }

        if (is_file($path)) {
            return $this->generateForFile($path, $options);
        }

        return false;
    }

    /**
     * Generate tests for an Eloquent model
     */
    public function generateForModel(string $modelPath, array $options = []): bool
    {
        if (!file_exists($modelPath)) {
            return false;
        }

        $className = $this->getClassNameFromFile($modelPath);
        if (!$className) {
            return false;
        }

        $testClassName = $className . 'Test';
        $testPath = $this->getTestPath($className, 'Unit');
        
        if (File::exists($testPath) && !($options['force'] ?? false)) {
            return false;
        }

        $template = $this->getTemplate('model_test');
        $content = $this->replacePlaceholders($template, [
            'CLASS' => $className,
            'TEST_CLASS' => $testClassName,
            'NAMESPACE' => $this->getTestNamespace('Unit'),
            'MODEL_NAMESPACE' => $this->getClassNamespace($modelPath),
            'METHODS' => $this->generateModelTestMethods($modelPath),
        ]);

        File::ensureDirectoryExists(dirname($testPath));
        File::put($testPath, $content);

        return true;
    }

    /**
     * Generate tests for a controller
     */
    public function generateForController(string $controllerPath, array $options = []): bool
    {
        if (!file_exists($controllerPath)) {
            return false;
        }

        $className = $this->getClassNameFromFile($controllerPath);
        if (!$className) {
            return false;
        }

        $testClassName = $className . 'Test';
        $testPath = $this->getTestPath($className, 'Feature');
        
        if (File::exists($testPath) && !($options['force'] ?? false)) {
            return false;
        }

        $template = $this->getTemplate('controller_test');
        $content = $this->replacePlaceholders($template, [
            'CLASS' => $className,
            'TEST_CLASS' => $testClassName,
            'NAMESPACE' => $this->getTestNamespace('Feature'),
            'CONTROLLER_NAMESPACE' => $this->getClassNamespace($controllerPath),
            'METHODS' => $this->generateControllerTestMethods($controllerPath),
        ]);

        File::ensureDirectoryExists(dirname($testPath));
        File::put($testPath, $content);

        return true;
    }

    /**
     * Generate tests for a service class
     */
    public function generateForService(string $servicePath, array $options = []): bool
    {
        if (!file_exists($servicePath)) {
            return false;
        }

        $className = $this->getClassNameFromFile($servicePath);
        if (!$className) {
            return false;
        }

        $testClassName = $className . 'Test';
        $testPath = $this->getTestPath($className, 'Unit');
        
        if (File::exists($testPath) && !($options['force'] ?? false)) {
            return false;
        }

        $template = $this->getTemplate('unit_test');
        $content = $this->replacePlaceholders($template, [
            'CLASS' => $className,
            'TEST_CLASS' => $testClassName,
            'NAMESPACE' => $this->getTestNamespace('Unit'),
            'SERVICE_NAMESPACE' => $this->getClassNamespace($servicePath),
            'METHODS' => $this->generateServiceTestMethods($servicePath),
        ]);

        File::ensureDirectoryExists(dirname($testPath));
        File::put($testPath, $content);

        return true;
    }

    /**
     * Generate tests using AI
     */
    public function generateWithAI(string $path, string $prompt = '', array $options = []): bool
    {
        $aiGenerator = app(AITestGenerator::class);
        return $aiGenerator->generate($path, $prompt, $options);
    }

    /**
     * Generate tests for a directory
     */
    protected function generateForDirectory(string $directory, array $options = []): bool
    {
        $files = File::allFiles($directory);
        $success = true;

        foreach ($files as $file) {
            if ($this->isPhpFile($file)) {
                $result = $this->generateForFile($file->getPathname(), $options);
                if (!$result) {
                    $success = false;
                }
            }
        }

        return $success;
    }

    /**
     * Generate tests for a single file
     */
    protected function generateForFile(string $filePath, array $options = []): bool
    {
        $className = $this->getClassNameFromFile($filePath);
        if (!$className) {
            return false;
        }

        // Determine the type of class and generate appropriate tests
        if ($this->isModel($filePath)) {
            return $this->generateForModel($filePath, $options);
        }

        if ($this->isController($filePath)) {
            return $this->generateForController($filePath, $options);
        }

        if ($this->isService($filePath)) {
            return $this->generateForService($filePath, $options);
        }

        // Default to unit test
        return $this->generateUnitTest($filePath, $options);
    }

    /**
     * Generate a basic unit test
     */
    protected function generateUnitTest(string $filePath, array $options = []): bool
    {
        $className = $this->getClassNameFromFile($filePath);
        if (!$className) {
            return false;
        }

        $testClassName = $className . 'Test';
        $testPath = $this->getTestPath($className, 'Unit');
        
        if (File::exists($testPath) && !($options['force'] ?? false)) {
            return false;
        }

        $template = $this->getTemplate('unit_test');
        $content = $this->replacePlaceholders($template, [
            'CLASS' => $className,
            'TEST_CLASS' => $testClassName,
            'NAMESPACE' => $this->getTestNamespace('Unit'),
            'SERVICE_NAMESPACE' => $this->getClassNamespace($filePath),
            'METHODS' => $this->generateBasicTestMethods($filePath),
        ]);

        File::ensureDirectoryExists(dirname($testPath));
        File::put($testPath, $content);

        return true;
    }

    /**
     * Generate model-specific test methods
     */
    protected function generateModelTestMethods(string $filePath): string
    {
        $methods = $this->getPublicMethods($filePath);
        $testMethods = [];

        // Basic model tests
        $testMethods[] = $this->generateTestMethod('it_can_create_a_model', [
            '$model = ' . $this->getClassNameFromFile($filePath) . '::factory()->create();',
            '$this->assertNotNull($model);'
        ]);

        $testMethods[] = $this->generateTestMethod('it_can_update_a_model', [
            '$model = ' . $this->getClassNameFromFile($filePath) . '::factory()->create();',
            '$originalValue = $model->name;',
            '$model->name = \'Updated Name\';',
            '$model->save();',
            '',
            '$this->assertNotEquals($originalValue, $model->fresh()->name);'
        ]);

        $testMethods[] = $this->generateTestMethod('it_can_delete_a_model', [
            '$model = ' . $this->getClassNameFromFile($filePath) . '::factory()->create();',
            '$modelId = $model->id;',
            '',
            '$model->delete();',
            '',
            '$this->assertNull(' . $this->getClassNameFromFile($filePath) . '::find($modelId));'
        ]);

        // Generate tests for relationships
        foreach ($methods as $method) {
            if (Str::startsWith($method, 'has') || Str::startsWith($method, 'belongsTo')) {
                $testMethods[] = $this->generateRelationshipTestMethod($method, $filePath);
            }
        }

        // Generate tests for accessors
        foreach ($methods as $method) {
            if (Str::startsWith($method, 'get') && Str::endsWith($method, 'Attribute')) {
                $testMethods[] = $this->generateAccessorTestMethod($method, $filePath);
            }
        }

        return implode("\n\n", $testMethods);
    }

    /**
     * Generate controller-specific test methods
     */
    protected function generateControllerTestMethods(string $filePath): string
    {
        $methods = $this->getPublicMethods($filePath);
        $testMethods = [];

        foreach ($methods as $method) {
            if ($method === '__construct') {
                continue;
            }

            $testMethods[] = $this->generateControllerTestMethod($method, $filePath);
        }

        return implode("\n\n", $testMethods);
    }

    /**
     * Generate service-specific test methods
     */
    protected function generateServiceTestMethods(string $filePath): string
    {
        $methods = $this->getPublicMethods($filePath);
        $testMethods = [];

        foreach ($methods as $method) {
            if ($method === '__construct') {
                continue;
            }

            $testMethods[] = $this->generateServiceTestMethod($method, $filePath);
        }

        return implode("\n\n", $testMethods);
    }

    /**
     * Generate basic test methods
     */
    protected function generateBasicTestMethods(string $filePath): string
    {
        $methods = $this->getPublicMethods($filePath);
        $testMethods = [];

        $testMethods[] = $this->generateTestMethod('it_can_be_instantiated', [
            '$instance = new ' . $this->getClassNameFromFile($filePath) . '();',
            '$this->assertNotNull($instance);'
        ]);

        foreach ($methods as $method) {
            if ($method === '__construct') {
                continue;
            }

            $testMethods[] = $this->generateTestMethod('it_can_call_' . Str::snake($method), [
                '$instance = new ' . $this->getClassNameFromFile($filePath) . '();',
                '// Add test logic for ' . $method . ' method',
                '$this->assertTrue(true);'
            ]);
        }

        return implode("\n\n", $testMethods);
    }

    /**
     * Generate a single test method
     */
    protected function generateTestMethod(string $methodName, array $lines): string
    {
        $indent = '    ';
        $content = $indent . '/** @test */' . "\n";
        $content .= $indent . 'public function ' . $methodName . '()' . "\n";
        $content .= $indent . '{' . "\n";
        
        foreach ($lines as $line) {
            $content .= $indent . $indent . $line . "\n";
        }
        
        $content .= $indent . '}';
        
        return $content;
    }

    /**
     * Generate relationship test method
     */
    protected function generateRelationshipTestMethod(string $methodName, string $filePath): string
    {
        $className = $this->getClassNameFromFile($filePath);
        $relatedModel = Str::studly(str_replace(['has', 'belongsTo'], '', $methodName));
        
        return $this->generateTestMethod('it_' . Str::snake($methodName), [
            '$model = ' . $className . '::factory()->create();',
            '$related = ' . $relatedModel . '::factory()->create([\'user_id\' => $model->id]);',
            '',
            '$this->assertTrue($model->' . $methodName . '->contains($related));'
        ]);
    }

    /**
     * Generate accessor test method
     */
    protected function generateAccessorTestMethod(string $methodName, string $filePath): string
    {
        $className = $this->getClassNameFromFile($filePath);
        $attributeName = Str::snake(Str::replaceLast('Attribute', '', Str::replaceFirst('get', '', $methodName)));
        
        return $this->generateTestMethod('it_can_get_' . $attributeName . '_attribute', [
            '$model = ' . $className . '::factory()->create([',
            '    \'first_name\' => \'John\',',
            '    \'last_name\' => \'Doe\'',
            ']);',
            '',
            '$this->assertEquals(\'John Doe\', $model->' . $attributeName . ');'
        ]);
    }

    /**
     * Generate controller test method
     */
    protected function generateControllerTestMethod(string $methodName, string $filePath): string
    {
        $className = $this->getClassNameFromFile($filePath);
        $routeName = Str::kebab($methodName);
        
        return $this->generateTestMethod('it_can_' . Str::snake($methodName), [
            '$response = $this->getJson(\'/' . $routeName . '\');',
            '',
            '$response->assertStatus(200);'
        ]);
    }

    /**
     * Generate service test method
     */
    protected function generateServiceTestMethod(string $methodName, string $filePath): string
    {
        $className = $this->getClassNameFromFile($filePath);
        
        return $this->generateTestMethod('it_can_' . Str::snake($methodName), [
            '$service = new ' . $className . '();',
            '// Add test logic for ' . $methodName . ' method',
            '$this->assertTrue(true);'
        ]);
    }

    /**
     * Get class name from file
     */
    protected function getClassNameFromFile(string $filePath): ?string
    {
        $content = File::get($filePath);
        
        if (preg_match('/class\s+(\w+)/', $content, $matches)) {
            return $matches[1];
        }
        
        return null;
    }

    /**
     * Get class namespace from file
     */
    protected function getClassNamespace(string $filePath): string
    {
        $content = File::get($filePath);
        
        if (preg_match('/namespace\s+([^;]+);/', $content, $matches)) {
            return trim($matches[1]);
        }
        
        return '';
    }

    /**
     * Get public methods from file
     */
    protected function getPublicMethods(string $filePath): array
    {
        $content = File::get($filePath);
        $methods = [];
        
        if (preg_match_all('/public\s+function\s+(\w+)/', $content, $matches)) {
            $methods = $matches[1];
        }
        
        return $methods;
    }

    /**
     * Check if file is a model
     */
    protected function isModel(string $filePath): bool
    {
        $content = File::get($filePath);
        return str_contains($content, 'extends Model') || str_contains($content, 'extends Authenticatable');
    }

    /**
     * Check if file is a controller
     */
    protected function isController(string $filePath): bool
    {
        $content = File::get($filePath);
        return str_contains($content, 'extends Controller') || str_contains($content, 'Controller');
    }

    /**
     * Check if file is a service
     */
    protected function isService(string $filePath): bool
    {
        $content = File::get($filePath);
        return str_contains($content, 'Service') && !str_contains($content, 'Test');
    }

    /**
     * Check if file is PHP
     */
    protected function isPhpFile($file): bool
    {
        return pathinfo($file, PATHINFO_EXTENSION) === 'php';
    }

    /**
     * Get test path
     */
    protected function getTestPath(string $className, string $type): string
    {
        $testPaths = $this->config['test_paths'] ?? ['tests/Unit', 'tests/Feature'];
        $basePath = $testPaths[0]; // Default to first path
        
        if ($type === 'Feature') {
            $basePath = $testPaths[1] ?? $testPaths[0];
        }
        
        return base_path($basePath . '/' . $className . 'Test.php');
    }

    /**
     * Get test namespace
     */
    protected function getTestNamespace(string $type): string
    {
        return "Tests\\{$type}";
    }

    /**
     * Get template content
     */
    protected function getTemplate(string $templateName): string
    {
        $templatePath = $this->templates[$templateName] ?? "laravel-test-accelerator::templates.{$templateName}";
        
        if (view()->exists($templatePath)) {
            return view($templatePath)->render();
        }
        
        // Fallback to default templates
        return $this->getDefaultTemplate($templateName);
    }

    /**
     * Get default template
     */
    protected function getDefaultTemplate(string $templateName): string
    {
        $templates = [
            'unit_test' => $this->getUnitTestTemplate(),
            'model_test' => $this->getModelTestTemplate(),
            'controller_test' => $this->getControllerTestTemplate(),
        ];
        
        return $templates[$templateName] ?? $templates['unit_test'];
    }

    /**
     * Get unit test template
     */
    protected function getUnitTestTemplate(): string
    {
        return '<?php

namespace {{NAMESPACE}};

use Tests\TestCase;
use {{SERVICE_NAMESPACE}}\{{CLASS}};

class {{TEST_CLASS}} extends TestCase
{
    /** @test */
    public function it_can_be_instantiated()
    {
        $instance = new {{CLASS}}();
        $this->assertNotNull($instance);
    }

    {{METHODS}}
}';
    }

    /**
     * Get model test template
     */
    protected function getModelTestTemplate(): string
    {
        return '<?php

namespace {{NAMESPACE}};

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use {{MODEL_NAMESPACE}}\{{CLASS}};

class {{TEST_CLASS}} extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_create_a_model()
    {
        $model = {{CLASS}}::factory()->create();
        $this->assertNotNull($model);
    }

    {{METHODS}}
}';
    }

    /**
     * Get controller test template
     */
    protected function getControllerTestTemplate(): string
    {
        return '<?php

namespace {{NAMESPACE}};

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use {{CONTROLLER_NAMESPACE}}\{{CLASS}};

class {{TEST_CLASS}} extends TestCase
{
    use RefreshDatabase;

    {{METHODS}}
}';
    }

    /**
     * Replace placeholders in template
     */
    protected function replacePlaceholders(string $content, array $replacements): string
    {
        foreach ($replacements as $key => $value) {
            $content = str_replace("{{$key}}", $value, $content);
        }
        
        return $content;
    }
}
