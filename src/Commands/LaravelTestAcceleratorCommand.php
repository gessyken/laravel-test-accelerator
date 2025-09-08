<?php

namespace KENCODE\LaravelTestAccelerator\Commands;

use Illuminate\Console\Command;

class LaravelTestAcceleratorCommand extends Command
{
    public $signature = 'laravel-test-accelerator';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
