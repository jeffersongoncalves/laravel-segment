<div class="filament-hidden">

![Laravel Segment](https://raw.githubusercontent.com/jeffersongoncalves/laravel-segment/main/art/jeffersongoncalves-laravel-segment.png)

</div>

# Laravel Segment

[![Latest Version on Packagist](https://img.shields.io/packagist/v/jeffersongoncalves/laravel-segment.svg?style=flat-square)](https://packagist.org/packages/jeffersongoncalves/laravel-segment)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/jeffersongoncalves/laravel-segment/tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/jeffersongoncalves/laravel-segment/actions?query=workflow%3ATests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/jeffersongoncalves/laravel-segment/pint.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/jeffersongoncalves/laravel-segment/actions?query=workflow%3A"Fix+PHP+code+styling"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/jeffersongoncalves/laravel-segment.svg?style=flat-square)](https://packagist.org/packages/jeffersongoncalves/laravel-segment)
[![License](https://img.shields.io/packagist/l/jeffersongoncalves/laravel-segment.svg?style=flat-square)](LICENSE.md)

A Laravel client for [Segment](https://segment.com)'s **HTTP API**: track events, identify users, record page views, send batches, and read Personas profile traits and events through a simple, typed API built on Laravel's `Http` client.

This is a server-side API client only — no JS SDK, no Blade snippet.

## Features

- Tracking API: `track`, `identify`, `page`, `batch`
- Profile API (Personas): `profileTraits`, `profileEvents`
- Throws `InvalidArgumentException` when the credentials a call requires are missing

## Installation

You can install the package via composer:

```bash
composer require jeffersongoncalves/laravel-segment
```

Publish the config file:

```bash
php artisan vendor:publish --tag=laravel-segment-config
```

Set your Segment credentials in `.env`:

```env
SEGMENT_WRITE_KEY=your-source-write-key
SEGMENT_ACCESS_TOKEN=your-profile-api-access-token
SEGMENT_SPACE_ID=your-personas-space-id
```

The write key authenticates the Tracking API (`track`, `identify`, `page`, `batch`) — find it under your source's **Settings > API Keys**. The access token and space ID authenticate the Profile API (`profileTraits`, `profileEvents`) — create one under **Unify/Personas > Settings > API Access**.

## Configuration

```php
// config/segment.php
return [
    'write_key' => env('SEGMENT_WRITE_KEY', ''),
    'access_token' => env('SEGMENT_ACCESS_TOKEN', ''),
    'space_id' => env('SEGMENT_SPACE_ID', ''),
];
```

## Usage

The package is resolved via the `Segment` facade or by injecting `JeffersonGoncalves\Segment\Segment`.

### Track an event

```php
use JeffersonGoncalves\Segment\Facades\Segment;

Segment::track('Order Completed', $user->id, [
    'revenue' => 99.90,
    'plan' => 'pro',
]);
```

### Identify a user

```php
Segment::identify($user->id, [
    'email' => $user->email,
    'name' => $user->name,
]);
```

### Record a page view

```php
Segment::page($user->id, 'Pricing', [
    'referrer' => 'google',
]);
```

### Send a batch

Each message carries its own `type` alongside its payload. Segment caps a batch request at 500KB.

```php
Segment::batch([
    ['type' => 'track', 'userId' => $user->id, 'event' => 'Signed Up'],
    ['type' => 'identify', 'userId' => $user->id, 'traits' => ['plan' => 'pro']],
]);
```

### Read profile traits and events

```php
$traits = Segment::profileTraits($user->id);
$events = Segment::profileEvents($user->id);
```

The space ID falls back to `segment.space_id`, and can be overridden per call:

```php
$traits = Segment::profileTraits($user->id, 'spa_other_space');
```

### Error handling

Calling a method without the credentials it requires throws `InvalidArgumentException`:

```php
try {
    Segment::track('Order Completed', $user->id);
} catch (InvalidArgumentException $e) {
    logger()->error($e->getMessage());
}
```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Contributions are welcome. Please open an issue or pull request on GitHub.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Jefferson Gonçalves](https://github.com/jeffersongoncalves)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
