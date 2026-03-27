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
        'clinic_start_time',
        'clinic_end_time',
        'consultation_fee',
        'doctor_share',
        'total_earnings',
        'appointments_count',
        'balance',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function appointments()
{
    return $this->hasMany(Appointment::class);
}


    /**
     * Set consultation fee and automatically compute doctor_share.
     */
    public function setConsultationFee(float $fee): void
    {
        $this->consultation_fee = $fee;
        $this->doctor_share = $fee / 2;
        $this->save();
    }

    /**
     * Register a completed appointment:
     * - increment appointments_count
     * - increase total_earnings
     * - increase balance
     */
    public function registerCompletedAppointment(): void
    {
        $this->appointments_count += 1;
        $this->total_earnings += $this->doctor_share;
        $this->balance += $this->doctor_share;
        $this->save();
    }
}
