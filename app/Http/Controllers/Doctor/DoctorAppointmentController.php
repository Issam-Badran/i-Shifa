<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Illuminate\Http\Request;

class DoctorAppointmentController extends Controller
{
    public function cancel(Request $request, Appointment $appointment)
    {
        $user = $request->user();

        $appointment->cancelByDoctor();

        return response()->json([
            'status' => 'success',
            'message' => 'Appointment cancelled successfully',
        ]);
    }

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

    /**
     * Accept appointment
     */
    public function accept(Request $request, Appointment $appointment)
    {
        if ($appointment->doctor_id !== $request->user()->doctor->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $appointment->status = 'accepted';
        $appointment->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Appointment accepted',
        ]);
    }

    /**
     * Reject appointment
     */
    public function reject(Request $request, Appointment $appointment)
    {
        if ($appointment->doctor_id !== $request->user()->doctor->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $appointment->status = 'rejected';
        $appointment->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Appointment rejected',
        ]);
    }

    public function complete(Request $request, Appointment $appointment)
    {
        if ($appointment->doctor_id !== $request->user()->doctor->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $appointment->markCompleted();

        return response()->json([
            'status' => 'success',
            'message' => 'Appointment marked as completed',
        ]);
    }
}
