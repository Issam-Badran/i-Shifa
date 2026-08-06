<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('medical_reports', function (Blueprint $table) {
            $table->id();

            // Link to appointment
            $table->foreignId('appointment_id')
                  ->constrained('appointments')
                  ->onDelete('cascade');

            // Link to patient
            $table->foreignId('patient_id')
                  ->constrained('patients')
                  ->onDelete('cascade');

            // Encrypted fields
            $table->text('ai_report')->nullable();
            $table->text('doctor_report')->nullable();
            $table->text('prescription')->nullable();

            // Whether fields are encrypted
            $table->boolean('is_encrypted')->default(true);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('medical_reports');
    }
};
