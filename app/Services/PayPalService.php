<?php

namespace App\Services;

use App\Models\PaymentSetting;

class PayPalService
{
    /**
     * Get the PayPal configuration from the database.
     *
     * @return array
     */
    public function getPayPalConfig()
    {
        // Retrieve PayPal settings from the database
        $paypalSettings = PaymentSetting::where('provider', 'paypal')
            ->where('enabled', true)
            ->first();

        // Fallback in case PayPal settings are not found in the database
        if ($paypalSettings) {
            $mode = $paypalSettings->mode; // 'sandbox' or 'live'
            $clientId = $paypalSettings->public_key;
            $clientSecret = $paypalSettings->secret_key;
        } else {
            // Default fallback in case the PayPal settings are not found
            $mode = 'sandbox';
            $clientId = '';  // Default empty client id
            $clientSecret = '';  // Default empty client secret
        }

        // Construct the configuration array
        return [
            'mode' => $mode, // 'sandbox' or 'live'
            $mode => [
                'client_id' => $clientId,
                'client_secret' => $clientSecret,
            ],
            'payment_action' => 'Sale', // Default payment action
            'currency' => 'USD', // Default currency
            'notify_url' => '', // You can add a default or leave it empty
            'locale' => 'en_US', // Default locale
            'validate_ssl' => true, // Default SSL validation
        ];
    }
}
