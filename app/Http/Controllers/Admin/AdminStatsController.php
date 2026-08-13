<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Doctor;
use App\Models\Appointment;
use Carbon\Carbon;

class AdminStatsController extends Controller
{
    public function stats()
    {
        $today = Carbon::today();

        // Consultations happening today (based on appointment date)
        $consultationsToday = Appointment::whereDate('date', $today)
            ->whereIn('status', ['booked', 'completed'])
            ->count();

        // Active doctors = doctors with at least 1 appointment in last 30 days
        $activeDoctors = Doctor::whereHas('appointments', function ($q) {
            $q->where('date', '>=', Carbon::now()->subDays(30));
        })->count();

        // Total users
        $totalUsers = User::count();

        // Latest consultations (limit 3)
        $latestConsultations = Appointment::with(['doctor.user', 'patient.user'])
            ->orderBy('created_at', 'desc')
            ->limit(3)
            ->get()
            ->map(function ($appt) {
                return [
                    'patient_name' => $appt->patient->user->first_name . ' ' . $appt->patient->user->last_name,
                    'doctor_name'  => 'د. ' . $appt->doctor->user->first_name . ' ' . $appt->doctor->user->last_name,
                    'time_ago' => $appt->created_at? $appt->created_at->diffForHumans(): 'غير معروف',

                    'status'       => $appt->status,
                ];
            });

        // Latest join requests = doctors with status = pending
        $latestJoinRequests = Doctor::with('user')
            ->where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->limit(3)
            ->get()
            ->map(function ($doc) {
                return [
                    'doctor_name' => 'د. ' . $doc->user->first_name . ' ' . $doc->user->last_name,
                    'specialization' => $doc->specialization,
                    'status' => $doc->status,
                ];
            });

        return response()->json([
            'status' => 'success',
            'stats' => [
                'consultations_today' => $consultationsToday,
                'active_doctors'      => $activeDoctors,
                'total_users'         => $totalUsers,
                'latest_consultations' => $latestConsultations,
                'latest_join_requests' => $latestJoinRequests,
            ]
        ]);
    }
}
