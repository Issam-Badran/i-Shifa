<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('doctors', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            // Basic info
            $table->string('specialization');
            $table->string('degree_file')->nullable();

            // Single consultation fee
            $table->decimal('consultation_fee', 10, 2)->default(0);

            // Earnings
            $table->decimal('doctor_share', 10, 2)->default(0);
            $table->decimal('total_earnings', 12, 2)->default(0);
            $table->unsignedInteger('appointments_count')->default(0);
            $table->decimal('balance', 12, 2)->default(0);

            // Availability
            $table->boolean('is_available')->default(true);

            // Approval status
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('doctors');
    }
};
