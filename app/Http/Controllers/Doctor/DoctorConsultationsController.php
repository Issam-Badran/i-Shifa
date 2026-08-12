<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Appointment;
use Carbon\Carbon;

class DoctorConsultationsController extends Controller
{
    /**
     * 1️⃣ Consultations List
     */
    public function index(Request $request)
    {
        $doctor = $request->user()->doctor;
        $filter = $request->query('filter'); // completed, cancelled, upcoming, ongoing, all

        $query = Appointment::where('doctor_id', $doctor->id)
            ->with(['patient.user']) // load patient + user (email)
            ->orderBy('date')
            ->orderBy('start_time');

        $today = Carbon::today();
        $now = Carbon::now();

        // Apply filters
        switch ($filter) {
            case 'completed':
                $query->where('status', 'completed');
                break;

            case 'cancelled':
                $query->where('status', 'cancelled');
                break;

            case 'upcoming':
                $query->where('status', 'booked')
                      ->where('date', '>=', $today->toDateString());
                break;

            case 'ongoing':
                $query->where('status', 'booked')
                      ->where('date', $today->toDateString())
                      ->where('start_time', '<=', $now->format('H:i'))
                      ->where('end_time', '>=', $now->format('H:i'));
                break;

            default:
                // all consultations
                break;
        }

        $consultations = $query->get()->map(function ($appointment) use ($now, $today) {

            // Determine status label for UI
            $statusLabel = match ($appointment->status) {
                'completed' => 'مكتملة',
                'cancelled' => 'ملغاة',
                'booked' => $appointment->date == $today->toDateString()
                    ? ($appointment->start_time <= $now->format('H:i') &&
                       $appointment->end_time >= $now->format('H:i')
                        ? 'جارية الآن'
                        : 'قادمة اليوم')
                    : 'قادمة',
                default => 'غير معروف'
            };

            return [
                'id' => $appointment->id,
                'patient_first_name' => $appointment->patient->user->first_name,
                'patient_last_name' => $appointment->patient->user->last_name,
                'status' => $statusLabel,
                'date' => $appointment->date,
                'time' => $appointment->start_time,
                'duration' => $appointment->duration, // NEW
            ];
        });

        return response()->json([
            'status' => 'success',
            'consultations' => $consultations
        ]);
    }

    /**
     * 2️⃣ Consultation Details
     */
    public function show(Request $request, $id)
    {
        // dd($request);
        $doctor = $request->user()->doctor;

        $appointment = Appointment::where('doctor_id', $doctor->id)
            ->where('id', $id)
            ->with(['patient.user']) // load patient + user (email)
            ->firstOrFail();

        $now = Carbon::now();
        $today = Carbon::today();

        // Determine status label
        $statusLabel = match ($appointment->status) {
            'completed' => 'مكتملة',
            'cancelled' => 'ملغاة',
            'booked' => $appointment->date == $today->toDateString()
                ? ($appointment->start_time <= $now->format('H:i') &&
                   $appointment->end_time >= $now->format('H:i')
                    ? 'جارية الآن'
                    : 'قادمة اليوم')
                : 'قادمة',
            default => 'غير معروف'
        };

        return response()->json([
            'status' => 'success',
            'consultation' => [
                'id' => $appointment->id,
                'status' => $statusLabel,
                'date' => $appointment->date,
                'time' => $appointment->start_time,
                'duration' => $appointment->duration,

                // NEW medical fields
                'symptoms' => $appointment->symptoms,
                'diagnosis' => $appointment->diagnosis,
                'prescription' => $appointment->prescription,

                // Patient info
                'patient' => [
                    'id' => $appointment->patient->id,
                    'first_name' => $appointment->patient->user->first_name,
                    'last_name' => $appointment->patient->user->last_name,
                    'age' => $appointment->patient->age,
                    'gender' => $appointment->patient->gender,
                    'blood_type' => $appointment->patient->blood_type,
                    'email' => $appointment->patient->user->email,
                    'phone' => $appointment->patient->phone,
                ]
            ]
        ]);
    }

    public function stats(Request $request)
    {
        
        $doctor = $request->user()->doctor;

        $today = Carbon::today();
        $now = Carbon::now();

        $cancelledCount = Appointment::where('doctor_id', $doctor->id)
            ->where('status', 'cancelled')
            ->count();

        $completedCount = Appointment::where('doctor_id', $doctor->id)
            ->where('status', 'completed')
            ->count();

        $upcomingTodayCount = Appointment::where('doctor_id', $doctor->id)
            ->where('status', 'booked')
            ->where('date', $today->toDateString())
            ->where('start_time', '>', $now->format('H:i'))
            ->count();

        $ongoingNowCount = Appointment::where('doctor_id', $doctor->id)
            ->where('status', 'booked')
            ->where('date', $today->toDateString())
            ->where('start_time', '<=', $now->format('H:i'))
            ->where('end_time', '>=', $now->format('H:i'))
            ->count();

        return response()->json([
            'status' => 'success',
            'stats' => [
                'cancelled' => $cancelledCount,
                'completed' => $completedCount,
                'upcoming_today' => $upcomingTodayCount,
                'ongoing_now' => $ongoingNowCount,
            ]
        ]);
    }

}
