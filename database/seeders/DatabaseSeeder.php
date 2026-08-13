<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
{
    $this->call([
        PlatformSettingsSeeder::class,
        UserSeeder::class,
        PatientSeeder::class,
        DoctorSeeder::class,
        AppointmentSeeder::class,
        MedicalReportSeeder::class,
    ]);
}

}
