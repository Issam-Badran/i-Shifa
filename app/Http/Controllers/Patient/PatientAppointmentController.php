<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\WalletTransaction;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PatientAppointmentController extends Controller
{
    public function book(Request $request)
{
}

public function cancel(Request $request, Appointment $appointment)
{ 
}

    /**
     * Show ALL my appointments
     */
    public function index(Request $request)
    {
        $patient = $request->user()->patient;

        $appointments = Appointment::with(['doctor.user'])
            ->where('patient_id', $patient->id)
            ->orderBy('appointment_datetime', 'desc')
            ->get()
            ->map(function ($appointment) {
                return [
                    'id' => $appointment->id,
                    'doctor_name' => $appointment->doctor->user->first_name . ' ' . $appointment->doctor->user->last_name,
                    'status' => $appointment->status,
                    'appointment_datetime' => $appointment->appointment_datetime,
                    'created_at' => $appointment->created_at,
                ];
            });

        return response()->json([
            'status' => 'success',
            'appointments' => $appointments
        ]);
    }

    /**
     * Show a single appointment
     */
    public function show(Request $request, Appointment $appointment)
    {
        $appointment->load(['doctor.user', 'patient.user']);

        // Ensure this appointment belongs to the patient
        if ($appointment->patient_id !== $request->user()->patient->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        return response()->json([
            'status' => 'success',
            'appointment' => [
                'id' => $appointment->id,
                'doctor_name' => $appointment->doctor->user->first_name . ' ' . $appointment->doctor->user->last_name,
                'status' => $appointment->status,
                'appointment_datetime' => $appointment->appointment_datetime,
                'created_at' => $appointment->created_at,
            ]
        ]);
    }


}
