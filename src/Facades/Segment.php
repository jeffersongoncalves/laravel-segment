<?php

namespace Jeffersongoncalves\Segment\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Jeffersongoncalves\Segment\Segment
 */
class Segment extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'laravel-segment';
    }
}
