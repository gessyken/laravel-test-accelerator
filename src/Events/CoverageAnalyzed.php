<?php

namespace KENCODE\LaravelTestAccelerator\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CoverageAnalyzed
{
    use Dispatchable, SerializesModels;

    public float $coverage;
    public float $threshold;
    public bool $passed;
    public array $files;
    public array $options;

    public function __construct(float $coverage, float $threshold, bool $passed, array $files, array $options = [])
    {
        $this->coverage = $coverage;
        $this->threshold = $threshold;
        $this->passed = $passed;
        $this->files = $files;
        $this->options = $options;
    }
}
