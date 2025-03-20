<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'cost',
        'duration',
        'image', // Add image to fillable properties
        'featured',

    ];

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

      public function reviews()
    {
        return $this->hasMany(Review::class);
    }
}
