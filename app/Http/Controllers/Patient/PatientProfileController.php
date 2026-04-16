<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PatientProfileController extends Controller
{
    /**
     * Show patient profile
     */
    public function show(Request $request)
    {
        $user = $request->user();
        $patient = $user->patient;

        return response()->json([
            'status' => 'success',
            'profile' => [
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
                'age' => $patient->age,
                'height' => $patient->height,
                'weight' => $patient->weight,
                'phone' => $user->phone,
                'email' => $user->email,
                'balance' => $patient->balance,
            ]
        ]);
    }

    /**
     * Update patient profile
     */
    public function update(Request $request)
    {
        $request->validate([
            'first_name' => 'nullable|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'age' => 'nullable|integer|min:0|max:120',
            'height' => 'nullable|integer|min:0|max:300',
            'weight' => 'nullable|integer|min:0|max:500',
            'phone' => 'nullable|string|max:20',
        ]);

        $user = $request->user();
        $patient = $user->patient;

        // Update user fields
        if ($request->filled('first_name')) $user->first_name = $request->first_name;
        if ($request->filled('last_name')) $user->last_name = $request->last_name;
        if ($request->filled('phone')) $user->phone = $request->phone;
        $user->save();

        // Update patient fields
        if ($request->filled('age')) $patient->age = $request->age;
        if ($request->filled('height')) $patient->height = $request->height;
        if ($request->filled('weight')) $patient->weight = $request->weight;
        $patient->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Profile updated successfully'
        ]);
    }
}
