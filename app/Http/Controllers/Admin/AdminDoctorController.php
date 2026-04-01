<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Doctor;

class AdminDoctorController extends Controller
{
    public function pending()
    {
        $doctors = Doctor::where('status', 'pending')
            ->with('user:id,first_name,last_name,email')
            ->get();

        return response()->json([
            'status' => 'success',
            'doctors' => $doctors,
        ]);
    }

    public function approve($id)
    {
        $doctor = Doctor::find($id);

        if (! $doctor) {
            return response()->json([
                'status' => 'error',
                'message' => 'Doctor not found',
            ], 404);
        }

        $doctor->status = 'approved';
        $doctor->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Doctor approved successfully',
        ]);
    }

    public function reject($id)
    {
        $doctor = Doctor::find($id);

        if (! $doctor) {
            return response()->json([
                'status' => 'error',
                'message' => 'Doctor not found',
            ], 404);
        }

        $doctor->status = 'rejected';
        $doctor->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Doctor rejected successfully',
        ]);
    }
}
