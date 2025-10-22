<?php

use KENCODE\LaravelTestAccelerator\Services\TestGenerator;

it('correctly replaces placeholders in templates', function () {
    $generator = new TestGenerator;

    $reflection = new ReflectionClass($generator);
    $method = $reflection->getMethod('replacePlaceholders');
    $method->setAccessible(true);

    $template = '<?php

namespace {{NAMESPACE}};

use {{MODEL_NAMESPACE}}\{{CLASS}};

class {{TEST_CLASS}} extends TestCase
{
    public function test_example()
    {
        $model = {{CLASS}}::factory()->create();
    }
}';

    $replacements = [
        'NAMESPACE' => 'Tests\Unit',
        'MODEL_NAMESPACE' => 'App\Models',
        'CLASS' => 'User',
        'TEST_CLASS' => 'UserTest',
    ];

    $result = $method->invoke($generator, $template, $replacements);

    // Vérifier qu'il n'y a plus de placeholders
    expect($result)->not->toContain('{{');
    expect($result)->not->toContain('}}');

    // Vérifier que les remplacements ont été faits
    expect($result)->toContain('namespace Tests\Unit;');
    expect($result)->toContain('use App\Models\User;');
    expect($result)->toContain('class UserTest extends TestCase');
    expect($result)->toContain('$model = User::factory()->create();');
});
