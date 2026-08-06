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
        'consultation_fee',
        'doctor_share',
        'total_earnings',
        'appointments_count',
        'balance',
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

    public function walletTransactions()
    {
        return $this->hasMany(WalletTransaction::class);
    }

    public function workingHours()
    {
        return $this->hasMany(DoctorWorkingHour::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Business Logic
    |--------------------------------------------------------------------------
    */

    /**
     * Set consultation fee and compute doctor share (90%).
     */
    public function setConsultationFee(float $fee): void
    {
        $this->consultation_fee = $fee;

        // Doctor receives 90%
        $this->doctor_share = $fee * 0.90;

        $this->save();
    }

    /**
     * Register a completed appointment:
     * - increment appointments_count
     * - increase total_earnings
     * - increase balance
     * - record platform fee (10%)
     */
    public function registerCompletedAppointment(): void
    {
        $doctorShare = $this->consultation_fee * 0.90;
        $platformFee = $this->consultation_fee * 0.10;

        $this->appointments_count += 1;
        $this->total_earnings += $doctorShare;
        $this->balance += $doctorShare;

        $this->save();

        // Record platform fee transaction
        $this->walletTransactions()->create([
            'doctor_id' => $this->id,
            'amount' => $platformFee,
            'type' => 'platform_fee',
        ]);
    }
}
