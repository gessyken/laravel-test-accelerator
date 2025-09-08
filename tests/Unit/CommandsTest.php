<?php

use KENCODE\LaravelTestAccelerator\Commands\CoverageAnalysisCommand;
use KENCODE\LaravelTestAccelerator\Commands\GenerateTestsCommand;
use KENCODE\LaravelTestAccelerator\Commands\PerformanceBenchmarkCommand;

it('can instantiate GenerateTestsCommand', function () {
    $command = new GenerateTestsCommand;
    expect($command)->toBeInstanceOf(GenerateTestsCommand::class);
    expect($command->getName())->toBe('test:generate');
});

it('can instantiate CoverageAnalysisCommand', function () {
    $command = new CoverageAnalysisCommand;
    expect($command)->toBeInstanceOf(CoverageAnalysisCommand::class);
    expect($command->getName())->toBe('test:coverage');
});

it('can instantiate PerformanceBenchmarkCommand', function () {
    $command = new PerformanceBenchmarkCommand;
    expect($command)->toBeInstanceOf(PerformanceBenchmarkCommand::class);
    expect($command->getName())->toBe('test:benchmark');
});

it('has correct command signatures', function () {
    $generateCommand = new GenerateTestsCommand;
    $coverageCommand = new CoverageAnalysisCommand;
    $benchmarkCommand = new PerformanceBenchmarkCommand;

    // Use reflection to access protected signature property
    $generateReflection = new ReflectionClass($generateCommand);
    $coverageReflection = new ReflectionClass($coverageCommand);
    $benchmarkReflection = new ReflectionClass($benchmarkCommand);

    $generateSignature = $generateReflection->getProperty('signature');
    $coverageSignature = $coverageReflection->getProperty('signature');
    $benchmarkSignature = $benchmarkReflection->getProperty('signature');

    $generateSignature->setAccessible(true);
    $coverageSignature->setAccessible(true);
    $benchmarkSignature->setAccessible(true);

    expect($generateSignature->getValue($generateCommand))->toContain('test:generate');
    expect($coverageSignature->getValue($coverageCommand))->toContain('test:coverage');
    expect($benchmarkSignature->getValue($benchmarkCommand))->toContain('test:benchmark');
});
