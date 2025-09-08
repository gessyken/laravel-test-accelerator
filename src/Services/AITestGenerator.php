<?php

namespace KENCODE\LaravelTestAccelerator\Services;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;

class AITestGenerator
{
    protected array $config;

    protected string $provider;

    protected string $apiKey;

    protected string $model;

    public function __construct()
    {
        $this->config = config('laravel-test-accelerator', []);
        $this->provider = $this->config['ai_provider'] ?? 'openai';
        $this->apiKey = $this->config['ai_api_key'] ?? '';
        $this->model = $this->config['ai_model'] ?? 'gpt-4';
    }

    /**
     * Generate tests using AI
     */
    public function generate(string $path, string $prompt = '', array $options = []): bool
    {
        if (empty($this->apiKey)) {
            throw new \Exception('AI API key is not configured');
        }

        $fileContent = File::get($path);
        $className = $this->extractClassName($fileContent);

        if (! $className) {
            return false;
        }

        $aiPrompt = $this->buildPrompt($fileContent, $className, $prompt);
        $testCode = $this->callAI($aiPrompt, $options);

        if (! $testCode) {
            return false;
        }

        return $this->saveTestFile($className, $testCode, $options);
    }

    /**
     * Generate tests with context
     */
    public function generateWithContext(string $path, array $contextData, string $prompt = ''): bool
    {
        $fileContent = File::get($path);
        $className = $this->extractClassName($fileContent);

        if (! $className) {
            return false;
        }

        $contextPrompt = $this->buildContextPrompt($fileContent, $className, $contextData, $prompt);
        $testCode = $this->callAI($contextPrompt);

        if (! $testCode) {
            return false;
        }

        return $this->saveTestFile($className, $testCode);
    }

    /**
     * Build AI prompt
     */
    protected function buildPrompt(string $fileContent, string $className, string $customPrompt = ''): string
    {
        $basePrompt = 'Generate comprehensive PHPUnit tests for the following PHP class. ';
        $basePrompt .= 'The tests should cover all public methods, edge cases, and follow Laravel testing best practices. ';
        $basePrompt .= "Use Pest PHP syntax and include proper setup, teardown, and assertions.\n\n";

        if (! empty($customPrompt)) {
            $basePrompt .= "Additional requirements: {$customPrompt}\n\n";
        }

        $basePrompt .= "Class to test:\n```php\n{$fileContent}\n```\n\n";
        $basePrompt .= 'Generate only the test code, no explanations or markdown formatting.';

        return $basePrompt;
    }

    /**
     * Build context-aware prompt
     */
    protected function buildContextPrompt(string $fileContent, string $className, array $contextData, string $customPrompt = ''): string
    {
        $prompt = $this->buildPrompt($fileContent, $className, $customPrompt);

        if (! empty($contextData)) {
            $prompt .= "\n\nAdditional context:\n";
            foreach ($contextData as $key => $value) {
                $prompt .= "- {$key}: {$value}\n";
            }
        }

        return $prompt;
    }

    /**
     * Call AI API
     */
    protected function callAI(string $prompt, array $options = []): ?string
    {
        switch ($this->provider) {
            case 'openai':
                return $this->callOpenAI($prompt, $options);
            case 'anthropic':
                return $this->callAnthropic($prompt, $options);
            default:
                throw new \Exception("Unsupported AI provider: {$this->provider}");
        }
    }

    /**
     * Call OpenAI API
     */
    protected function callOpenAI(string $prompt, array $options = []): ?string
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer '.$this->apiKey,
            'Content-Type' => 'application/json',
        ])->post('https://api.openai.com/v1/chat/completions', [
            'model' => $this->model,
            'messages' => [
                [
                    'role' => 'user',
                    'content' => $prompt,
                ],
            ],
            'max_tokens' => $options['max_tokens'] ?? 4000,
            'temperature' => $options['temperature'] ?? 0.7,
        ]);

        if (! $response->successful()) {
            throw new \Exception('OpenAI API request failed: '.$response->body());
        }

        $data = $response->json();

        return $data['choices'][0]['message']['content'] ?? null;
    }

    /**
     * Call Anthropic API
     */
    protected function callAnthropic(string $prompt, array $options = []): ?string
    {
        $response = Http::withHeaders([
            'x-api-key' => $this->apiKey,
            'Content-Type' => 'application/json',
            'anthropic-version' => '2023-06-01',
        ])->post('https://api.anthropic.com/v1/messages', [
            'model' => $this->model,
            'max_tokens' => $options['max_tokens'] ?? 4000,
            'messages' => [
                [
                    'role' => 'user',
                    'content' => $prompt,
                ],
            ],
        ]);

        if (! $response->successful()) {
            throw new \Exception('Anthropic API request failed: '.$response->body());
        }

        $data = $response->json();

        return $data['content'][0]['text'] ?? null;
    }

    /**
     * Extract class name from file content
     */
    protected function extractClassName(string $fileContent): ?string
    {
        if (preg_match('/class\s+(\w+)/', $fileContent, $matches)) {
            return $matches[1];
        }

        return null;
    }

    /**
     * Save generated test file
     */
    protected function saveTestFile(string $className, string $testCode, array $options = []): bool
    {
        $testClassName = $className.'Test';
        $testPath = $this->getTestPath($className, $options['type'] ?? 'Unit');

        if (File::exists($testPath) && ! ($options['force'] ?? false)) {
            return false;
        }

        // Clean up the AI response
        $testCode = $this->cleanAICode($testCode);

        File::ensureDirectoryExists(dirname($testPath));
        File::put($testPath, $testCode);

        return true;
    }

    /**
     * Clean AI-generated code
     */
    protected function cleanAICode(string $code): string
    {
        // Remove markdown code blocks if present
        $code = preg_replace('/```php\s*/', '', $code);
        $code = preg_replace('/```\s*$/', '', $code);

        // Remove any leading/trailing whitespace
        $code = trim($code);

        return $code;
    }

    /**
     * Get test file path
     */
    protected function getTestPath(string $className, string $type = 'Unit'): string
    {
        $testPaths = config('laravel-test-accelerator.test_paths', ['tests/Unit', 'tests/Feature']);
        $basePath = $testPaths[0]; // Default to Unit

        if ($type === 'Feature') {
            $basePath = $testPaths[1] ?? $testPaths[0];
        }

        return base_path($basePath.'/'.$className.'Test.php');
    }

    /**
     * Validate AI configuration
     */
    public function validateConfiguration(): array
    {
        $errors = [];

        if (empty($this->apiKey)) {
            $errors[] = 'AI API key is not configured';
        }

        if (! in_array($this->provider, ['openai', 'anthropic'])) {
            $errors[] = 'Unsupported AI provider: '.$this->provider;
        }

        return $errors;
    }

    /**
     * Test AI connection
     */
    public function testConnection(): bool
    {
        try {
            $testPrompt = 'Generate a simple PHP test for a basic class.';
            $this->callAI($testPrompt);

            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
}
