<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Appointment;
use App\Models\Patient;
use Carbon\Carbon;

class DoctorDashboardController extends Controller
{
    public function index(Request $request)
    {
        // dd($request);
        $doctor = $request->user()->doctor;

        $today = Carbon::today();
        $startOfWeek = Carbon::now()->startOfWeek(Carbon::SATURDAY);
        $endOfWeek = Carbon::now()->endOfWeek(Carbon::FRIDAY);

        /**
         * 1️⃣ Consultations Today
         */
        $consultationsToday = Appointment::where('doctor_id', $doctor->id)
            ->where('date', $today->toDateString())
            ->whereIn('status', ['booked', 'completed'])
            ->count();


        // Yesterday
        $consultationsYesterday = Appointment::where('doctor_id', $doctor->id)
            ->where('date', $today->copy()->subDay()->toDateString())
            ->where('status', 'completed')
            ->count();

        $consultationsTodayChange = $consultationsToday - $consultationsYesterday;

        /**
         * 2️⃣ Total Patients + New Patients Today
         */
        $totalPatients = Appointment::where('doctor_id', $doctor->id)
            ->where('status', 'completed')
            ->distinct('patient_id')
            ->count('patient_id');


        $patientsCompletedThisWeek = Appointment::where('doctor_id', $doctor->id)
            ->where('status', 'completed')
            ->whereBetween('date', [
                $startOfWeek->toDateString(),
                $endOfWeek->toDateString()
            ])
            ->pluck('patient_id');

        $newPatientsThisWeek = Appointment::whereIn('patient_id', $patientsCompletedThisWeek)
            ->where('doctor_id', $doctor->id)
            ->where('status', 'completed')
            ->selectRaw('patient_id, MIN(date) as first_date')
            ->groupBy('patient_id')
            ->havingRaw('first_date BETWEEN ? AND ?', [
                $startOfWeek->toDateString(),
                $endOfWeek->toDateString()
            ])
            ->count();




        /**
         * 3️⃣ Weekly Consultations Chart
         */
        $weeklyData = [];

        foreach (range(0, 6) as $day) {
            $date = $startOfWeek->copy()->addDays($day)->toDateString();

            $count = Appointment::where('doctor_id', $doctor->id)
                ->where('date', $date)
                ->where('status', 'completed')
                ->count();

            $weeklyData[] = $count;
        }

        /**
         * 4️⃣ Upcoming Consultations
         */
        $upcoming = Appointment::where('doctor_id', $doctor->id)
            ->where('date', $today->toDateString())
            ->where('status', 'booked')
            ->where('start_time', '>', now()->format('H:i'))
            ->orderBy('start_time')
            ->take(10)
            ->get()
            ->map(function ($appointment) {
                return [
                    'patient_name' => $appointment->patient->name,
                    'time' => $appointment->start_time,
                ];
            });

        return response()->json([
            'status' => 'success',
            'data' => [
                'consultations_today' => $consultationsToday,
                'consultations_today_change' => $consultationsTodayChange,

                'total_patients' => $totalPatients,
                'new_patients_this_week' => $newPatientsThisWeek,

                'weekly_consultations' => [
                    'labels' => ['Sat', 'Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri'],
                    'values' => $weeklyData
                ],

                'upcoming_consultations' => $upcoming
            ]
        ]);
    }
}
