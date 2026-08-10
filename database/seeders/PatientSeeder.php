<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Patient;

class PatientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    use WithoutModelEvents;

public function run(): void
{
    Patient::create([
        'user_id' => 3, // patient user
        'appointments_count' => 0,
        'cancellations_count' => 0,
    ]);
}

}
