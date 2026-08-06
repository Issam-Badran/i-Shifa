<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('doctor_working_hours', function (Blueprint $table) {
            $table->id();

            // Link to doctor
            $table->foreignId('doctor_id')->constrained()->onDelete('cascade');

            // 0 = Sunday, 1 = Monday, ... 6 = Saturday
            $table->tinyInteger('day_of_week');

            // Each day can have multiple working periods
            $table->time('start_time');
            $table->time('end_time');

            // Toggle day on/off
            $table->boolean('is_active')->default(true);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('doctor_working_hours');
    }
};
