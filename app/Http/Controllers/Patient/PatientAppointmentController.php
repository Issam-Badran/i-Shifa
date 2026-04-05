<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\WalletTransaction;
use Illuminate\Http\Request;

class PatientAppointmentController extends Controller
{
    public function book(Request $request)
{
    $request->validate([
        'doctor_id' => 'required|exists:doctors,id',
        'appointment_datetime' => 'required|date'
    ]);

    $patient = $request->user()->patient;
    $doctor = Doctor::find($request->doctor_id);

    $doctorShare = $doctor->doctor_share; // already computed by model
    $platformFee = 0.5;

    $totalCost = $doctorShare + $platformFee;

    if ($patient->balance < $totalCost) {
        return response()->json([
            'status' => 'error',
            'message' => 'Insufficient balance'
        ], 422);
    }

    // Create appointment
    $appointment = Appointment::create([
        'patient_id' => $patient->id,
        'doctor_id' => $doctor->id,
        'appointment_datetime' => $request->appointment_datetime,
        'status' => 'pending'
    ]);

    // Patient pays
    $patient->payForAppointment($totalCost);

    // Doctor receives his share
    $doctor->addDoctorShare($doctorShare);

    // Doctor pays platform fee
    $doctor->deductPlatformFee($platformFee);

    // Transactions
    $appointment->addTransaction($patient->id, $doctor->id, $doctorShare, 'deposit');
    $appointment->addTransaction($patient->id, null, $platformFee, 'platform_fee');
    $appointment->addTransaction(null, $doctor->id, $platformFee, 'platform_fee');

    return response()->json([
        'status' => 'success',
        'message' => 'Appointment booked successfully',
        'appointment' => $appointment
    ]);
}

public function cancel(Request $request, Appointment $appointment)
{
    $user = $request->user();

    $appointment->cancelByPatient();

    return response()->json([
        'status' => 'success',
        'message' => 'Appointment cancelled successfully'
    ]);
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
     * Show last 3 MY appointments
     */
    public function latest(Request $request)
    {
        $patient = $request->user()->patient;

        $appointments = Appointment::with(['doctor.user'])
            ->where('patient_id', $patient->id)
            ->orderBy('appointment_datetime', 'desc')
            ->take(3)
            ->get()
            ->map(function ($appointment) {
                return [
                    'id' => $appointment->id,
                    'doctor_name' => $appointment->doctor->user->first_name . ' ' . $appointment->doctor->user->last_name,
                    'status' => $appointment->status,
                    'appointment_datetime' => $appointment->appointment_datetime,
                    'time_since_started' => Carbon::parse($appointment->appointment_datetime)->diffForHumans(),
                ];
            });

        return response()->json([
            'status' => 'success',
            'latest_appointments' => $appointments
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
