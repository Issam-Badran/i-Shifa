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
}
