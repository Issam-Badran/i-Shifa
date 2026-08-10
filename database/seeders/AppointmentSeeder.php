<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Appointment;

class AppointmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    use WithoutModelEvents;

public function run(): void
{
    // Appointment::create([
    //     'patient_id' => 1,
    //     'doctor_id' => 1,
    //     'appointment_datetime' => now()->addDay(),
    //     'status' => 'pending',
    //     'ai_report' => null,
    // ]);
}

}
