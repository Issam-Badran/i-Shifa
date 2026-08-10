<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\DoctorWorkingDay;
use Illuminate\Http\Request;

class DoctorWorkingDaysController extends Controller
{
    /**
     * Get all working days for the doctor
     */
    public function index(Request $request)
    {
        $doctor = $request->user()->doctor;


        $days = DoctorWorkingDay::where('doctor_id', $doctor->id)
            ->orderBy('day_of_week')
            ->get();

        return response()->json([
            'status' => 'success',
            'days' => $days
        ]);
    }

    /**
     * Update a single day (toggle ON/OFF)
     */
    public function update(Request $request, $dayId)
    {

        // dd($request);
        $request->validate([
            'is_open' => 'required|boolean',
        ]);

        $day = DoctorWorkingDay::findOrFail($dayId);

        // Ensure the day belongs to this doctor
        if ($day->doctor_id !== $request->user()->doctor->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $day->is_open = $request->is_open;
        $day->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Working day updated',
            'day' => $day
        ]);
    }

    
}
