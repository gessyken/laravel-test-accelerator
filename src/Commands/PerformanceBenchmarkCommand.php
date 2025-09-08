<?php

namespace KENCODE\LaravelTestAccelerator\Commands;

use Illuminate\Console\Command;
use KENCODE\LaravelTestAccelerator\Services\PerformanceAnalyzer;

class PerformanceBenchmarkCommand extends Command
{
    protected $signature = 'test:benchmark 
                            {--s|slow-threshold= : Threshold for slow tests in milliseconds}
                            {--m|memory-threshold= : Threshold for memory usage in KB}
                            {--r|report : Generate detailed performance report}
                            {--o|output= : Output directory for reports}';

    protected $description = 'Analyze test performance and identify bottlenecks';

    public function handle(PerformanceAnalyzer $performanceAnalyzer): int
    {
        $slowThreshold = $this->option('slow-threshold');
        $memoryThreshold = $this->option('memory-threshold');
        $generateReport = $this->option('report');
        $outputDir = $this->option('output');

        $this->info('⚡ Running performance benchmark...');

        $options = [];
        if ($slowThreshold) {
            $options['slow_threshold'] = (int) $slowThreshold;
        }
        if ($memoryThreshold) {
            $options['memory_threshold'] = (int) $memoryThreshold;
        }

        $results = $performanceAnalyzer->analyze($options);

        // Display results
        $this->displayPerformanceResults($results);

        // Generate report if requested
        if ($generateReport) {
            $this->generatePerformanceReport($performanceAnalyzer, $outputDir);
        }

        $this->newLine();
        $this->info('✅ Performance analysis completed!');

        // Return failure if there are performance issues
        if (! empty($results['slow_tests']) || ! empty($results['memory_issues'])) {
            return self::FAILURE;
        }

        return self::SUCCESS;
    }

    protected function displayPerformanceResults(array $results): void
    {
        $this->newLine();

        // Display summary
        $this->info('📊 Performance Summary:');
        $this->line("Total Tests: {$results['total_tests']}");
        $this->line('Average Time: '.number_format($results['average_time'], 2).'ms');
        $this->line('Total Time: '.number_format($results['total_time'], 2).'ms');
        $this->line('Slow Tests: '.count($results['slow_tests']));
        $this->line('Memory Issues: '.count($results['memory_issues']));

        // Display slow tests
        if (! empty($results['slow_tests'])) {
            $this->newLine();
            $this->warn('🐌 Slow Tests:');

            $headers = ['Test Name', 'Time (ms)', 'Memory (KB)'];
            $rows = [];

            foreach ($results['slow_tests'] as $test) {
                $rows[] = [
                    $this->truncateString($test['name'], 40),
                    number_format($test['time'], 2),
                    number_format($test['memory'], 2),
                ];
            }

            $this->table($headers, $rows);
        }

        // Display memory issues
        if (! empty($results['memory_issues'])) {
            $this->newLine();
            $this->warn('💾 Memory Issues:');

            $headers = ['Test Name', 'Memory (KB)', 'Time (ms)'];
            $rows = [];

            foreach ($results['memory_issues'] as $test) {
                $rows[] = [
                    $this->truncateString($test['name'], 40),
                    number_format($test['memory'], 2),
                    number_format($test['time'], 2),
                ];
            }

            $this->table($headers, $rows);
        }

        // Display recommendations
        if (! empty($results['recommendations'])) {
            $this->newLine();
            $this->info('💡 Recommendations:');

            foreach ($results['recommendations'] as $recommendation) {
                $icon = $recommendation['type'] === 'success' ? '✅' : '⚠️';
                $this->line("{$icon} {$recommendation['message']}");

                if (! empty($recommendation['tests'])) {
                    foreach (array_slice($recommendation['tests'], 0, 3) as $test) {
                        $this->line("   • {$test}");
                    }

                    if (count($recommendation['tests']) > 3) {
                        $remaining = count($recommendation['tests']) - 3;
                        $this->line("   • ... and {$remaining} more");
                    }
                }
            }
        }
    }

    protected function generatePerformanceReport(PerformanceAnalyzer $performanceAnalyzer, ?string $outputDir): void
    {
        $this->newLine();
        $this->info('📄 Generating performance report...');

        $report = $performanceAnalyzer->generateReport();

        $outputPath = $outputDir ?? storage_path('app/laravel-test-accelerator');
        $reportPath = $outputPath.'/performance-report.json';

        \Illuminate\Support\Facades\File::ensureDirectoryExists($outputPath);
        \Illuminate\Support\Facades\File::put($reportPath, json_encode($report, JSON_PRETTY_PRINT));

        $this->info("✅ Performance report saved to: {$reportPath}");
    }

    protected function truncateString(string $string, int $length): string
    {
        if (strlen($string) <= $length) {
            return $string;
        }

        return substr($string, 0, $length - 3).'...';
    }
}
