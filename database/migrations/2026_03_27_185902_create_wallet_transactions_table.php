<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('wallet_transactions', function (Blueprint $table) {
        $table->id();

        // Foreign keys
        $table->foreignId('patient_id')->nullable()->constrained('patients')->onDelete('cascade');
        $table->foreignId('doctor_id')->nullable()->constrained('doctors')->onDelete('cascade');

        // Amount of money moved
        $table->decimal('amount', 12, 2);

        // Type of transaction
        // withdraw = money taken from patient
        // deposit = money added to doctor
        // topup = patient adds money to his wallet
        $table->enum('type', ['withdraw', 'deposit', 'topup', 'platform_fee']);

        // Optional: link to appointment
        $table->foreignId('appointment_id')->nullable()->constrained('appointments')->onDelete('cascade');

        

        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wallet_transactions');
    }
};
