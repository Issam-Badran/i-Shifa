<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlatformSetting extends Model
{
    protected $fillable = [
        'site_name',
        'contact_email',
        'contact_phone',
        'address',
        'enable_notifications',
        'email_notifications',
        'sms_notifications',
        'maintenance_mode',
        'allow_registration',
        'doctor_approval_required',
    ];
}
