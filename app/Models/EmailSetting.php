<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailSetting extends Model
{
    use HasFactory;

    // Define the table name
    protected $table = 'email_settings';

    // Define which fields are mass assignable
    protected $fillable = [
        'provider',
        'host',
        'port',
        'username',
        'password',
        'encryption',
        'from_address',
        'from_name',
        'enabled',
    ];

}
