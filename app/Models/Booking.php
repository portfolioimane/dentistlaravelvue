<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'email',
        'phone',
        'service_id',
        'date',
        'start_time',
        'end_time',
        'payment_method',
        'total',
        'status',
        'paid_amount',
    ];

    public function service()
    {
        return $this->belongsTo(Service::class); // Defines a many-to-one relationship
    }

      public function user()
    {
        return $this->belongsTo(User::class);
    }

  
}
