<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\WalletTransaction;

class WalletTransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    use WithoutModelEvents;

public function run(): void
{
    WalletTransaction::create([
        'patient_id' => 1,
        'doctor_id' => 1,
        'appointment_id' => 1,
        'amount' => 10000,
        'type' => 'withdraw',
    ]);
}

}
