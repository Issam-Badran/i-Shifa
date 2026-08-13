<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    protected $fillable = [
        'doctor_id',
        'patient_id',
        'date',
        'start_time',
        'end_time',
        'status',
        'symptoms',
        'diagnosis',
        'prescription',
        'notes',
    ];


    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function overlaps($start, $end)
    {
        return $this->start_time < $end && $this->end_time > $start;
    }

    protected static function booted()
{
    static::saving(function ($appointment) {
        if ($appointment->start_time && $appointment->end_time) {
            $start = Carbon::parse($appointment->start_time);
            $end = Carbon::parse($appointment->end_time);
            $appointment->duration = $start->diffInMinutes($end);
        }
    });
}

}

