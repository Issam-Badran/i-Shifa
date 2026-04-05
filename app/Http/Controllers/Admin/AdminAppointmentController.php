<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\WalletTransaction;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AdminAppointmentController extends Controller
{
  /**
     * Show ALL appointments (with doctor & patient names)
     */
    public function index()
    {
        $appointments = Appointment::with(['doctor.user', 'patient.user'])
            ->orderBy('appointment_datetime', 'desc')
            ->get()
            ->map(function ($appointment) {
                return [
                    'id' => $appointment->id,
                    'doctor_name' => $appointment->doctor->user->first_name . ' ' . $appointment->doctor->user->last_name,
                    'patient_name' => $appointment->patient->user->first_name . ' ' . $appointment->patient->user->last_name,
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
     * Show last 3 appointments that are:
     * - completed
     * - OR currently happening (appointment_datetime <= now)
     * NOT upcoming
     */
    public function latest()
    {
        $now = Carbon::now();

        $appointments = Appointment::with(['doctor.user', 'patient.user'])
            ->where(function ($query) use ($now) {
                $query->where('status', 'completed')
                      ->orWhere('appointment_datetime', '<=', $now);
            })
            ->orderBy('appointment_datetime', 'desc')
            ->take(3)
            ->get()
            ->map(function ($appointment) {
                return [
                    'id' => $appointment->id,
                    'doctor_name' => $appointment->doctor->user->first_name . ' ' . $appointment->doctor->user->last_name,
                    'patient_name' => $appointment->patient->user->first_name . ' ' . $appointment->patient->user->last_name,
                    'status' => $appointment->status,
                    'time_since_started' => Carbon::parse($appointment->appointment_datetime)->diffForHumans(),
                ];
            });

        return response()->json([
            'status' => 'success',
            'latest_appointments' => $appointments
        ]);
    }

    /**
     * Show single appointment
     */
    public function show(Appointment $appointment)
    {
        $appointment->load(['doctor.user', 'patient.user']);

        return response()->json([
            'status' => 'success',
            'appointment' => [
                'id' => $appointment->id,
                'doctor_name' => $appointment->doctor->user->first_name . ' ' . $appointment->doctor->user->last_name,
                'patient_name' => $appointment->patient->user->first_name . ' ' . $appointment->patient->user->last_name,
                'status' => $appointment->status,
                'appointment_datetime' => $appointment->appointment_datetime,
                'created_at' => $appointment->created_at,
            ]
        ]);
    }

}
