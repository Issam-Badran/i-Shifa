<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('doctor_time_slots', function (Blueprint $table) {
            $table->id();

            $table->foreignId('doctor_id')->constrained('doctors')->onDelete('cascade');

            // The actual calendar date of the slot
            $table->date('date');

            // Slot start time (30-minute fixed slot)
            $table->time('start_time');

            // Whether the slot is available
            $table->boolean('is_available')->default(true);

            // If booked, link to appointment
            $table->foreignId('appointment_id')->nullable()->constrained('appointments')->onDelete('cascade');

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('doctor_time_slots');
    }
};
