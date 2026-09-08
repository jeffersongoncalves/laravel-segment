<?php

namespace Jeffersongoncalves\Segment\Tests;

use Jeffersongoncalves\Segment\SegmentServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    protected function getPackageProviders($app): array
    {
        return [
            SegmentServiceProvider::class,
        ];
    }
}
