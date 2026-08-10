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
        'appointments_count' => 0,
        'status' => 'approved',
    ]);
}

}
