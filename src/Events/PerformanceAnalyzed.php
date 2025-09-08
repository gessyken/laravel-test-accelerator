<?php

namespace KENCODE\LaravelTestAccelerator\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PerformanceAnalyzed
{
    use Dispatchable, SerializesModels;

    public array $slowTests;

    public array $memoryIssues;

    public float $averageTime;

    public float $totalTime;

    public array $recommendations;

    public function __construct(
        array $slowTests,
        array $memoryIssues,
        float $averageTime,
        float $totalTime,
        array $recommendations = []
    ) {
        $this->slowTests = $slowTests;
        $this->memoryIssues = $memoryIssues;
        $this->averageTime = $averageTime;
        $this->totalTime = $totalTime;
        $this->recommendations = $recommendations;
    }
}
