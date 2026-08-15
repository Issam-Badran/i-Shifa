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

    $workingDays = DoctorWorkingDay::where('doctor_id', $doctor->id)
        ->orderBy('day_of_week')
        ->get();

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

    $weekNames = [
        "السبت",
        "الأحد",
        "الإثنين",
        "الثلاثاء",
        "الأربعاء",
        "الخميس",
        "الجمعة"
    ];

    $schedule = [];

    foreach ($workingDays as $day) {
        $schedule[] = [
            'id'          => $day->id,  // <-- FIX
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


    public function update(Request $request)
{
    $doctor = $request->user()->doctor;

    if (!$doctor) {
        return response()->json([
            'status' => 'error',
            'message' => 'Doctor profile not found'
        ], 404);
    }

    $request->validate([
    'days' => 'nullable|array',
    'days.*.id' => 'required|integer|exists:doctor_working_days,id',
    'days.*.is_open' => 'required|boolean',

    'slots' => 'nullable|array',

    'slots.*.delete' => 'boolean',

    'slots.*.day_of_week' => 'required_without:slots.*.delete|integer|min:0|max:6',
    'slots.*.start_time' => 'required_without:slots.*.delete|date_format:H:i',
    'slots.*.end_time' => 'required_without:slots.*.delete|date_format:H:i|after:start_time',
]);


    /* -----------------------------------------
     * UPDATE WORKING DAYS
     * ----------------------------------------- */
    foreach ($request->days as $dayData) {
        $day = DoctorWorkingDay::find($dayData['id']);

        if ($day->doctor_id !== $doctor->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $day->is_open = $dayData['is_open'];
        $day->save();
    }

    /* -----------------------------------------
     * UPDATE / DELETE / CREATE TIME SLOTS
     * ----------------------------------------- */
    foreach ($request->slots as $slotData) {

        /* -----------------------------------------
         * DELETE SLOT
         * ----------------------------------------- */
        if (isset($slotData['delete']) && $slotData['delete'] === true) {
            $slot = DoctorTimeSlot::find($slotData['id']);

            if ($slot && $slot->doctor_id === $doctor->id) {
                $slot->delete();
            }

            continue;
        }

        /* -----------------------------------------
         * UPDATE EXISTING SLOT
         * ----------------------------------------- */
        if (isset($slotData['id'])) {
            $slot = DoctorTimeSlot::find($slotData['id']);

            if ($slot->doctor_id !== $doctor->id) {
                return response()->json(['error' => 'Unauthorized'], 403);
            }

            // Prevent overlap
            $overlap = DoctorTimeSlot::where('doctor_id', $doctor->id)
                ->where('day_of_week', $slotData['day_of_week'])
                ->where('id', '!=', $slot->id)
                ->where(function ($q) use ($slotData) {
                    $q->whereBetween('start_time', [$slotData['start_time'], $slotData['end_time']])
                      ->orWhereBetween('end_time', [$slotData['start_time'], $slotData['end_time']])
                      ->orWhere(function ($q2) use ($slotData) {
                          $q2->where('start_time', '<=', $slotData['start_time'])
                             ->where('end_time', '>=', $slotData['end_time']);
                      });
                })
                ->exists();

            if ($overlap) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'This update overlaps with another time slot'
                ], 400);
            }

            $slot->update([
                'start_time' => $slotData['start_time'],
                'end_time' => $slotData['end_time'],
            ]);

            continue;
        }

        /* -----------------------------------------
         * CREATE NEW SLOT (your store() method)
         * ----------------------------------------- */
        $overlap = DoctorTimeSlot::where('doctor_id', $doctor->id)
            ->where('day_of_week', $slotData['day_of_week'])
            ->where(function ($q) use ($slotData) {
                $q->whereBetween('start_time', [$slotData['start_time'], $slotData['end_time']])
                  ->orWhereBetween('end_time', [$slotData['start_time'], $slotData['end_time']])
                  ->orWhere(function ($q2) use ($slotData) {
                      $q2->where('start_time', '<=', $slotData['start_time'])
                         ->where('end_time', '>=', $slotData['end_time']);
                  });
            })
            ->exists();

        if ($overlap) {
            return response()->json([
                'status' => 'error',
                'message' => 'This time slot overlaps with an existing one'
            ], 400);
        }

        DoctorTimeSlot::create([
            'doctor_id' => $doctor->id,
            'day_of_week' => $slotData['day_of_week'],
            'start_time' => $slotData['start_time'],
            'end_time' => $slotData['end_time'],
        ]);
    }

    return response()->json([
        'status' => 'success',
        'message' => 'Schedule updated successfully'
    ]);
}

}
