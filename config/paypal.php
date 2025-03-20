<?php

use App\Models\PaymentSetting;

$paypalSettings = PaymentSetting::where('provider', 'paypal')->where('enabled', true)->first();

if ($paypalSettings) {
    $mode = $paypalSettings->mode; // 'sandbox' or 'live'
    $clientId = $paypalSettings->public_key;
    $clientSecret = $paypalSettings->secret_key;
} else {
    // Default fallback in case the PayPal settings are not found
    $mode = 'sandbox';
    $clientId = '';
    $clientSecret = '';
    $appId = 'APP-80W284485P519543T'; // Default sandbox app_id
}

return [
    'mode' => $mode, // 'sandbox' or 'live'
    'sandbox' => [
        'client_id'     => $clientId,
        'client_secret' => $clientSecret,
    ],
    'live' => [
        'client_id'     => $clientId,
        'client_secret' => $clientSecret,
    ],
    'payment_action' => 'Sale', // Default payment action
    'currency'       => 'USD', // Default currency
    'notify_url'     => '', // You can add a default or leave it empty
    'locale'         => 'en_US', // Default locale
    'validate_ssl'   => true, // Default SSL validation
];
