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


    /**
     * Mark appointment as completed.
     */
    public function markCompleted(): void
    {
        $this->status = 'completed';
        $this->save();

        // Update doctor stats
        $this->doctor->registerCompletedAppointment();

        // Update patient stats
        $this->patient->registerAppointment();
    }

    /**
     * Cancel appointment.
     */
    public function cancel(): void
    {
        $this->status = 'cancelled';
        $this->save();

        // Update patient cancellation count
        $this->patient->registerCancellation();
    }

    public function addTransaction($patientId, $doctorId, $amount, $type)
{
    $this->walletTransactions()->create([
        'patient_id' => $patientId,
        'doctor_id' => $doctorId,
        'amount' => $amount,
        'type' => $type,
    ]);
}

public function cancelByPatient()
{
    $doctorShare = $this->doctor->doctor_share;
    $platformFee = 0.5;

    // Refund only doctor share
    $this->patient->addBalance($doctorShare);

    // Platform keeps 0.5$

    $this->patient->registerCancellation();

    $this->status = 'cancelled';
    $this->save();
}


public function cancelByDoctor()
{
    $doctorShare = $this->doctor->doctor_share;
    $platformFee = 0.5;

    // Refund patient fully
    $this->patient->addBalance($doctorShare + $platformFee);

    // Doctor pays penalty
    $this->doctor->deductPlatformFee($platformFee);

    // Platform keeps 0.5$

    $this->status = 'cancelled';
    $this->save();
}



}