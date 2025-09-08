<?php

namespace KENCODE\LaravelTestAccelerator\Services;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Process;

class CoverageAnalyzer
{
    protected array $config;

    public function __construct()
    {
        $this->config = config('laravel-test-accelerator.coverage', []);
    }

    /**
     * Analyze code coverage
     */
    public function analyze(array $options = []): array
    {
        $threshold = $options['threshold'] ?? $this->config['threshold'] ?? 80;
        $coverageData = $this->runCoverageAnalysis();
        
        $results = [
            'total' => $coverageData['total'] ?? 0,
            'files' => $coverageData['files'] ?? [],
            'threshold' => $threshold,
            'passed' => ($coverageData['total'] ?? 0) >= $threshold,
        ];

        return $results;
    }

    /**
     * Generate HTML coverage report
     */
    public function generateHtmlReport(?string $outputPath = null): bool
    {
        $outputPath = $outputPath ?? storage_path('app/laravel-test-accelerator/coverage');
        
        File::ensureDirectoryExists($outputPath);

        $command = $this->buildCoverageCommand([
            '--coverage-html' => $outputPath,
            '--coverage-text' => '',
        ]);

        $result = Process::run($command);
        
        return $result->successful();
    }

    /**
     * Generate XML coverage report
     */
    public function generateXmlReport(?string $outputPath = null): bool
    {
        $outputPath = $outputPath ?? storage_path('app/laravel-test-accelerator/coverage.xml');
        
        File::ensureDirectoryExists(dirname($outputPath));

        $command = $this->buildCoverageCommand([
            '--coverage-xml' => $outputPath,
            '--coverage-text' => '',
        ]);

        $result = Process::run($command);
        
        return $result->successful();
    }

    /**
     * Generate Clover coverage report
     */
    public function generateCloverReport(?string $outputPath = null): bool
    {
        $outputPath = $outputPath ?? storage_path('app/laravel-test-accelerator/clover.xml');
        
        File::ensureDirectoryExists(dirname($outputPath));

        $command = $this->buildCoverageCommand([
            '--coverage-clover' => $outputPath,
            '--coverage-text' => '',
        ]);

        $result = Process::run($command);
        
        return $result->successful();
    }

    /**
     * Get coverage statistics
     */
    public function getCoverageStats(): array
    {
        $coverageData = $this->runCoverageAnalysis();
        
        return [
            'total_coverage' => $coverageData['total'] ?? 0,
            'files_analyzed' => count($coverageData['files'] ?? []),
            'files_covered' => count(array_filter($coverageData['files'] ?? [], fn($file) => $file['coverage'] >= 80)),
            'files_low_coverage' => count(array_filter($coverageData['files'] ?? [], fn($file) => $file['coverage'] < 80)),
        ];
    }

    /**
     * Run coverage analysis
     */
    protected function runCoverageAnalysis(): array
    {
        $command = $this->buildCoverageCommand([
            '--coverage-text' => '',
        ]);

        $result = Process::run($command);
        
        if (!$result->successful()) {
            return ['total' => 0, 'files' => []];
        }

        return $this->parseCoverageOutput($result->output());
    }

    /**
     * Build coverage command
     */
    protected function buildCoverageCommand(array $options = []): string
    {
        $command = 'vendor/bin/pest --coverage-text';
        
        foreach ($options as $key => $value) {
            if ($value === '') {
                $command .= " --{$key}";
            } else {
                $command .= " --{$key}={$value}";
            }
        }
        
        return $command;
    }

    /**
     * Parse coverage output
     */
    protected function parseCoverageOutput(string $output): array
    {
        $lines = explode("\n", $output);
        $files = [];
        $totalCoverage = 0;
        
        foreach ($lines as $line) {
            if (str_contains($line, '%') && str_contains($line, '|')) {
                $parts = explode('|', $line);
                if (count($parts) >= 2) {
                    $filePath = trim($parts[0]);
                    $coverage = $this->extractCoveragePercentage($parts[1]);
                    
                    if ($coverage !== null) {
                        $files[] = [
                            'file' => $filePath,
                            'coverage' => $coverage,
                            'status' => $coverage >= 80 ? 'good' : 'low'
                        ];
                    }
                }
            }
            
            if (str_contains($line, 'Total Coverage:')) {
                $totalCoverage = $this->extractCoveragePercentage($line);
            }
        }
        
        return [
            'total' => $totalCoverage ?? 0,
            'files' => $files
        ];
    }

    /**
     * Extract coverage percentage from string
     */
    protected function extractCoveragePercentage(string $text): ?float
    {
        if (preg_match('/(\d+(?:\.\d+)?)%/', $text, $matches)) {
            return (float) $matches[1];
        }
        
        return null;
    }
}
