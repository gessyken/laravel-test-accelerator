<?php

namespace KENCODE\LaravelTestAccelerator\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \KENCODE\LaravelTestAccelerator\LaravelTestAccelerator
 */
class LaravelTestAccelerator extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \KENCODE\LaravelTestAccelerator\LaravelTestAccelerator::class;
    }
}
