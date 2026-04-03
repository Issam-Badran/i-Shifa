<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Patient extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'balance',
        'appointments_count',
        'cancellations_count',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function appointments()
{
    return $this->hasMany(Appointment::class);
}

public function WalletTransaction ()
{
    return $this->hasMany(WalletTransaction::class);
}

    /**
     * Register a successful appointment.
     */
    public function registerAppointment(): void
    {
        $this->appointments_count += 1;
        $this->save();
    }

    /**
     * Register a cancellation.
     */
    public function registerCancellation(): void
    {
        $this->cancellations_count += 1;
        $this->save();
    }

    /**
     * Add money to patient balance.
     */
    public function addBalance(float $amount): void
    {
        $this->balance += $amount;
        $this->save();
    }

    /**
     * Deduct money from patient balance.
     */
    public function deductBalance(float $amount): void
    {
        $this->balance -= $amount;
        $this->save();
    }

    public function payForAppointment(float $amount): void
{
    $this->deductBalance($amount);
}

}
