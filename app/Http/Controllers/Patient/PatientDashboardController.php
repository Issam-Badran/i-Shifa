<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Appointment;
use Carbon\Carbon;

class PatientDashboardController extends Controller
{
    public function index(Request $request)
    {
        $patient = $request->user()->patient;

        // Last 3 consultations (completed or cancelled or booked)
        $lastConsultations = Appointment::where('patient_id', $patient->id)
            ->with(['doctor.user'])
            ->orderBy('date', 'desc')
            ->orderBy('start_time', 'desc')
            ->take(3)
            ->get()
            ->map(function ($appointment) {

                // Status label (Arabic)
                $statusLabel = match ($appointment->status) {
                    'completed' => 'مكتملة',
                    'cancelled' => 'ملغاة',
                    'booked' => 'قيد الانتظار',
                    default => 'غير معروف'
                };

                return [
                    'id' => $appointment->id,
                    'doctor_name' => $appointment->doctor->user->first_name . ' ' . $appointment->doctor->user->last_name,
                    'doctor_specialization' => $appointment->doctor->specialization,
                    'date' => $appointment->date,
                    'start_time' => Carbon::parse($appointment->start_time)->format('H:i'),
                    'status' => $statusLabel,
                ];
            });

        return response()->json([
            'status' => 'success',
            'last_consultations' => $lastConsultations
        ]);
    }
}
