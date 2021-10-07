<?php

namespace Compellio\EloquentAES\Tests;

use Compellio\EloquentAES\EloquentAESServiceProvider;

class TestCase extends \Orchestra\Testbench\TestCase
{
    protected function getPackageProviders($app)
    {
        return [
            EloquentAESServiceProvider::class,
        ];
    }

    public function tearDown(): void
    {
        \Mockery::close();
    }
}