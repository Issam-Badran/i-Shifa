<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Doctor;

class DoctorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    use WithoutModelEvents;

public function run(): void
{
    Doctor::create([
        'user_id' => 2, // doctor user
        'specialization' => 'Cardiology',
        'degree_file' => null,
        'clinic_start_time' => '09:00',
        'clinic_end_time' => '17:00',
        'consultation_fee' => 20000,
        'doctor_share' => 10000,
        'total_earnings' => 0,
        'appointments_count' => 0,
        'balance' => 0,
        'status' => 'approved',
    ]);
}

}
