<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'avatar',
        'phone',
        'date_of_birth',
        'gender',
        'emergency_contact',
        'medical_history',
        'insurance_provider',
        'insurance_id',
        'is_insured',
    ];

    // Define the relationship with the Booking model (for appointments)
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

     public function histories()
    {
        return $this->hasMany(PatientHistory::class);
    }
 
}
