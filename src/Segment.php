<?php

namespace JeffersonGoncalves\Segment;

use Illuminate\Support\Facades\Http;
use InvalidArgumentException;

/**
 * Thin wrapper around Laravel's Http client for Segment's HTTP APIs:
 * the Tracking API (track/identify/page/batch, authenticated with the
 * source write key) and the Profile API (Personas traits and events,
 * authenticated with an access token).
 */
class Segment
{
    protected const TRACKING_URL = 'https://api.segment.io/v1';

    protected const PROFILE_URL = 'https://profiles.segment.com/v1';

    public function __construct(
        protected ?string $writeKey = null,
        protected ?string $accessToken = null,
        protected ?string $spaceId = null,
    ) {}

    /**
     * @param  array<string, mixed>  $properties
     * @return array<string, mixed>
     */
    public function track(string $event, string $userId, array $properties = []): array
    {
        return $this->tracking('/track', array_filter([
            'userId' => $userId,
            'event' => $event,
            'properties' => $properties ?: null,
        ]));
    }

    /**
     * @param  array<string, mixed>  $traits
     * @return array<string, mixed>
     */
    public function identify(string $userId, array $traits = []): array
    {
        return $this->tracking('/identify', array_filter([
            'userId' => $userId,
            'traits' => $traits ?: null,
        ]));
    }

    /**
     * @param  array<string, mixed>  $properties
     * @return array<string, mixed>
     */
    public function page(string $userId, ?string $name = null, array $properties = []): array
    {
        return $this->tracking('/page', array_filter([
            'userId' => $userId,
            'name' => $name,
            'properties' => $properties ?: null,
        ]));
    }

    /**
     * Sends up to 500KB of messages in one request. Each entry needs its own
     * `type` key (`track`, `identify`, `page`, ...) alongside its payload.
     *
     * @param  array<int, array<string, mixed>>  $messages
     * @return array<string, mixed>
     */
    public function batch(array $messages): array
    {
        return $this->tracking('/batch', ['batch' => $messages]);
    }

    /**
     * @return array<string, mixed>
     */
    public function profileTraits(string $userId, ?string $spaceId = null): array
    {
        return $this->profile($this->resolveSpaceId($spaceId), $userId, 'traits');
    }

    /**
     * @return array<string, mixed>
     */
    public function profileEvents(string $userId, ?string $spaceId = null): array
    {
        return $this->profile($this->resolveSpaceId($spaceId), $userId, 'events');
    }

    /**
     * @param  array<string, mixed>  $body
     * @return array<string, mixed>
     */
    protected function tracking(string $path, array $body): array
    {
        if (blank($this->writeKey)) {
            throw new InvalidArgumentException('Segment write key is required for tracking operations.');
        }

        // Segment authenticates the Tracking API with the write key as the
        // Basic Auth username and an empty password.
        $response = Http::withBasicAuth($this->writeKey, '')
            ->asJson()
            ->post(self::TRACKING_URL.$path, $body);

        return (array) ($response->json() ?? []);
    }

    /**
     * @return array<string, mixed>
     */
    protected function profile(string $spaceId, string $userId, string $collection): array
    {
        if (blank($this->accessToken)) {
            throw new InvalidArgumentException('Segment access token is required for profile operations.');
        }

        $response = Http::withBasicAuth($this->accessToken, '')
            ->get(self::PROFILE_URL."/spaces/{$spaceId}/collections/users/profiles/user_id:{$userId}/{$collection}");

        return (array) ($response->json() ?? []);
    }

    protected function resolveSpaceId(?string $spaceId): string
    {
        $spaceId ??= $this->spaceId;

        if (blank($spaceId)) {
            throw new InvalidArgumentException('Segment space ID is required for profile operations.');
        }

        return $spaceId;
    }
}
