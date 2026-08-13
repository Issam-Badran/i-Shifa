<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PlatformSetting;

class PlatformSettingsSeeder extends Seeder
{
    public function run(): void
    {
        PlatformSetting::create([
            'site_name' => 'iShifa - منصة الاستشارات الطبية',
            'contact_email' => 'info@ishifa.com',
            'contact_phone' => '+963 11 123 4567',
            'address' => 'اللاذقية، سوريا',
            'enable_notifications' => true,
            'email_notifications' => true,
            'sms_notifications' => false,
            'maintenance_mode' => false,
            'allow_registration' => true,
            'doctor_approval_required' => true,
        ]);
    }
}
