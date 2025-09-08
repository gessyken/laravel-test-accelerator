<?php

namespace KENCODE\LaravelTestAccelerator\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TestsGenerated
{
    use Dispatchable, SerializesModels;

    public string $path;

    public array $options;

    public bool $success;

    public ?string $error;

    public function __construct(string $path, array $options, bool $success, ?string $error = null)
    {
        $this->path = $path;
        $this->options = $options;
        $this->success = $success;
        $this->error = $error;
    }
}
