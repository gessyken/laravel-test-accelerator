<?php

namespace KENCODE\LaravelTestAccelerator;

use KENCODE\LaravelTestAccelerator\Commands\LaravelTestAcceleratorCommand;
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
            ->hasMigration('create_migration_table_name_table')
            ->hasCommand(LaravelTestAcceleratorCommand::class);
    }
}
