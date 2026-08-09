<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Appointment extends Model
{
    protected $fillable = [
        'patient_id',
        'doctor_id',
        'time_slot_id',
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

    public function timeSlot()
    {
        return $this->belongsTo(DoctorTimeSlot::class);
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

        // Doctor stats
        $this->doctor->registerCompletedAppointment();

        // Patient stats
        $this->patient->registerAppointment();
    }

    /**
     * Cancel appointment by patient.
     * Refund full consultation fee.
     */
    public function cancelByPatient(): void
    {
  
    
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

        $this->status = 'cancelled';
        $this->save();
    }
}
