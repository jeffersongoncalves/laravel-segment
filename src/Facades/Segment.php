<?php

namespace JeffersonGoncalves\Segment\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \JeffersonGoncalves\Segment\Segment
 *
 * @method static array track(string $event, string $userId, array $properties = [])
 * @method static array identify(string $userId, array $traits = [])
 * @method static array page(string $userId, ?string $name = null, array $properties = [])
 * @method static array batch(array $messages)
 * @method static array profileTraits(string $userId, ?string $spaceId = null)
 * @method static array profileEvents(string $userId, ?string $spaceId = null)
 */
class Segment extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \JeffersonGoncalves\Segment\Segment::class;
    }
}
