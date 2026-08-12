<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Appointment;
use App\Models\Patient;
use Carbon\Carbon;

class DoctorPatientsController extends Controller
{
    public function index(Request $request)
    {
        $doctor = $request->user()->doctor;

        // Get unique patients treated by this doctor
        $patientIds = Appointment::where('doctor_id', $doctor->id)
            ->where('status', 'completed')
            ->distinct('patient_id')
            ->pluck('patient_id');

        $patients = Patient::whereIn('id', $patientIds)
            ->with('user') // email
            ->get()
            ->map(function ($patient) use ($doctor) {

                // Last completed appointment
                $lastVisit = Appointment::where('doctor_id', $doctor->id)
                    ->where('patient_id', $patient->id)
                    ->where('status', 'completed')
                    ->orderBy('date', 'desc')
                    ->first();

                // Next appointment
                $nextAppointment = Appointment::where('doctor_id', $doctor->id)
                    ->where('patient_id', $patient->id)
                    ->where('status', 'booked')
                    ->where('date', '>=', Carbon::today()->toDateString())
                    ->orderBy('date')
                    ->orderBy('start_time')
                    ->first();

                return [
                    'id' => $patient->id,
                    'first_name' => $patient->user->first_name,
                    'last_name' => $patient->user->last_name,
                    'email' => $patient->user->email,
                    'phone' => $patient->phone,
                    'age' => $patient->age,
                    'gender' => $patient->gender,
                    'blood_type' => $patient->blood_type,

                    // Visits
                    'visits_count' => Appointment::where('doctor_id', $doctor->id)
                        ->where('patient_id', $patient->id)
                        ->where('status', 'completed')
                        ->count(),

                    'last_visit_date' => $lastVisit?->date,
                    'last_visit_time' => $lastVisit?->start_time,
                    'last_visit_duration' => $lastVisit?->duration,

                    // Medical fields from last visit
                    'last_symptoms' => $lastVisit?->symptoms,
                    'last_diagnosis' => $lastVisit?->diagnosis,
                    'last_prescription' => $lastVisit?->prescription,

                    // Next appointment
                    'next_appointment' => $nextAppointment ? [
                        'date' => $nextAppointment->date,
                        'time' => $nextAppointment->start_time
                    ] : null,
                ];
            });

        return response()->json([
            'status' => 'success',
            'patients' => $patients
        ]);
    }


    public function show(Request $request, $id)
    {
        $doctor = $request->user()->doctor;

        $patient = Patient::with('user')->findOrFail($id);

        // Ensure this patient belongs to this doctor
        $hasVisited = Appointment::where('doctor_id', $doctor->id)
            ->where('patient_id', $patient->id)
            ->where('status', 'completed')
            ->exists();

        if (!$hasVisited) {
            return response()->json([
                'status' => 'error',
                'message' => 'This patient has no completed visits with this doctor.'
            ], 403);
        }

        // All visits
        $visits = Appointment::where('doctor_id', $doctor->id)
            ->where('patient_id', $patient->id)
            ->orderBy('date', 'desc')
            ->get();

        // Last completed visit
        $lastVisit = $visits->where('status', 'completed')->first();

        // Next appointment
        $nextAppointment = $visits->where('status', 'booked')->first();

        return response()->json([
            'status' => 'success',
            'patient' => [
                'id' => $patient->id,
                'first_name' => $patient->user->first_name,
                'last_name' => $patient->user->last_name,
                'email' => $patient->user->email,
                'phone' => $patient->phone,
                'age' => $patient->age,
                'gender' => $patient->gender,
                'blood_type' => $patient->blood_type,

                'visits_count' => $visits->where('status', 'completed')->count(),
                'last_visit_date' => $lastVisit?->date,
                'last_visit_time' => $lastVisit?->start_time,
                'last_visit_duration' => $lastVisit?->duration,

                // Medical fields from last visit
                'last_symptoms' => $lastVisit?->symptoms,
                'last_diagnosis' => $lastVisit?->diagnosis,
                'last_prescription' => $lastVisit?->prescription,

                'next_appointment' => $nextAppointment ? [
                    'date' => $nextAppointment->date,
                    'time' => $nextAppointment->start_time
                ] : null,

                // Full visit history including medical fields
                'visit_history' => $visits->map(function ($v) {
                    return [
                        'id' => $v->id,
                        'date' => $v->date,
                        'time' => $v->start_time,
                        'duration' => $v->duration,
                        'status' => $v->status,
                        'symptoms' => $v->symptoms,
                        'diagnosis' => $v->diagnosis,
                        'prescription' => $v->prescription,
                    ];
                })
            ]
        ]);
    }


}
