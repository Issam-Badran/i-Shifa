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
        'age',
        'height',
        'weight',
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

    public function walletTransactions()
    {
        return $this->hasMany(WalletTransaction::class);
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

    public function addBalance(float $amount): void
    {
        $this->balance += $amount;
        $this->save();
    }

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
