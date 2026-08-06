<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\MedicalReport;
use Illuminate\Http\Request;

class PatientStatsController extends Controller
{
    public function index(Request $request)
    {
        $patient = $request->user()->patient;

        // ============================
        // 1. Total consultations
        // ============================
        $totalConsultations = Appointment::where('patient_id', $patient->id)->count();

        // ============================
        // 2. Medical reports count
        // ============================
        $medicalReportsCount = MedicalReport::where('patient_id', $patient->id)->count();

        // ============================
        // 3. Patient balance
        // ============================
        $balance = $patient->balance;

        // ============================
        // 4. Upcoming appointments count
        // ============================
        $upcomingAppointments = Appointment::where('patient_id', $patient->id)
            ->where('appointment_datetime', '>', now())
            ->where('status', 'pending')
            ->count();

        return response()->json([
            'status' => 'success',
            'dashboard' => [
                'total_consultations'   => $totalConsultations,
                'medical_reports_count' => $medicalReportsCount,
                'balance'               => $balance,
                'upcoming_appointments' => $upcomingAppointments,
            ]
        ]);
    }
}
