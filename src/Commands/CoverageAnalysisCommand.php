<?php

namespace KENCODE\LaravelTestAccelerator\Commands;

use Illuminate\Console\Command;
use KENCODE\LaravelTestAccelerator\Services\CoverageAnalyzer;

class CoverageAnalysisCommand extends Command
{
    protected $signature = 'test:coverage 
                            {--r|report : Generate HTML coverage report}
                            {--x|xml : Generate XML coverage report}
                            {--c|clover : Generate Clover coverage report}
                            {--t|threshold= : Minimum coverage percentage to enforce}
                            {--o|output= : Output directory for reports}';

    protected $description = 'Analyze test coverage and identify gaps';

    public function handle(CoverageAnalyzer $coverageAnalyzer): int
    {
        $threshold = (int) $this->option('threshold');
        $generateReport = $this->option('report');
        $generateXml = $this->option('xml');
        $generateClover = $this->option('clover');
        $outputDir = $this->option('output');

        $this->info('📊 Analyzing test coverage...');

        $options = [
            'threshold' => $threshold,
        ];

        $results = $coverageAnalyzer->analyze($options);

        // Display results
        $this->displayCoverageResults($results);

        // Generate reports if requested
        if ($generateReport || $generateXml || $generateClover) {
            $this->generateReports($coverageAnalyzer, $outputDir, $generateReport, $generateXml, $generateClover);
        }

        // Check if coverage meets threshold
        if ($results['total'] < $results['threshold']) {
            $this->newLine();
            $this->error("❌ Coverage below threshold of {$results['threshold']}%");
            $this->line('💡 Consider adding more tests or improving existing ones');

            return self::FAILURE;
        }

        $this->newLine();
        $this->info('✅ Coverage analysis completed successfully!');

        return self::SUCCESS;
    }

    protected function displayCoverageResults(array $results): void
    {
        $this->newLine();

        // Display file-by-file coverage
        if (! empty($results['files'])) {
            $this->info('📁 File Coverage:');

            $headers = ['File', 'Coverage %', 'Status'];
            $rows = [];

            foreach ($results['files'] as $file) {
                $status = $file['coverage'] >= 80 ? '✅ Good' : '⚠️ Low';
                $rows[] = [
                    $this->truncatePath($file['file']),
                    number_format($file['coverage'], 1).'%',
                    $status,
                ];
            }

            $this->table($headers, $rows);
        }

        // Display overall coverage
        $this->newLine();
        $this->info('📈 Overall Coverage: '.number_format($results['total'], 1).'%');
        $this->info('🎯 Threshold: '.$results['threshold'].'%');

        $status = $results['passed'] ? '✅ Passed' : '❌ Failed';
        $this->info("📊 Status: {$status}");
    }

    protected function generateReports(CoverageAnalyzer $coverageAnalyzer, ?string $outputDir, bool $html, bool $xml, bool $clover): void
    {
        $this->newLine();
        $this->info('📄 Generating reports...');

        $success = true;

        if ($html) {
            $this->line('Generating HTML report...');
            $htmlSuccess = $coverageAnalyzer->generateHtmlReport($outputDir);
            if ($htmlSuccess) {
                $this->info('✅ HTML report generated');
            } else {
                $this->error('❌ Failed to generate HTML report');
                $success = false;
            }
        }

        if ($xml) {
            $this->line('Generating XML report...');
            $xmlSuccess = $coverageAnalyzer->generateXmlReport($outputDir);
            if ($xmlSuccess) {
                $this->info('✅ XML report generated');
            } else {
                $this->error('❌ Failed to generate XML report');
                $success = false;
            }
        }

        if ($clover) {
            $this->line('Generating Clover report...');
            $cloverSuccess = $coverageAnalyzer->generateCloverReport($outputDir);
            if ($cloverSuccess) {
                $this->info('✅ Clover report generated');
            } else {
                $this->error('❌ Failed to generate Clover report');
                $success = false;
            }
        }

        if ($success) {
            $this->info('📁 Reports saved to: '.($outputDir ?? storage_path('app/laravel-test-accelerator')));
        }
    }

    protected function truncatePath(string $path, int $length = 50): string
    {
        if (strlen($path) <= $length) {
            return $path;
        }

        return '...'.substr($path, -($length - 3));
    }
}
