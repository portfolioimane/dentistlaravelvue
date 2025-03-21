<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class PatientSeeder extends Seeder
{
    public function run()
    {
        User::create([
            'name' => 'John Doe', // Combine first and last name
            'email' => 'john.doe@example.com',
            'phone' => '1234567890',
            'date_of_birth' => '1985-10-10',
            'gender' => 'male',
            'emergency_contact' => 'Jane Doe - 0987654321',
            'medical_history' => 'No significant medical history.',
            'is_insured' => true,
            'insurance_provider' => 'XYZ Insurance',
            'insurance_id' => 'ABC123456',
            'role' => 'customer', // Set role as customer by default
            'password' => bcrypt('password123'), // Add a default password
        ]);
    }
}
