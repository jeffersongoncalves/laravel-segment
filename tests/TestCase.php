<?php

namespace JeffersonGoncalves\Segment\Tests;

use JeffersonGoncalves\Segment\SegmentServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    protected function getPackageProviders($app): array
    {
        return [
            SegmentServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app): void
    {
        $app['config']->set('segment.write_key', 'test-write-key');
        $app['config']->set('segment.access_token', 'test-access-token');
        $app['config']->set('segment.space_id', 'spa_123');
    }
}
