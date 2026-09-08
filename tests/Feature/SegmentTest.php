<?php

use Illuminate\Support\Facades\Http;
use JeffersonGoncalves\Segment\Facades\Segment;
use JeffersonGoncalves\Segment\Segment as SegmentManager;

it('tracks an event', function () {
    Http::fake(['api.segment.io/*' => Http::response(['success' => true])]);

    $result = Segment::track('signup', '123', ['plan' => 'pro']);

    expect($result['success'])->toBeTrue();
    Http::assertSent(fn ($request) => str_contains($request->url(), 'api.segment.io/v1/track')
        && $request['userId'] === '123'
        && $request['event'] === 'signup'
        && $request['properties']['plan'] === 'pro');
});

it('omits empty properties when tracking', function () {
    Http::fake(['api.segment.io/*' => Http::response(['success' => true])]);

    Segment::track('signup', '123');

    Http::assertSent(fn ($request) => ! array_key_exists('properties', $request->data()));
});

it('identifies a user', function () {
    Http::fake(['api.segment.io/*' => Http::response(['success' => true])]);

    $result = Segment::identify('123', ['email' => 'user@example.com']);

    expect($result['success'])->toBeTrue();
    Http::assertSent(fn ($request) => str_contains($request->url(), 'api.segment.io/v1/identify')
        && $request['userId'] === '123'
        && $request['traits']['email'] === 'user@example.com');
});

it('records a page view', function () {
    Http::fake(['api.segment.io/*' => Http::response(['success' => true])]);

    $result = Segment::page('123', 'Pricing', ['referrer' => 'google']);

    expect($result['success'])->toBeTrue();
    Http::assertSent(fn ($request) => str_contains($request->url(), 'api.segment.io/v1/page')
        && $request['userId'] === '123'
        && $request['name'] === 'Pricing'
        && $request['properties']['referrer'] === 'google');
});

it('sends a batch of messages', function () {
    Http::fake(['api.segment.io/*' => Http::response(['success' => true])]);

    $messages = [
        ['type' => 'track', 'userId' => '123', 'event' => 'signup'],
        ['type' => 'identify', 'userId' => '123', 'traits' => ['plan' => 'pro']],
    ];

    $result = Segment::batch($messages);

    expect($result['success'])->toBeTrue();
    Http::assertSent(fn ($request) => str_contains($request->url(), 'api.segment.io/v1/batch')
        && $request['batch'] === $messages);
});

it('authenticates tracking requests with the write key as the basic auth user', function () {
    Http::fake(['api.segment.io/*' => Http::response(['success' => true])]);

    Segment::track('signup', '123');

    Http::assertSent(fn ($request) => $request->header('Authorization')[0]
        === 'Basic '.base64_encode('test-write-key:'));
});

it('throws when tracking without a write key', function () {
    app('config')->set('segment.write_key', null);
    app()->forgetInstance(SegmentManager::class);

    expect(fn () => Segment::track('signup', '123'))
        ->toThrow(InvalidArgumentException::class, 'Segment write key is required for tracking operations.');
});

it('reads profile traits', function () {
    Http::fake(['profiles.segment.com/*' => Http::response(['traits' => ['plan' => 'pro']])]);

    $result = Segment::profileTraits('123');

    expect($result['traits']['plan'])->toBe('pro');
    Http::assertSent(fn ($request) => str_contains(
        $request->url(),
        'profiles.segment.com/v1/spaces/spa_123/collections/users/profiles/user_id:123/traits'
    ));
});

it('reads profile events', function () {
    Http::fake(['profiles.segment.com/*' => Http::response(['data' => []])]);

    $result = Segment::profileEvents('123', 'spa_override');

    expect($result)->toHaveKey('data');
    Http::assertSent(fn ($request) => str_contains(
        $request->url(),
        'profiles.segment.com/v1/spaces/spa_override/collections/users/profiles/user_id:123/events'
    ));
});

it('throws when reading a profile without an access token', function () {
    app('config')->set('segment.access_token', null);
    app()->forgetInstance(SegmentManager::class);

    expect(fn () => Segment::profileTraits('123'))
        ->toThrow(InvalidArgumentException::class, 'Segment access token is required for profile operations.');
});

it('throws when reading a profile without a space id', function () {
    app('config')->set('segment.space_id', null);
    app()->forgetInstance(SegmentManager::class);

    expect(fn () => Segment::profileTraits('123'))
        ->toThrow(InvalidArgumentException::class, 'Segment space ID is required for profile operations.');
});
