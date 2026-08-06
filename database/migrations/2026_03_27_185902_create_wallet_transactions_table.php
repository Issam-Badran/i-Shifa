<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('wallet_transactions', function (Blueprint $table) {
            $table->id();

            // Foreign keys
            $table->foreignId('patient_id')->nullable()->constrained('patients')->onDelete('cascade');
            $table->foreignId('doctor_id')->nullable()->constrained('doctors')->onDelete('cascade');

            // Optional: link to appointment
            $table->foreignId('appointment_id')->nullable()->constrained('appointments')->onDelete('cascade');

            // Amount of money moved
            $table->decimal('amount', 12, 2);

            // Type of transaction
            $table->enum('type', [
                'withdraw',       // patient pays for appointment
                'deposit',        // doctor receives money
                'topup',          // patient adds money to wallet
                'platform_fee',   // platform takes 10%
            ]);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('wallet_transactions');
    }
};
