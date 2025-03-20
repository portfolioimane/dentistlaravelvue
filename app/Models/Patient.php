<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    use HasFactory;

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone',
        'address',
        'date_of_birth',
        'gender',
        'emergency_contact',
        'medical_history',
        'is_insured',
        'insurance_provider',
        'insurance_id',
    ];

    public function histories()
    {
        return $this->hasMany(PatientHistory::class);
    }
}
