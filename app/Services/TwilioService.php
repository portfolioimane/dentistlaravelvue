<?php

namespace App\Services;

use App\Models\SmsSetting;
use Twilio\Rest\Client;
use Illuminate\Support\Facades\Log;

class TwilioService
{
    protected $twilio;
    protected $fromNumber;

    public function __construct()
    {
        // Retrieve Twilio settings from the database
        $smsSettings = SmsSetting::first();

        if ($smsSettings && $smsSettings->enabled) {
            $this->twilio = new Client($smsSettings->sid, $smsSettings->auth_token);
            $this->fromNumber = $smsSettings->phone_number;
        } else {
            Log::error('Twilio settings not found or SMS is disabled.');
        }
    }

    /**
     * Send SMS using Twilio.
     *
     * @param string $to
     * @param string $message
     * @return bool
     */
    public function sendSms($to, $message)
    {
        try {
            if (!$this->twilio || !$this->fromNumber) {
                Log::error('Twilio client is not initialized. Check SMS settings.');
                return false;
            }

            $this->twilio->messages->create($to, [
                'from' => $this->fromNumber,
                'body' => $message,
            ]);

            Log::info("SMS sent successfully to {$to}");

            return true;
        } catch (\Exception $e) {
            Log::error('Twilio error: ' . $e->getMessage());
            return false;
        }
    }
}
