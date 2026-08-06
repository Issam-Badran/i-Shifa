<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MedicalReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'appointment_id',
        'patient_id',
        'ai_report',
        'doctor_report',
        'prescription',
        'is_encrypted',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Encryption Logic
    |--------------------------------------------------------------------------
    */

    // Encrypt before saving
    public function setAiReportAttribute($value)
    {
        if ($value === null) {
            $this->attributes['ai_report'] = null;
            return;
        }

        $this->attributes['ai_report'] = $this->is_encrypted ? encrypt($value) : $value;
    }

    public function setDoctorReportAttribute($value)
    {
        if ($value === null) {
            $this->attributes['doctor_report'] = null;
            return;
        }

        $this->attributes['doctor_report'] = $this->is_encrypted ? encrypt($value) : $value;
    }

    public function setPrescriptionAttribute($value)
    {
        if ($value === null) {
            $this->attributes['prescription'] = null;
            return;
        }

        $this->attributes['prescription'] = $this->is_encrypted ? encrypt($value) : $value;
    }

    // Decrypt when reading
    public function getAiReportAttribute($value)
    {
        if ($value === null || !$this->is_encrypted) {
            return $value;
        }

        return decrypt($value);
    }

    public function getDoctorReportAttribute($value)
    {
        if ($value === null || !$this->is_encrypted) {
            return $value;
        }

        return decrypt($value);
    }

    public function getPrescriptionAttribute($value)
    {
        if ($value === null || !$this->is_encrypted) {
            return $value;
        }

        return decrypt($value);
    }
}
