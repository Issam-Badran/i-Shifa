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
        Schema::table('doctors', function (Blueprint $table) {

            // Professional info
            $table->string('sub_specialization')->nullable();
            $table->unsignedInteger('experience_years')->nullable();
            $table->string('languages')->nullable(); // comma-separated: "Arabic,English"
            $table->string('license_number')->nullable();
            $table->string('degree')->nullable(); // e.g., "PhD in Cardiology"
            $table->string('university')->nullable();
            $table->text('bio')->nullable();


            
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('doctors', function (Blueprint $table) {
            //
        });
    }
};
