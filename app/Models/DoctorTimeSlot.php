<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DoctorTimeSlot extends Model
{
    protected $fillable = [
        'doctor_id',
        'date',
        'start_time',
        'is_available',
        'appointment_id',
    ];

    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }

    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }
}
