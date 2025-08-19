<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Safari Analytics Configuration
    |--------------------------------------------------------------------------
    |
    | This file contains the configuration for Safari Analytics integration.
    |
    */

    'website_id' => env('SAFARI_WEBSITE_ID', ''),
    'enabled' => env('SAFARI_ENABLED', false),
    'track_pageviews' => env('SAFARI_TRACK_PAGEVIEWS', true),
    'track_events' => env('SAFARI_TRACK_EVENTS', true),
    'track_downloads' => env('SAFARI_TRACK_DOWNLOADS', true),
    'track_donations' => env('SAFARI_TRACK_DONATIONS', true),
    'track_engagement' => env('SAFARI_TRACK_ENGAGEMENT', true),
    'debug_mode' => env('SAFARI_DEBUG_MODE', false),
];
