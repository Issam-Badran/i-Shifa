<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MedicalReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'appointment_id',
        'ai_report',
        'doctor_report',
        'prescription',
        'is_encrypted',
    ];

    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }

    // Encrypt before saving
    public function setAiReportAttribute($value)
    {
        $this->attributes['ai_report'] = encrypt($value);
    }

    public function setDoctorReportAttribute($value)
    {
        $this->attributes['doctor_report'] = encrypt($value);
    }

    public function setPrescriptionAttribute($value)
    {
        $this->attributes['prescription'] = encrypt($value);
    }

    // Decrypt when reading
    public function getAiReportAttribute($value)
    {
        return decrypt($value);
    }

    public function getDoctorReportAttribute($value)
    {
        return decrypt($value);
    }

    public function getPrescriptionAttribute($value)
    {
        return decrypt($value);
    }
}
