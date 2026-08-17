<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\AIConsultation;
use Illuminate\Http\Request;

class AIConsultationController extends Controller
{
    /**
     * List all consultations for the logged-in patient
     */
    public function index(Request $request)
    {
        
        $patient = $request->user()->patient;

        if (!$patient) {
            return response()->json([
                'status' => 'error',
                'message' => 'Patient profile not found'
            ], 404);
        }

        $consultations = AIConsultation::where('patient_id', $patient->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'status' => 'success',
            'consultations' => $consultations
        ]);
    }

    /**
     * Store a new AI consultation
     */
    public function store(Request $request)
    {
        $patient = $request->user()->patient;

        if (!$patient) {
            return response()->json([
                'status' => 'error',
                'message' => 'Patient profile not found'
            ], 404);
        }

        $request->validate([
            'diagnosis' => 'required|string',
            'suggested_specialization' => 'required|string',
        ]);

        $consultation = AIConsultation::create([
            'patient_id' => $patient->id,
            'diagnosis' => $request->diagnosis,
            'suggested_specialization' => $request->suggested_specialization,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Consultation saved successfully',
            'consultation' => $consultation
        ]);
    }

    /**
     * Show one consultation
     */
    public function show(Request $request, $id)
    {
        $patient = $request->user()->patient;

        if (!$patient) {
            return response()->json([
                'status' => 'error',
                'message' => 'Patient profile not found'
            ], 404);
        }

        $consultation = AIConsultation::where('patient_id', $patient->id)
            ->where('id', $id)
            ->first();

        if (!$consultation) {
            return response()->json([
                'status' => 'error',
                'message' => 'Consultation not found'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'consultation' => $consultation
        ]);
    }
}
