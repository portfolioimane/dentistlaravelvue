<?php
namespace App\Jobs;

use App\Mail\BookingReminder;
use App\Models\Booking;
use App\Models\EmailSetting; // Import EmailSetting model
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Services\TwilioService; // Import TwilioService
use Carbon\Carbon; // Make sure Carbon is imported
use Illuminate\Support\Facades\Log; // Import the Log facade
use Illuminate\Support\Facades\Config; // Import Config facade
use Illuminate\Support\Facades\Artisan;

class SendBookingReminder implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $booking;

    /**
     * Create a new job instance.
     *
     * @param  \App\Models\Booking  $booking
     * @return void
     */
    public function __construct(Booking $booking)
    {
        $this->booking = $booking;
    }

    /**
     * Execute the job.
     *
     * @return void
     **/
public function handle(TwilioService $twilioService)
{
    // Fetch email settings from the database
    $setting = EmailSetting::first(); // Assuming you are fetching a single record

    // Log the settings before updating configurations
    Log::debug('Email settings from DB:', [
        'provider' => $setting->provider,
        'host' => $setting->host,
        'port' => $setting->port,
        'username' => $setting->username,
        'password' => $setting->password,
        'encryption' => $setting->encryption,
        'from_address' => $setting->from_address,
        'from_name' => $setting->from_name,
    ]);

    // Clear the configuration cache
    Artisan::call('config:clear');

    // Ensure transport is set properly
    $mailer = $setting->provider;
    Config::set('mail.default', $mailer);
    Config::set("mail.mailers.{$mailer}.transport", $mailer);
    Config::set("mail.mailers.{$mailer}.host", $setting->host);
    Config::set("mail.mailers.{$mailer}.port", $setting->port);
    Config::set("mail.mailers.{$mailer}.username", $setting->username);
    Config::set("mail.mailers.{$mailer}.password", $setting->password);
    Config::set("mail.mailers.{$mailer}.encryption", $setting->encryption);
    Config::set("mail.from.address", $setting->from_address);
    Config::set("mail.from.name", $setting->from_name);

    // Log the updated mail configuration after applying the changes
    Log::debug('Updated mail conf:', [
        'mail.default' => Config::get('mail.default'),
        'mail.mailers' => Config::get('mail.mailers'),
        'mail.from' => Config::get('mail.from'),
    ]);

    // Send the email and SMS reminder
    $bookingDate = Carbon::parse($this->booking->date);
    $startTime = Carbon::parse($this->booking->start_time);
    $endTime = Carbon::parse($this->booking->end_time);

    $formattedDate = $bookingDate->format('Y-m-d');
    $formattedStartTime = $startTime->format('H:i:s');
    $formattedEndTime = $endTime->format('H:i:s');

    Mail::to($this->booking->email)->send(new BookingReminder($this->booking));

    $message = "Reminder: You have a dental appointment scheduled for {$formattedDate} from {$formattedStartTime} to {$formattedEndTime}. Your booked service is: {$this->booking->service->name}. See you soon!";
    $twilioService->sendSms($this->booking->phone, $message);

    \Log::info('SMS sent to ' . $this->booking->phone);
}


}

