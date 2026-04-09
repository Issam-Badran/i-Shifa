<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WalletTransaction;

class AdminPaymentsController extends Controller
{
    /**
     * Show all platform payments
     */
    public function index()
    {
        $payments = WalletTransaction::with(['patient.user', 'doctor.user', 'appointment'])
            ->where('type', 'platform_fee')
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($payment) {
                return [
                    'id' => $payment->id,
                    'appointment_id' => $payment->appointment_id,

                    'patient_name' => $payment->patient
                        ? $payment->patient->user->first_name . ' ' . $payment->patient->user->last_name
                        : null,

                    'doctor_name' => $payment->doctor
                        ? $payment->doctor->user->first_name . ' ' . $payment->doctor->user->last_name
                        : null,

                    'amount' => $payment->amount,
                    'type' => $payment->type,
                    'created_at' => $payment->created_at,
                ];
            });

        return response()->json([
            'status' => 'success',
            'payments' => $payments
        ]);
    }
}
