<?php

namespace KENCODE\LaravelTestAccelerator\Tests;

use Illuminate\Database\Eloquent\Factories\Factory;
use KENCODE\LaravelTestAccelerator\LaravelTestAcceleratorServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();

        Factory::guessFactoryNamesUsing(
            fn (string $modelName) => 'KENCODE\\LaravelTestAccelerator\\Database\\Factories\\'.class_basename($modelName).'Factory'
        );
    }

    protected function getPackageProviders($app)
    {
        return [
            LaravelTestAcceleratorServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app)
    {
        config()->set('database.default', 'testing');
        config()->set('laravel-test-accelerator.ai_api_key', 'test-key');
        config()->set('laravel-test-accelerator.ai_provider', 'openai');

        /*
         foreach (\Illuminate\Support\Facades\File::allFiles(__DIR__ . '/database/migrations') as $migration) {
            (include $migration->getRealPath())->up();
         }
         */
    }
}
