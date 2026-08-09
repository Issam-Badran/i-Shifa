<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Doctor extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'specialization',
        'degree_file',
        'phone',
        'email',
        'consultation_fee',
        'doctor_share',
        'total_earnings',
        'appointments_count',
        
        'is_available',
        'status',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }


    

    public function workingHours()
    {
        return $this->hasMany(DoctorWorkingDay::class);
    }

    
    


    

    
}
