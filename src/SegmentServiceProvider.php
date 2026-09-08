<?php

namespace JeffersonGoncalves\Segment;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class SegmentServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('laravel-segment')
            ->hasConfigFile('segment');
    }

    public function packageRegistered(): void
    {
        $this->app->singleton(Segment::class, function () {
            return new Segment(
                config('segment.write_key'),
                config('segment.access_token'),
                config('segment.space_id'),
            );
        });
    }
}
