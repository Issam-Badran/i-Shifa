<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Doctor extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',

        // Basic info
        'specialization',
        'sub_specialization',
        'experience_years',
        'languages',
        'phone',
        
        // Professional info
        'license_number',
        'degree',
        'university',
        'bio',
        'degree_file',

        // Stats
        'appointments_count',


        // Approval status
        'status',
    ];


    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */


    protected static function booted()
{
    static::created(function ($doctor) {
        // Create 7 days (Saturday → Friday)
        for ($i = 0; $i < 7; $i++) {
            \App\Models\DoctorWorkingDay::create([
                'doctor_id'   => $doctor->id,
                'day_of_week' => $i,
                'is_open'     => false,
            ]);
        }
    });
}


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
