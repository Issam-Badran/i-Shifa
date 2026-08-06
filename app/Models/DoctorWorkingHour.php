<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DoctorWorkingHour extends Model
{
    protected $fillable = [
        'doctor_id',
        'day_of_week',
        'start_time',
        'end_time',
        'is_active',
    ];

    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }

    public function workingHours()
{
    return $this->hasMany(DoctorWorkingHour::class);
}

}
