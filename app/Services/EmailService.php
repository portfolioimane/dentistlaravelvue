<?php
namespace App\Services;

use App\Models\EmailSetting;
use Illuminate\Support\Facades\Config;

class EmailService
{
    public function updateMailConfig()
    {
        // Retrieve the first email setting
        $setting = EmailSetting::first();

        if ($setting) {
            // Set the mailer configuration dynamically based on the database settings
            Config::set('mail.mailers.' . $setting->provider . '.host', $setting->host);
            Config::set('mail.mailers.' . $setting->provider . '.port', $setting->port);
            Config::set('mail.mailers.' . $setting->provider . '.username', $setting->username);
            Config::set('mail.mailers.' . $setting->provider . '.password', $setting->password);
            Config::set('mail.mailers.' . $setting->provider . '.encryption', $setting->encryption);
            Config::set('mail.from.address', $setting->from_address);
            Config::set('mail.from.name', $setting->from_name);

            // Dynamically change the default mailer provider based on the database value
            Config::set('mail.default', $setting->provider);
        }
    }
}
