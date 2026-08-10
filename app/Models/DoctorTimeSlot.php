<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DoctorTimeSlot extends Model
{
    protected $fillable = [
        'doctor_id',
        'day_of_week',
        'start_time',
        'end_time',
    ];
}
