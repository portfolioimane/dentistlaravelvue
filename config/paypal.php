<?php
return [
    'mode' => env('PAYPAL_MODE', 'sandbox'), // This can still be used if you have a fallback in .env, but it's optional.
    'sandbox' => [
        'client_id'     => env('PAYPAL_CLIENT_ID', ''),  // Empty or from database
        'client_secret' => env('PAYPAL_CLIENT_SECRET', ''), // Empty or from database
    ],
    'live' => [
        'client_id'     => env('PAYPAL_CLIENT_ID', ''),  // Empty or from database
        'client_secret' => env('PAYPAL_CLIENT_SECRET', ''), // Empty or from database
    ],
    'payment_action' => 'Sale', // Default payment action
    'currency'       => 'USD', // Default currency
    'notify_url'     => '', // Default or custom notify URL
    'locale'         => 'en_US', // Default locale
    'validate_ssl'   => true, // Default SSL validation
];
