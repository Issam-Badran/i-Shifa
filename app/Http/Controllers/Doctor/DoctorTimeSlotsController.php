<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\DoctorTimeSlot;
use Illuminate\Http\Request;

class DoctorTimeSlotsController extends Controller
{
    /**
     * List all time slots for the doctor
     */
    public function index(Request $request)
    {
        $doctor = $request->user()->doctor;

        $slots = DoctorTimeSlot::where('doctor_id', $doctor->id)
            ->orderBy('day_of_week')
            ->orderBy('start_time')
            ->get();

        return response()->json([
            'status' => 'success',
            'slots' => $slots
        ]);
    }

    /**
     * Add a new time slot (weekly template)
     */
    public function store(Request $request)
    {
        $request->validate([
            'day_of_week' => 'required|integer|min:0|max:6',
            'start_time'  => 'required|date_format:H:i',
            'end_time'    => 'required|date_format:H:i|after:start_time',
        ]);

        $doctor = $request->user()->doctor;

        // Prevent overlap with existing weekly slots
        $overlap = DoctorTimeSlot::where('doctor_id', $doctor->id)
            ->where('day_of_week', $request->day_of_week)
            ->where(function ($q) use ($request) {
                $q->whereBetween('start_time', [$request->start_time, $request->end_time])
                  ->orWhereBetween('end_time', [$request->start_time, $request->end_time])
                  ->orWhere(function ($q2) use ($request) {
                      $q2->where('start_time', '<=', $request->start_time)
                         ->where('end_time', '>=', $request->end_time);
                  });
            })
            ->exists();

        if ($overlap) {
            return response()->json([
                'status' => 'error',
                'message' => 'This time slot overlaps with an existing one'
            ], 400);
        }

        // Create slot
        $slot = DoctorTimeSlot::create([
            'doctor_id'   => $doctor->id,
            'day_of_week' => $request->day_of_week,
            'start_time'  => $request->start_time,
            'end_time'    => $request->end_time,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Time slot added successfully',
            'slot' => $slot
        ]);
    }

    /**
     * Update an existing time slot
     */
    public function update(Request $request, $slotId)
    {
        $request->validate([
            'start_time' => 'required|date_format:H:i',
            'end_time'   => 'required|date_format:H:i|after:start_time',
        ]);

        $slot = DoctorTimeSlot::findOrFail($slotId);

        if ($slot->doctor_id !== $request->user()->doctor->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // Prevent overlap with other weekly slots
        $overlap = DoctorTimeSlot::where('doctor_id', $slot->doctor_id)
            ->where('day_of_week', $slot->day_of_week)
            ->where('id', '!=', $slot->id)
            ->where(function ($q) use ($request) {
                $q->whereBetween('start_time', [$request->start_time, $request->end_time])
                  ->orWhereBetween('end_time', [$request->start_time, $request->end_time])
                  ->orWhere(function ($q2) use ($request) {
                      $q2->where('start_time', '<=', $request->start_time)
                         ->where('end_time', '>=', $request->end_time);
                  });
            })
            ->exists();

        if ($overlap) {
            return response()->json([
                'status' => 'error',
                'message' => 'This update overlaps with another time slot'
            ], 400);
        }

        // Update slot
        $slot->update([
            'start_time' => $request->start_time,
            'end_time'   => $request->end_time,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Time slot updated successfully',
            'slot' => $slot
        ]);
    }

    /**
     * Delete a time slot
     */
    public function destroy(Request $request, $slotId)
    {
        $slot = DoctorTimeSlot::findOrFail($slotId);

        if ($slot->doctor_id !== $request->user()->doctor->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // Safe to delete because appointments store their own date/time
        $slot->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Time slot deleted'
        ]);
    }
}
