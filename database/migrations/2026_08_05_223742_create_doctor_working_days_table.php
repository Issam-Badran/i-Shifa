<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('doctor_working_days', function (Blueprint $table) {
            $table->id();
            $table->foreignId('doctor_id')->constrained('doctors')->onDelete('cascade');

            // 0 = Sunday, 1 = Monday, ... 6 = Saturday
            $table->unsignedTinyInteger('day_of_week');

            // Whether the doctor works on this day
            $table->boolean('is_open')->default(true);

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('doctor_working_days');
    }
};
