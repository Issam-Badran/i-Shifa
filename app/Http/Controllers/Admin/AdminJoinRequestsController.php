<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use App\Models\User;
use Illuminate\Http\Request;

class AdminJoinRequestsController extends Controller
{
    public function index(Request $request)
    {
        // Filters
        $status = $request->query('status'); // pending, approved, rejected
        $search = $request->query('search'); // name, specialization, email

        $query = Doctor::with('user');

        // Filter by doctor status
        if ($status) {
            $query->where('status', $status);
        }

        // Search filter
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('specialization', 'like', "%$search%")
                  ->orWhereHas('user', function ($u) use ($search) {
                      $u->where('first_name', 'like', "%$search%")
                        ->orWhere('last_name', 'like', "%$search%")
                        ->orWhere('email', 'like', "%$search%");
                  });
            });
        }

        // Fetch doctors
        $doctors = $query->orderBy('created_at', 'desc')->get();

        // Map to UI format
        $mapped = $doctors->map(function ($doc) {
            return [
                'id' => $doc->id,
                'name' => 'د. ' . $doc->user->first_name . ' ' . $doc->user->last_name,
                'specialization' => $doc->specialization,
                'status' => $doc->status,
                'experience_years' => $doc->experience_years ?? null, // if you add this later
                'latitude' => $doc->user->latitude,
                'longitude' => $doc->user->longitude,
                'email' => $doc->user->email,
                'phone' => $doc->phone,
                'joined_at' => $doc->created_at->format('Y-m-d'),
            ];
        });

        return response()->json([
            'status' => 'success',
            'requests' => $mapped
        ]);
    }

    public function show($id)
    {
        $doctor = Doctor::with('user')->findOrFail($id);

        return response()->json([
            'status' => 'success',
            'request' => [
                'id' => $doctor->id,

                // Basic info
                'name' => 'د. ' . $doctor->user->first_name . ' ' . $doctor->user->last_name,
                'specialization' => $doctor->specialization,
                'sub_specialization' => $doctor->sub_specialization,
                'status' => $doctor->status,

                // Professional info
                'experience_years' => $doctor->experience_years,
                'languages' => $doctor->languages,
                'license_number' => $doctor->license_number,
                'degree' => $doctor->degree,
                'university' => $doctor->university,
                'bio' => $doctor->bio,

                // Application date
                'applied_at' => $doctor->created_at->format('Y-m-d'),

                // Location
                'latitude' => $doctor->user->latitude,
                'longitude' => $doctor->user->longitude,

                // Contact
                'email' => $doctor->user->email,
                'phone' => $doctor->phone,
                'profile_image' => $doctor->user->profile_image,

                // Attached documents
                'degree_file' => $doctor->degree_file,   // REQUIRED
            ]
        ]);
    }

    public function approve($id)
    {
        $doctor = Doctor::findOrFail($id);
        $doctor->status = 'approved';
        $doctor->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Doctor approved successfully'
        ]);
    }

    public function reject($id)
    {
        $doctor = Doctor::findOrFail($id);
        $doctor->status = 'rejected';
        $doctor->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Doctor rejected successfully'
        ]);
    }
}
