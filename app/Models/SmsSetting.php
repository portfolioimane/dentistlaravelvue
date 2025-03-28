<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SmsSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'provider',
        'sid',
        'auth_token',
        'phone_number',
        'enabled',
    ];

    protected $casts = [
        'enabled' => 'boolean',
    ];
}
