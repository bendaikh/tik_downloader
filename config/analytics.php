<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Google Analytics Configuration
    |--------------------------------------------------------------------------
    |
    | This file contains the configuration for Google Analytics integration.
    |
    */

    'ga_measurement_id' => env('GA_MEASUREMENT_ID', ''),
    'ga_enabled' => env('GA_ENABLED', false),
    'ga_track_pageviews' => env('GA_TRACK_PAGEVIEWS', true),
    'ga_track_events' => env('GA_TRACK_EVENTS', true),
    'ga_track_downloads' => env('GA_TRACK_DOWNLOADS', true),
    'ga_track_donations' => env('GA_TRACK_DONATIONS', true),
    'ga_anonymize_ip' => env('GA_ANONYMIZE_IP', true),
    'ga_debug_mode' => env('GA_DEBUG_MODE', false),
];
