<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use Illuminate\Http\Request;

class DoctorStatusController extends Controller
{
    /**
     * عرض حالة الطبيب
     */
    public function show($id)
    {
        $doctor = Doctor::find($id);

        if (!$doctor) {
            return response()->json([
                'status' => 'error',
                'message' => 'Doctor not found'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'doctor_id' => $doctor->id,
            'status_value' => $doctor->status // الحقل من جدول doctors
        ]);
    }

    /**
     * تعديل حالة الطبيب
     */
    public function update(Request $request, $id)
    {
        $doctor = Doctor::find($id);

        if (!$doctor) {
            return response()->json([
                'status' => 'error',
                'message' => 'Doctor not found'
            ], 404);
        }

        $request->validate([
            'status' => 'required|in:approved,pending,rejected'
        ]);

        $doctor->status = $request->status;
        $doctor->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Doctor status updated successfully',
            'doctor_id' => $doctor->id,
            'status_value' => $doctor->status
        ]);
    }
}