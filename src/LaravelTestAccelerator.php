<?php

namespace KENCODE\LaravelTestAccelerator;

use KENCODE\LaravelTestAccelerator\Services\TestGenerator;
use KENCODE\LaravelTestAccelerator\Services\CoverageAnalyzer;
use KENCODE\LaravelTestAccelerator\Services\PerformanceAnalyzer;
use KENCODE\LaravelTestAccelerator\Services\AITestGenerator;

class LaravelTestAccelerator
{
    protected TestGenerator $testGenerator;
    protected CoverageAnalyzer $coverageAnalyzer;
    protected PerformanceAnalyzer $performanceAnalyzer;
    protected AITestGenerator $aiTestGenerator;

    public function __construct(
        TestGenerator $testGenerator,
        CoverageAnalyzer $coverageAnalyzer,
        PerformanceAnalyzer $performanceAnalyzer,
        AITestGenerator $aiTestGenerator
    ) {
        $this->testGenerator = $testGenerator;
        $this->coverageAnalyzer = $coverageAnalyzer;
        $this->performanceAnalyzer = $performanceAnalyzer;
        $this->aiTestGenerator = $aiTestGenerator;
    }

    /**
     * Generate tests for a file or directory
     */
    public function generateTests(string $path, array $options = []): bool
    {
        return $this->testGenerator->generateBasicTests($path, $options);
    }

    /**
     * Generate tests for a model
     */
    public function generateModelTests(string $modelPath, array $options = []): bool
    {
        return $this->testGenerator->generateForModel($modelPath, $options);
    }

    /**
     * Generate tests for a controller
     */
    public function generateControllerTests(string $controllerPath, array $options = []): bool
    {
        return $this->testGenerator->generateForController($controllerPath, $options);
    }

    /**
     * Generate tests for a service
     */
    public function generateServiceTests(string $servicePath, array $options = []): bool
    {
        return $this->testGenerator->generateForService($servicePath, $options);
    }

    /**
     * Generate tests using AI
     */
    public function generateWithAI(string $path, string $prompt = '', array $options = []): bool
    {
        return $this->aiTestGenerator->generate($path, $prompt, $options);
    }

    /**
     * Analyze code coverage
     */
    public function analyzeCoverage(array $options = []): array
    {
        return $this->coverageAnalyzer->analyze($options);
    }

    /**
     * Generate HTML coverage report
     */
    public function generateCoverageReport(?string $outputPath = null): bool
    {
        return $this->coverageAnalyzer->generateHtmlReport($outputPath);
    }

    /**
     * Get performance statistics
     */
    public function getPerformanceStats(): array
    {
        return $this->performanceAnalyzer->analyze();
    }

    /**
     * Get slow tests
     */
    public function getSlowTests(): array
    {
        return $this->performanceAnalyzer->getSlowTests();
    }

    /**
     * Get memory usage statistics
     */
    public function getMemoryUsage(): array
    {
        return $this->performanceAnalyzer->getMemoryUsage();
    }

    /**
     * Generate performance report
     */
    public function generatePerformanceReport(): array
    {
        return $this->performanceAnalyzer->generateReport();
    }

    /**
     * Get coverage statistics
     */
    public function getCoverageStats(): array
    {
        return $this->coverageAnalyzer->getCoverageStats();
    }

    /**
     * Test AI connection
     */
    public function testAIConnection(): bool
    {
        return $this->aiTestGenerator->testConnection();
    }

    /**
     * Validate AI configuration
     */
    public function validateAIConfiguration(): array
    {
        return $this->aiTestGenerator->validateConfiguration();
    }
}
