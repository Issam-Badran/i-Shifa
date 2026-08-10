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

            $table->unsignedTinyInteger('day_of_week'); // 0–6
            $table->time('start_time');
            $table->time('end_time');

            $table->timestamps();
        });

    }

    public function down()
    {
        Schema::dropIfExists('doctor_time_slots');
    }
};
