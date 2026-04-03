<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\WalletTransaction;
use Illuminate\Http\Request;

class AppointmentController extends Controller
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

    if ($user->role === 'patient') {
        $appointment->cancelByPatient();
    }

    if ($user->role === 'doctor') {
        $appointment->cancelByDoctor();
    }

    return response()->json([
        'status' => 'success',
        'message' => 'Appointment cancelled successfully'
    ]);
}


}
