<?php

namespace App\Http\Controllers\Appointment;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\DoctorTimeSlot;
use App\Models\DoctorWorkingDay;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    /**
     * Book an appointment
     */
public function store(Request $request)
{
    // dd($request);
    $request->validate([
        'doctor_id'  => 'required|exists:doctors,id',
        'date'       => 'required|date',
        'start_time' => 'required|date_format:H:i',
        'end_time'   => 'required|date_format:H:i|after:start_time',
    ]);

    $doctorId = $request->doctor_id;
    $date = $request->date;

    // 1. Check doctor works that day
    $carbonDay = Carbon::parse($date)->dayOfWeek; // 0=Sun ... 6=Sat
    $dayOfWeek = ($carbonDay + 1) % 7; // Convert to your DB format

    $workingDay = DoctorWorkingDay::where('doctor_id', $doctorId)
        ->where('day_of_week', $dayOfWeek)
        ->where('is_open', true)
        ->first();


    if (!$workingDay) {
        return response()->json([
            'status' => 'error',
            'message' => 'Doctor does not work on this day'
        ], 400);
    }

    // 2. Check slot exists
    $slot = DoctorTimeSlot::where('doctor_id', $doctorId)
        ->where('day_of_week', $dayOfWeek)
        ->where('start_time', $request->start_time)
        ->where('end_time', $request->end_time)
        ->first();

    if (!$slot) {
        return response()->json([
            'status' => 'error',
            'message' => 'This time slot is not available on this day'
        ], 400);
    }

    // 3. Check overlap ONLY with booked appointments
    $overlap = Appointment::where('doctor_id', $doctorId)
        ->where('date', $date)
        ->where('status', 'booked')
        ->where(function ($q) use ($request) {
            $q->where('start_time', '<', $request->end_time)
              ->where('end_time', '>', $request->start_time);
        })
        ->exists();

    if ($overlap) {
        return response()->json([
            'status' => 'error',
            'message' => 'This time slot is already booked'
        ], 400);
    }

    // 4. Create appointment
    $appointment = Appointment::create([
        'doctor_id'  => $doctorId,
        'patient_id' => $request->user()->patient->id,
        'date'       => $date,
        'start_time' => $request->start_time,
        'end_time'   => $request->end_time,
        'status'     => 'booked',
    ]);

    // 5. Update statistics
    $appointment->doctor->increment('appointments_count');
    $appointment->patient->increment('appointments_count');

    return response()->json([
        'status' => 'success',
        'message' => 'Appointment booked successfully',
        'appointment' => $appointment
    ]);
}



    /**
     * Cancel appointment
     */
    public function cancel(Request $request, $id)
{
    $appointment = Appointment::findOrFail($id);

    if ($appointment->patient_id !== $request->user()->patient->id) {
        return response()->json(['error' => 'Unauthorized'], 403);
    }

    if ($appointment->status !== 'booked') {
        return response()->json([
            'status' => 'error',
            'message' => 'Only booked appointments can be cancelled'
        ], 400);
    }

    $appointment->status = 'cancelled';
    $appointment->save();

    // Update statistics
    
    $appointment->patient->increment('cancellations_count');

    return response()->json([
        'status' => 'success',
        'message' => 'Appointment cancelled'
    ]);
}


    public function complete(Request $request, $id)
    {
        $appointment = Appointment::findOrFail($id);

        if ($appointment->status !== 'booked') {
            return response()->json([
                'status' => 'error',
                'message' => 'Only booked appointments can be completed'
            ], 400);
        }

        // Combine date + end_time into a Carbon datetime
        $appointmentEnd = Carbon::parse($appointment->date . ' ' . $appointment->end_time);

        // Check if appointment has actually finished
        if (Carbon::now()->lt($appointmentEnd)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Appointment cannot be completed before it ends'
            ], 400);
        }

        // Validate medical fields
        $request->validate([
            'symptoms'     => 'required|string',
            'diagnosis'    => 'required|string',
            'prescription' => 'required|string',
            'notes'        => 'nullable|string',
        ]);

        // Update appointment status + medical fields
        $appointment->update([
            'status'       => 'completed',
            'symptoms'     => $request->symptoms,
            'diagnosis'    => $request->diagnosis,
            'prescription' => $request->prescription,
            'notes'        => $request->notes,
        ]);

        // Update statistics
        $doctor = $appointment->doctor;
        $patient = $appointment->patient;

        $doctor->increment('appointments_count');
        $patient->increment('appointments_count');

        return response()->json([
            'status' => 'success',
            'message' => 'Appointment marked as completed',
            'appointment' => [
                'id'          => $appointment->id,
                'doctor_id'   => $appointment->doctor_id,
                'patient_id'  => $appointment->patient_id,
                'date'        => $appointment->date,
                'start_time'  => Carbon::parse($appointment->start_time)->format('H:i'),
                'end_time'    => Carbon::parse($appointment->end_time)->format('H:i'),
                'status'      => $appointment->status,
                'symptoms'    => $appointment->symptoms,
                'diagnosis'   => $appointment->diagnosis,
                'prescription'=> $appointment->prescription,
                'notes'       => $appointment->notes,
            ]
        ]);
    }



}
