<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Appointment extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'doctor_id',
        'appointment_datetime',
        'status',
        'ai_report',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }

    public function walletTransactions()
    {
        return $this->hasMany(WalletTransaction::class);
    }

    public function medicalReport()
    {
        return $this->hasOne(MedicalReport::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Business Logic
    |--------------------------------------------------------------------------
    */

    /**
     * Mark appointment as completed.
     */
    public function markCompleted(): void
    {
        $this->status = 'completed';
        $this->save();

        $doctorShare = $this->doctor->consultation_fee * 0.90;
        $platformFee = $this->doctor->consultation_fee * 0.10;

        // Doctor stats
        $this->doctor->registerCompletedAppointment();

        // Patient stats
        $this->patient->registerAppointment();

        // Wallet transactions
        $this->walletTransactions()->create([
            'patient_id' => $this->patient_id,
            'doctor_id' => $this->doctor_id,
            'amount' => $platformFee,
            'type' => 'platform_fee',
        ]);
    }

    /**
     * Cancel appointment by patient.
     * Refund full consultation fee.
     */
    public function cancelByPatient(): void
    {
        $refundAmount = $this->doctor->consultation_fee;

        // Refund patient fully
        $this->patient->addBalance($refundAmount);

        // Update stats
        $this->patient->registerCancellation();

        $this->status = 'cancelled';
        $this->save();
    }

    /**
     * Cancel appointment by doctor.
     * Refund full consultation fee to patient.
     */
    public function cancelByDoctor(): void
    {
        $refundAmount = $this->doctor->consultation_fee;

        // Refund patient fully
        $this->patient->addBalance($refundAmount);

        // No penalty for doctor unless you want one

        $this->status = 'cancelled';
        $this->save();
    }
}
