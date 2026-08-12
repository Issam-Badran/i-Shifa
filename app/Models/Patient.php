<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Patient extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'phone',
        'age',
        'height',
        'weight',
        'blood_type',
        'gender',
        'appointments_count',
        'cancellations_count',
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

    

    /*
    |--------------------------------------------------------------------------
    | Business Logic
    |--------------------------------------------------------------------------
    */

    public function registerAppointment(): void
    {
        $this->appointments_count += 1;
        $this->save();
    }

    public function registerCancellation(): void
    {
        $this->cancellations_count += 1;
        $this->save();
    }

  
}
