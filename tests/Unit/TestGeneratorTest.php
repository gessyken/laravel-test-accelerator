<?php

use Illuminate\Support\Facades\File;
use KENCODE\LaravelTestAccelerator\Services\TestGenerator;

it('can generate basic tests for a simple class', function () {
    // Create a test class
    $testClassContent = "<?php\n\nclass TestClass {\n    public function testMethod() {\n        return 'test';\n    }\n}";
    File::put(app_path('TestClass.php'), $testClassContent);

    $generator = new TestGenerator;
    $result = $generator->generateBasicTests(app_path('TestClass.php'));

    expect($result)->toBeTrue();
    expect(File::exists(base_path('tests/Unit/TestClassTest.php')))->toBeTrue();

    // Clean up
    File::delete(app_path('TestClass.php'));
    File::delete(base_path('tests/Unit/TestClassTest.php'));
});

it('can identify model classes', function () {
    $generator = new TestGenerator;

    // Create a model class
    $modelContent = "<?php\n\nuse Illuminate\Database\Eloquent\Model;\n\nclass User extends Model {\n    protected \$fillable = ['name', 'email'];\n}";
    File::put(app_path('User.php'), $modelContent);

    $reflection = new ReflectionClass($generator);
    $method = $reflection->getMethod('isModel');
    $method->setAccessible(true);

    $isModel = $method->invoke($generator, app_path('User.php'));

    expect($isModel)->toBeTrue();

    // Clean up
    File::delete(app_path('User.php'));
});

it('can identify controller classes', function () {
    $generator = new TestGenerator;

    // Create a controller class
    $controllerContent = "<?php\n\nuse Illuminate\Http\Request;\n\nclass UserController extends Controller {\n    public function index() {\n        return view('users.index');\n    }\n}";
    File::put(app_path('UserController.php'), $controllerContent);

    $reflection = new ReflectionClass($generator);
    $method = $reflection->getMethod('isController');
    $method->setAccessible(true);

    $isController = $method->invoke($generator, app_path('UserController.php'));

    expect($isController)->toBeTrue();

    // Clean up
    File::delete(app_path('UserController.php'));
});

it('can extract class name from file', function () {
    $generator = new TestGenerator;

    $testContent = "<?php\n\nnamespace App\\Models;\n\nclass User {\n    // class content\n}";
    File::put(app_path('User.php'), $testContent);

    $reflection = new ReflectionClass($generator);
    $method = $reflection->getMethod('getClassNameFromFile');
    $method->setAccessible(true);

    $className = $method->invoke($generator, app_path('User.php'));

    expect($className)->toBe('User');

    // Clean up
    File::delete(app_path('User.php'));
});
