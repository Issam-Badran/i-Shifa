<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('patients', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            $table->string('phone')->nullable();
            $table->string('email')->unique();

            

            // Optional health info
            $table->integer('age')->default(0);
            $table->integer('height')->default(0);
            $table->integer('weight')->default(0);

            // Stats
            $table->unsignedInteger('appointments_count')->default(0);
            $table->unsignedInteger('cancellations_count')->default(0);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('patients');
    }
};
