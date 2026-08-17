<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AIConsultation extends Model
{
    protected $table = 'ai_consultations';

    protected $fillable = [
        'patient_id',
        'diagnosis',
        'suggested_specialization',
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }
}
