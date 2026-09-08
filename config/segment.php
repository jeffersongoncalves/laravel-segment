<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Write Key
    |--------------------------------------------------------------------------
    |
    | Authenticates ingestion requests (track, identify, page, batch) against
    | the Tracking API. Find it under your source's Settings > API Keys.
    |
    */
    'write_key' => env('SEGMENT_WRITE_KEY', ''),

    /*
    |--------------------------------------------------------------------------
    | Access Token
    |--------------------------------------------------------------------------
    |
    | Authenticates read requests against the Profile API (Personas). Create
    | one under Unify/Personas > Settings > API Access.
    |
    */
    'access_token' => env('SEGMENT_ACCESS_TOKEN', ''),

    /*
    |--------------------------------------------------------------------------
    | Space ID
    |--------------------------------------------------------------------------
    |
    | Default Personas space queried by the Profile API. Can be overridden per
    | call. Found in the Profile API settings URL.
    |
    */
    'space_id' => env('SEGMENT_SPACE_ID', ''),

];
