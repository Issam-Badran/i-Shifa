<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Doctor;
use App\Models\Appointment;
use App\Models\DoctorWorkingDay;
use App\Models\DoctorTimeSlot;
use Carbon\Carbon;

class DoctorBookingInfoController extends Controller
{
    public function show(Request $request, $doctorId)
    {
        $doctor = Doctor::with('user')->findOrFail($doctorId);

        $dayNames = [
    0 => 'السبت',
    1 => 'الأحد',
    2 => 'الإثنين',
    3 => 'الثلاثاء',
    4 => 'الأربعاء',
    5 => 'الخميس',
    6 => 'الجمعة',
        ];

        $days = [];
        for ($i = 0; $i < 6; $i++) {

            $date = Carbon::tomorrow()->addDays($i);

            // Correct day-of-week mapping
            $carbonDay = $date->dayOfWeek; // 0=Sun ... 6=Sat
            $dayOfWeek = ($carbonDay + 1) % 7; // Convert to your DB format

            // Check if doctor works this day
            $workingDay = DoctorWorkingDay::where('doctor_id', $doctor->id)
                ->where('day_of_week', $dayOfWeek)
                ->first();

            $isOpen = $workingDay ? $workingDay->is_open : false;

            // Get time slots for this day
            $timeSlots = DoctorTimeSlot::where('doctor_id', $doctor->id)
                ->where('day_of_week', $dayOfWeek)
                ->orderBy('start_time')
                ->get();

            // Get booked appointments for this day
            $booked = Appointment::where('doctor_id', $doctor->id)
                ->where('date', $date->toDateString())
                ->where('status', 'booked')
                ->get();

            // Compute availability
            $slots = $timeSlots->map(function ($slot) use ($booked) {

                $isBooked = $booked->contains(function ($appt) use ($slot) {
                    return !(
                        $appt->end_time <= $slot->start_time ||
                        $appt->start_time >= $slot->end_time
                    );
                });

                return [
                    'id' => $slot->id,
                    'start_time' => Carbon::parse($slot->start_time)->format('H:i'),
                    'end_time' => Carbon::parse($slot->end_time)->format('H:i'),
                    'is_available' => !$isBooked
                ];
            });

            $days[] = [
                'date' => $date->toDateString(),
                'day_of_week' => $dayOfWeek,
                'day_name' => $dayNames[$dayOfWeek],
                'is_open' => $isOpen,
                'slots' => $slots
            ];
        }


        return response()->json([
            'status' => 'success',
            'doctor' => [
                'id' => $doctor->id,
                'first_name' => $doctor->user->first_name,
                'last_name' => $doctor->user->last_name,
                'profile_image' => $doctor->user->profile_image,
                'specialization' => $doctor->specialization,
                'sub_specialization' => $doctor->sub_specialization,
                'bio' => $doctor->bio,
                'latitude' => $doctor->user->latitude,
                'longitude' => $doctor->user->longitude,
            ],
            'days' => $days
        ]);
    }
}
