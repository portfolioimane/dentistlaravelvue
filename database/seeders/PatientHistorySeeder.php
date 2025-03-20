<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PatientHistory;
use App\Models\Patient;

class PatientHistorySeeder extends Seeder
{
    public function run()
    {
        $patient = Patient::first(); // Assuming there is at least one patient

        PatientHistory::create([
            'patient_id' => $patient->id,
            'treatment_date' => '2025-03-05',
            'treatment_details' => 'Dental cleaning and checkup',
            'dentist_name' => 'Dr. Smith',
            'treatment_type' => 'cleaning',
            'treatment_cost' => 150.00,
            'amount_paid' => 150.00,
            'remaining_balance' => 0.00,
            'prescriptions' => 'Use fluoride toothpaste.',
            'follow_up_instructions' => 'No food for 30 minutes.',
            'is_completed' => true,
        ]);
    }
}
