<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PatientHistory;
use App\Models\User;

class PatientHistorySeeder extends Seeder
{
    public function run()
    {
        $user =User::first(); // Assuming there is at least one patient

        PatientHistory::create([
            'user_id' => $user->id,
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
