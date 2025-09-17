<?php

namespace KENCODE\LaravelTestAccelerator;

use KENCODE\LaravelTestAccelerator\Commands\CoverageAnalysisCommand;
use KENCODE\LaravelTestAccelerator\Commands\GenerateTestsCommand;
use KENCODE\LaravelTestAccelerator\Commands\LaravelTestAcceleratorCommand;
use KENCODE\LaravelTestAccelerator\Commands\PerformanceBenchmarkCommand;
use KENCODE\LaravelTestAccelerator\Services\AITestGenerator;
use KENCODE\LaravelTestAccelerator\Services\CoverageAnalyzer;
use KENCODE\LaravelTestAccelerator\Services\PerformanceAnalyzer;
use KENCODE\LaravelTestAccelerator\Services\TestGenerator;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class LaravelTestAcceleratorServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('laravel-test-accelerator')
            ->hasConfigFile()
            ->hasViews()
            ->hasCommands([
                LaravelTestAcceleratorCommand::class,
                GenerateTestsCommand::class,
                CoverageAnalysisCommand::class,
                PerformanceBenchmarkCommand::class,
            ]);
    }

    public function packageRegistered()
    {
        // Register services
        $this->app->singleton(TestGenerator::class);
        $this->app->singleton(CoverageAnalyzer::class);
        $this->app->singleton(PerformanceAnalyzer::class);
        $this->app->singleton(AITestGenerator::class);

        // Register main service
        $this->app->singleton('laravel-test-accelerator', function ($app) {
            return new LaravelTestAccelerator(
                $app->make(TestGenerator::class),
                $app->make(CoverageAnalyzer::class),
                $app->make(PerformanceAnalyzer::class),
                $app->make(AITestGenerator::class)
            );
        });
    }
}
