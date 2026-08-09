<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DoctorWorkingDay extends Model
{
    protected $fillable = [
        'doctor_id',
        'day_of_week',
        'is_open',
    ];

    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }
}
