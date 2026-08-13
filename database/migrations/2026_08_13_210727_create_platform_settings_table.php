<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('platform_settings', function (Blueprint $table) {
            $table->id();

            // General settings
            $table->string('site_name')->default('iShifa - منصة الاستشارات الطبية');
            $table->string('contact_email')->nullable();
            $table->string('contact_phone')->nullable();
            $table->string('address')->nullable();

            // Notifications
            $table->boolean('enable_notifications')->default(true);
            $table->boolean('email_notifications')->default(true);
            $table->boolean('sms_notifications')->default(false);

            // Security & permissions
            $table->boolean('maintenance_mode')->default(false);
            $table->boolean('allow_registration')->default(true);
            $table->boolean('doctor_approval_required')->default(true);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('platform_settings');
    }
};
