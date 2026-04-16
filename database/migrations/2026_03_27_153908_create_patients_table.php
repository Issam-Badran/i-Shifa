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
    Schema::create('patients', function (Blueprint $table) {
        $table->id();

        $table->foreignId('user_id')->constrained()->onDelete('cascade');

        $table->decimal('balance', 12, 2)->default(0);
        $table->integer('age')->default(0);
        $table->integer('height')->default(0);
        $table->integer('weight')->default(0);

        $table->unsignedInteger('appointments_count')->default(0);
        $table->unsignedInteger('cancellations_count')->default(0);

        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patients');
    }

    
};
