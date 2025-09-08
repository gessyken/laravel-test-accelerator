<?php

// Configuration for KENCODE/LaravelTestAccelerator
return [
    /*
    |--------------------------------------------------------------------------
    | AI Provider
    |--------------------------------------------------------------------------
    |
    | Defines the AI provider to use for test generation.
    | Available options: openai, anthropic, claude
    |
    */
    'ai_provider' => env('TEST_ACCELERATOR_AI_PROVIDER', 'openai'),

    /*
    |--------------------------------------------------------------------------
    | AI API Key
    |--------------------------------------------------------------------------
    |
    | API key for the selected AI provider.
    | Make sure to keep this key secure.
    |
    */
    'ai_api_key' => env('TEST_ACCELERATOR_AI_API_KEY'),

    /*
    |--------------------------------------------------------------------------
    | AI Model
    |--------------------------------------------------------------------------
    |
    | Specific model to use for test generation.
    | Examples: gpt-4, gpt-3.5-turbo, claude-3-sonnet
    |
    */
    'ai_model' => env('TEST_ACCELERATOR_AI_MODEL', 'gpt-4'),

    /*
    |--------------------------------------------------------------------------
    | Test Paths
    |--------------------------------------------------------------------------
    |
    | Directories where to search and generate tests.
    |
    */
    'test_paths' => [
        'tests/Unit',
        'tests/Feature',
        'tests/Integration',
    ],

    /*
    |--------------------------------------------------------------------------
    | Ignore Directories
    |--------------------------------------------------------------------------
    |
    | Directories to ignore during analysis and test generation.
    |
    */
    'ignore_directories' => [
        'vendor',
        'node_modules',
        'storage',
        'bootstrap/cache',
        'public',
        'resources/views',
    ],

    /*
    |--------------------------------------------------------------------------
    | Benchmark Configuration
    |--------------------------------------------------------------------------
    |
    | Thresholds for test performance analysis.
    |
    */
    'benchmark' => [
        'slow_threshold' => env('TEST_ACCELERATOR_SLOW_THRESHOLD', 1000), // ms
        'memory_threshold' => env('TEST_ACCELERATOR_MEMORY_THRESHOLD', 1024), // KB
        'enable_profiling' => env('TEST_ACCELERATOR_ENABLE_PROFILING', true),
    ],

    /*
    |--------------------------------------------------------------------------
    | Coverage Configuration
    |--------------------------------------------------------------------------
    |
    | Parameters for code coverage analysis.
    |
    */
    'coverage' => [
        'threshold' => env('TEST_ACCELERATOR_COVERAGE_THRESHOLD', 80), // %
        'html_reports' => env('TEST_ACCELERATOR_HTML_REPORTS', true),
        'xml_reports' => env('TEST_ACCELERATOR_XML_REPORTS', false),
        'clover_reports' => env('TEST_ACCELERATOR_CLOVER_REPORTS', false),
    ],

    /*
    |--------------------------------------------------------------------------
    | Test Templates
    |--------------------------------------------------------------------------
    |
    | Configuration of templates used for test generation.
    |
    */
    'templates' => [
        'unit_test' => 'laravel-test-accelerator::templates.unit-test',
        'feature_test' => 'laravel-test-accelerator::templates.feature-test',
        'model_test' => 'laravel-test-accelerator::templates.model-test',
        'controller_test' => 'laravel-test-accelerator::templates.controller-test',
    ],

    /*
    |--------------------------------------------------------------------------
    | Reports Configuration
    |--------------------------------------------------------------------------
    |
    | Parameters for report generation.
    |
    */
    'reports' => [
        'output_directory' => storage_path('app/laravel-test-accelerator'),
        'keep_reports' => env('TEST_ACCELERATOR_KEEP_REPORTS', 10), // number of reports to keep
        'include_screenshots' => env('TEST_ACCELERATOR_INCLUDE_SCREENSHOTS', false),
    ],

    /*
    |--------------------------------------------------------------------------
    | Auto Generation Configuration
    |--------------------------------------------------------------------------
    |
    | Parameters for automatic test generation.
    |
    */
    'auto_generation' => [
        'enabled' => env('TEST_ACCELERATOR_AUTO_GENERATE', false),
        'watch_directories' => [
            'app/Models',
            'app/Services',
            'app/Http/Controllers',
        ],
        'exclude_patterns' => [
            '*Test.php',
            '*Trait.php',
            'Abstract*',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Notifications Configuration
    |--------------------------------------------------------------------------
    |
    | Parameters for notifications (email, slack, etc.).
    |
    */
    'notifications' => [
        'enabled' => env('TEST_ACCELERATOR_NOTIFICATIONS', false),
        'channels' => ['mail'], // mail, slack, discord
        'coverage_threshold' => 70,
        'performance_threshold' => 2000, // ms
    ],
];
