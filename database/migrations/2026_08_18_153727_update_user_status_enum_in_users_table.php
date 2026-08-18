<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Change enum values
            $table->enum('status', ['active', 'banned', 'pending'])
                  ->default('active')
                  ->change();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Rollback to old values
            $table->enum('status', ['active', 'banned', 'suspended'])
                  ->default('active')
                  ->change();
        });
    }
};
