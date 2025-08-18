<?php

return [
    /*
    |--------------------------------------------------------------------------
    | PayPal Configuration
    |--------------------------------------------------------------------------
    |
    | This file contains the configuration for PayPal integration.
    |
    */

    'paypal_client_id' => env('PAYPAL_CLIENT_ID', ''),
    'paypal_client_secret' => env('PAYPAL_CLIENT_SECRET', ''),
    'paypal_mode' => env('PAYPAL_MODE', 'sandbox'),
    'paypal_currency' => env('PAYPAL_CURRENCY', 'USD'),
    'donation_amounts' => env('DONATION_AMOUNTS', '5,10,25,50,100'),
    'donation_enabled' => env('DONATION_ENABLED', false),
];
