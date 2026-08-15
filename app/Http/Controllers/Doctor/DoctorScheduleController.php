<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\DoctorWorkingDay;
use App\Models\DoctorTimeSlot;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DoctorScheduleController extends Controller
{
    public function index(Request $request)
    {
        $doctor = $request->user()->doctor;

        if (!$doctor) {
            return response()->json([
                'status' => 'error',
                'message' => 'Doctor profile not found'
            ], 404);
        }

        // Fetch working days (same logic as DoctorWorkingDaysController)
        $workingDays = DoctorWorkingDay::where('doctor_id', $doctor->id)
            ->orderBy('day_of_week')
            ->get();

        // Fetch time slots (same logic as DoctorTimeSlotsController)
        $timeSlots = DoctorTimeSlot::where('doctor_id', $doctor->id)
            ->orderBy('day_of_week')
            ->orderBy('start_time')
            ->get()
            ->map(function ($slot) {
                return [
                    'id' => $slot->id,
                    'day_of_week' => $slot->day_of_week,
                    'start_time' => Carbon::parse($slot->start_time)->format('H:i'),
                    'end_time' => Carbon::parse($slot->end_time)->format('H:i'),
                ];
            });

        // Arabic day names
        $weekNames = [
            "السبت",
            "الأحد",
            "الإثنين",
            "الثلاثاء",
            "الأربعاء",
            "الخميس",
            "الجمعة"
        ];

        // Build final schedule array
        $schedule = [];

        foreach ($workingDays as $day) {
            $schedule[] = [
                'day_of_week' => $day->day_of_week,
                'day_name'    => $weekNames[$day->day_of_week],
                'is_open'     => $day->is_open,
                'slots'       => $timeSlots->where('day_of_week', $day->day_of_week)->values()
            ];
        }

        return response()->json([
            'status'   => 'success',
            'schedule' => $schedule
        ]);
    }
}
