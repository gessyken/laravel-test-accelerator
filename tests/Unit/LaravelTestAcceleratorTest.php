<?php

use KENCODE\LaravelTestAccelerator\Facades\LaravelTestAccelerator;
use KENCODE\LaravelTestAccelerator\LaravelTestAccelerator as LaravelTestAcceleratorClass;

it('can be instantiated', function () {
    expect(LaravelTestAccelerator::class)->toBeString();
});

it('has generateTests method', function () {
    expect(method_exists(LaravelTestAcceleratorClass::class, 'generateTests'))->toBeTrue();
});

it('has analyzeCoverage method', function () {
    expect(method_exists(LaravelTestAcceleratorClass::class, 'analyzeCoverage'))->toBeTrue();
});

it('has getPerformanceStats method', function () {
    expect(method_exists(LaravelTestAcceleratorClass::class, 'getPerformanceStats'))->toBeTrue();
});

it('has generateWithAI method', function () {
    expect(method_exists(LaravelTestAcceleratorClass::class, 'generateWithAI'))->toBeTrue();
});
