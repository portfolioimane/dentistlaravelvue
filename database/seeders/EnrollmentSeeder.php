<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Enrollment;
use App\Models\User;
use App\Models\Course;

class EnrollmentSeeder extends Seeder
{
    /**
     * Seed the application's database with enrollments.
     *
     * @return void
     */
    public function run()
    {
        // Get the first user and the first course
        $user = User::first();  // Assuming there's at least one user in the database
        $course = Course::first();  // Assuming there's at least one course in the database

        // Enroll the user in the course with additional data
        Enrollment::create([
            'user_id' => $user->id,
            'course_id' => $course->id,
            'name' => $user->name,  // Assuming the user model has a name field
            'email' => $user->email,
            'phone' => $user->phone ?? '000-000-0000',  // Assuming the user model has a phone field, defaulting to a placeholder if not
            'address' => $user->address ?? '123 Main Street, City, Country',  // Default address if not provided
            'payment_method' => 'credit_card',  // Assuming a default payment method
            'total' => 100.00,  // Example total amount for enrollment
            'status' => 'pending',  // Default status
        ]);
    }
}
