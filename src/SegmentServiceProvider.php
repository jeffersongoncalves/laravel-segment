<?php

namespace Jeffersongoncalves\Segment;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class SegmentServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('laravel-segment')
            ->hasConfigFile()
            ->hasViews()
            ->hasMigrations();
    }
}
