<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Patient;

class PatientSeeder extends Seeder
{
    public function run()
    {
        Patient::create([
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john.doe@example.com',
            'phone' => '1234567890',
            'address' => '123 Main Street, Fez, Morocco',
            'date_of_birth' => '1985-10-10',
            'gender' => 'male',
            'emergency_contact' => 'Jane Doe - 0987654321',
            'medical_history' => 'No significant medical history.',
            'is_insured' => true,
            'insurance_provider' => 'XYZ Insurance',
            'insurance_id' => 'ABC123456',
        ]);
    }
}
