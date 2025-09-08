<?php

namespace KENCODE\LaravelTestAccelerator\Commands;

use Illuminate\Console\Command;
use KENCODE\LaravelTestAccelerator\Services\TestGenerator;
use KENCODE\LaravelTestAccelerator\Services\AITestGenerator;

class GenerateTestsCommand extends Command
{
    protected $signature = 'test:generate 
                            {path? : Path to file or directory to generate tests for}
                            {--a|ai : Use AI to generate test cases}
                            {--m|model= : Specific model to generate tests for}
                            {--t|type= : Test type (unit, feature, model, controller)}
                            {--f|force : Overwrite existing test files}
                            {--p|prompt= : Custom prompt for AI generation}';

    protected $description = 'Generate tests for existing code';

    public function handle(TestGenerator $testGenerator, AITestGenerator $aiGenerator): int
    {
        $path = $this->argument('path') ?? app_path();
        $useAI = $this->option('ai');
        $model = $this->option('model');
        $type = $this->option('type');
        $force = $this->option('force');
        $prompt = $this->option('prompt');

        $this->info("🚀 Generating tests for: {$path}");

        if ($useAI) {
            $this->info("🤖 Using AI to generate test cases...");
            
            // Validate AI configuration
            $errors = $aiGenerator->validateConfiguration();
            if (!empty($errors)) {
                foreach ($errors as $error) {
                    $this->error("❌ {$error}");
                }
                return self::FAILURE;
            }

            // Test AI connection
            if (!$aiGenerator->testConnection()) {
                $this->error("❌ Failed to connect to AI service");
                return self::FAILURE;
            }

            $this->info("✅ AI connection successful");

            $result = $aiGenerator->generate($path, $prompt, [
                'force' => $force,
                'type' => $type,
            ]);
        } else {
            $this->info("📝 Using basic test generation...");
            
            $options = [
                'force' => $force,
                'type' => $type,
            ];

            if ($model) {
                $result = $testGenerator->generateForModel($path, $options);
            } else {
                $result = $testGenerator->generateBasicTests($path, $options);
            }
        }

        if ($result) {
            $this->info("✅ Tests generated successfully!");
            $this->line("📁 Check your tests directory for the generated files.");
            
            // Show next steps
            $this->newLine();
            $this->info("Next steps:");
            $this->line("• Run tests: composer test");
            $this->line("• Check coverage: php artisan test:coverage");
            $this->line("• Analyze performance: php artisan test:benchmark");
            
            return self::SUCCESS;
        } else {
            $this->error("❌ Failed to generate tests");
            $this->line("💡 Try using --force to overwrite existing files");
            return self::FAILURE;
        }
    }
}
