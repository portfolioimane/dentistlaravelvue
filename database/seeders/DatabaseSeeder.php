<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Call the individual seeders
        $this->call([
            UserSeeder::class,
            HomepageHeaderSeeder::class,
            HomepageHeaderSeeder::class,
            PatientSeeder::class,
            PatientHistorySeeder::class,
            ServiceSeeder::class,
            BuisnessHourSeeder::class,
        ]);
    }
}
