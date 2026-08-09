<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();

            $table->foreignId('patient_id')->constrained('patients')->onDelete('cascade');
            $table->foreignId('doctor_id')->constrained('doctors')->onDelete('cascade');

            // Link to the chosen time slot
            $table->foreignId('time_slot_id')->constrained('doctor_time_slots')->onDelete('cascade');

            $table->enum('status', ['pending', 'cancelled', 'completed'])->default('pending');

            $table->text('ai_report')->nullable();

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('appointments');
    }
};
