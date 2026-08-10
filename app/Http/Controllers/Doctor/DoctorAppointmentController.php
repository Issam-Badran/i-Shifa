<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DoctorAppointmentController extends Controller
{

    /**
     * Show ALL my appointments
     */
    public function index(Request $request)
    {
        $doctor = $request->user()->doctor;

        $appointments = Appointment::with(['patient.user'])
            ->where('doctor_id', $doctor->id)
            ->orderBy('appointment_datetime', 'desc')
            ->get()
            ->map(function ($appointment) {
                return [
                    'id' => $appointment->id,
                    'patient_name' => $appointment->patient->user->first_name.' '.$appointment->patient->user->last_name,
                    'status' => $appointment->status,
                    'appointment_datetime' => $appointment->appointment_datetime,
                    'created_at' => $appointment->created_at,
                ];
            });

        return response()->json([
            'status' => 'success',
            'appointments' => $appointments,
        ]);
    }

    /**
     * Show upcoming appointments
     */
    public function upcoming(Request $request)
    {
        $doctor = $request->user()->doctor;

        $appointments = Appointment::with(['patient.user'])
            ->where('doctor_id', $doctor->id)
            ->where('appointment_datetime', '>', Carbon::now())
            ->orderBy('appointment_datetime', 'asc')
            ->get()
            ->map(function ($appointment) {
                return [
                    'id' => $appointment->id,
                    'patient_name' => $appointment->patient->user->first_name.' '.$appointment->patient->user->last_name,
                    'status' => $appointment->status,
                    'appointment_datetime' => $appointment->appointment_datetime,
                ];
            });

        return response()->json([
            'status' => 'success',
            'upcoming_appointments' => $appointments,
        ]);
    }
}
