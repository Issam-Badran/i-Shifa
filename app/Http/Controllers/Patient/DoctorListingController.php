<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Doctor;

class DoctorListingController extends Controller
{
    public function index(Request $request)
    {
        $query = Doctor::with('user');

        // Filter by specialization
        if ($request->has('specialization')) {
            $query->where('specialization', $request->specialization);
        }

        // Search by doctor name
        if ($request->has('search')) {
            $search = $request->search;
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('first_name', 'LIKE', "%$search%")
                  ->orWhere('last_name', 'LIKE', "%$search%");
            });
        }

        $doctors = $query->get()->map(function ($doctor) {
            return [
                'id' => $doctor->id,

                // User info
                'first_name' => $doctor->user->first_name,
                'last_name' => $doctor->user->last_name,
                'profile_image' => $doctor->user->profile_image,

                // Specializations
                'specialization' => $doctor->specialization,
                'sub_specialization' => $doctor->sub_specialization,

                // Professional info
                'experience_years' => $doctor->experience_years,
                'languages' => $doctor->languages,

                // Location (raw coordinates)
                'latitude' => $doctor->user->latitude,
                'longitude' => $doctor->user->longitude,

                // Fee
                'consultation_fee' => $doctor->consultation_fee,
            ];
        });

        return response()->json([
            'status' => 'success',
            'doctors' => $doctors
        ]);
    }
}
