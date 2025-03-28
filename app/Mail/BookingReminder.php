<?php
namespace App\Mail;

use App\Models\Booking;
use Illuminate\Mail\Mailable;

class BookingReminder extends Mailable
{
    public $booking;

    public function __construct(Booking $booking)
    {
        $this->booking = $booking;
    }

    public function build()
    {
        return $this->subject('Booking Reminder')
                    ->view('emails.booking_reminder')
                    ->with([
                        'booking' => $this->booking,
                    ]);
    }
}
