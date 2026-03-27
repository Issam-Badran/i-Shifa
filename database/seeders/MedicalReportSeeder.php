<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\MedicalReport;

class MedicalReportSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    use WithoutModelEvents;

public function run(): void
{
    MedicalReport::create([
        'appointment_id' => 1,
        'ai_report' => 'AI diagnosis example',
        'doctor_report' => 'Doctor final report example',
        'prescription' => 'Take medication X twice daily',
        'is_encrypted' => true,
    ]);
}

}
