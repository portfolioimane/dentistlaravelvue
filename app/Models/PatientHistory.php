<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PatientHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'treatment_date',
        'treatment_details',
        'dentist_name',
        'treatment_type',
        'treatment_cost',
        'amount_paid',
        'remaining_balance',
        'prescriptions',
        'follow_up_instructions',
        'is_completed',
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }
}
