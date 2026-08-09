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

        // 1. Total consultations (completed only)
        $totalConsultations = Appointment::where('patient_id', $patient->id)
            ->where('status', 'completed')
            ->count();

        // 2. Medical reports count
        $medicalReportsCount = MedicalReport::where('patient_id', $patient->id)->count();

        // 3. Balance
        $balance = $patient->balance;

        // 4. Upcoming appointments
        $upcomingAppointments = Appointment::where('patient_id', $patient->id)
            ->where('appointment_datetime', '>', now())
            ->where('status', 'pending')
            ->count();

        // 5. Last consultations (latest 3 or 5)
        $lastConsultations = Appointment::where('patient_id', $patient->id)
            ->orderBy('appointment_datetime', 'desc')
            ->take(5)
            ->get();

        // 6. Consultation distribution (completed / pending / cancelled)
        $consultationsDistribution = Appointment::where('patient_id', $patient->id)
            ->selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->get();

        return response()->json([
            'status' => 'success',
            'dashboard' => [
                'total_consultations'        => $totalConsultations,
                'medical_reports_count'      => $medicalReportsCount,
                'balance'                    => $balance,
                'upcoming_appointments'      => $upcomingAppointments,
                'last_consultations'         => $lastConsultations,
                'consultations_distribution' => $consultationsDistribution,
            ]
        ]);
    }
}
