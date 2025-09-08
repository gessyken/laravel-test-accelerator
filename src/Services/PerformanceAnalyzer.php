<?php

namespace KENCODE\LaravelTestAccelerator\Services;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Process;

class PerformanceAnalyzer
{
    protected array $config;

    public function __construct()
    {
        $this->config = config('laravel-test-accelerator.benchmark', []);
    }

    /**
     * Analyze test performance
     */
    public function analyze(array $options = []): array
    {
        $slowThreshold = $options['slow_threshold'] ?? $this->config['slow_threshold'] ?? 1000;
        $memoryThreshold = $options['memory_threshold'] ?? $this->config['memory_threshold'] ?? 1024;
        
        $testResults = $this->runPerformanceTests();
        
        $slowTests = $this->identifySlowTests($testResults, $slowThreshold);
        $memoryIssues = $this->identifyMemoryIssues($testResults, $memoryThreshold);
        
        return [
            'total_tests' => count($testResults),
            'slow_tests' => $slowTests,
            'memory_issues' => $memoryIssues,
            'average_time' => $this->calculateAverageTime($testResults),
            'total_time' => $this->calculateTotalTime($testResults),
            'recommendations' => $this->generateRecommendations($slowTests, $memoryIssues),
        ];
    }

    /**
     * Get list of slow tests
     */
    public function getSlowTests(): array
    {
        $slowThreshold = $this->config['slow_threshold'] ?? 1000;
        $testResults = $this->runPerformanceTests();
        
        return $this->identifySlowTests($testResults, $slowThreshold);
    }

    /**
     * Get memory usage statistics
     */
    public function getMemoryUsage(): array
    {
        $memoryThreshold = $this->config['memory_threshold'] ?? 1024;
        $testResults = $this->runPerformanceTests();
        
        return $this->identifyMemoryIssues($testResults, $memoryThreshold);
    }

    /**
     * Generate performance report
     */
    public function generateReport(): array
    {
        $analysis = $this->analyze();
        
        return [
            'summary' => [
                'total_tests' => $analysis['total_tests'],
                'slow_tests_count' => count($analysis['slow_tests']),
                'memory_issues_count' => count($analysis['memory_issues']),
                'average_time' => $analysis['average_time'],
                'total_time' => $analysis['total_time'],
            ],
            'slow_tests' => $analysis['slow_tests'],
            'memory_issues' => $analysis['memory_issues'],
            'recommendations' => $analysis['recommendations'],
            'generated_at' => now()->toISOString(),
        ];
    }

    /**
     * Run performance tests
     */
    protected function runPerformanceTests(): array
    {
        $command = 'vendor/bin/pest --verbose --coverage-text';
        
        $result = Process::run($command);
        
        if (!$result->successful()) {
            return [];
        }

        return $this->parseTestOutput($result->output());
    }

    /**
     * Parse test output for performance data
     */
    protected function parseTestOutput(string $output): array
    {
        $lines = explode("\n", $output);
        $tests = [];
        
        foreach ($lines as $line) {
            if (str_contains($line, 'PASS') || str_contains($line, 'FAIL')) {
                $testData = $this->extractTestData($line);
                if ($testData) {
                    $tests[] = $testData;
                }
            }
        }
        
        return $tests;
    }

    /**
     * Extract test data from line
     */
    protected function extractTestData(string $line): ?array
    {
        // This is a simplified parser - in a real implementation,
        // you'd want to use a more robust test result parser
        if (preg_match('/(PASS|FAIL)\s+(.+?)\s+\((\d+(?:\.\d+)?)ms\)/', $line, $matches)) {
            return [
                'name' => trim($matches[2]),
                'status' => strtolower($matches[1]),
                'time' => (float) $matches[3],
                'memory' => $this->extractMemoryUsage($line),
            ];
        }
        
        return null;
    }

    /**
     * Extract memory usage from line
     */
    protected function extractMemoryUsage(string $line): float
    {
        if (preg_match('/(\d+(?:\.\d+)?)MB/', $line, $matches)) {
            return (float) $matches[1] * 1024; // Convert to KB
        }
        
        return 0;
    }

    /**
     * Identify slow tests
     */
    protected function identifySlowTests(array $testResults, float $threshold): array
    {
        return array_filter($testResults, function ($test) use ($threshold) {
            return $test['time'] > $threshold;
        });
    }

    /**
     * Identify memory issues
     */
    protected function identifyMemoryIssues(array $testResults, float $threshold): array
    {
        return array_filter($testResults, function ($test) use ($threshold) {
            return $test['memory'] > $threshold;
        });
    }

    /**
     * Calculate average test time
     */
    protected function calculateAverageTime(array $testResults): float
    {
        if (empty($testResults)) {
            return 0;
        }
        
        $totalTime = array_sum(array_column($testResults, 'time'));
        return $totalTime / count($testResults);
    }

    /**
     * Calculate total test time
     */
    protected function calculateTotalTime(array $testResults): float
    {
        return array_sum(array_column($testResults, 'time'));
    }

    /**
     * Generate performance recommendations
     */
    protected function generateRecommendations(array $slowTests, array $memoryIssues): array
    {
        $recommendations = [];
        
        if (!empty($slowTests)) {
            $recommendations[] = [
                'type' => 'performance',
                'message' => 'Consider optimizing ' . count($slowTests) . ' slow tests',
                'tests' => array_column($slowTests, 'name'),
            ];
        }
        
        if (!empty($memoryIssues)) {
            $recommendations[] = [
                'type' => 'memory',
                'message' => 'Consider optimizing ' . count($memoryIssues) . ' memory-intensive tests',
                'tests' => array_column($memoryIssues, 'name'),
            ];
        }
        
        if (empty($slowTests) && empty($memoryIssues)) {
            $recommendations[] = [
                'type' => 'success',
                'message' => 'All tests are performing well!',
                'tests' => [],
            ];
        }
        
        return $recommendations;
    }
}
