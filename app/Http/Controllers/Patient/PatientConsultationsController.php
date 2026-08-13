<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Appointment;
use Carbon\Carbon;

class PatientConsultationsController extends Controller
{
    public function index(Request $request)
    {
        $patient = $request->user()->patient;

        // Fetch ALL consultations for this patient
        $consultations = Appointment::where('patient_id', $patient->id)
            ->with(['doctor.user'])
            ->orderBy('date', 'desc')
            ->orderBy('start_time', 'desc')
            ->get()
            ->map(function ($appointment) {

                // Arabic status label
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
                    'time' => $appointment->start_time,
                    'status' => $statusLabel,
                ];
            });

        return response()->json([
            'status' => 'success',
            'consultations' => $consultations
        ]);
    }
}
