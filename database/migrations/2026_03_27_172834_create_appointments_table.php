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
    Schema::create('appointments', function (Blueprint $table) {
        $table->id();

        // Foreign keys
        $table->foreignId('patient_id')->constrained('patients')->onDelete('cascade');
        $table->foreignId('doctor_id')->constrained('doctors')->onDelete('cascade');

        // Appointment date & time
        $table->dateTime('appointment_datetime');

        // Status: pending, cancelled, completed
        $table->enum('status', ['pending', 'cancelled', 'completed'])->default('pending');

        // AI report (nullable)
        $table->text('ai_report')->nullable();

        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};
