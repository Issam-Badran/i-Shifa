<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use App\Models\User;
use Illuminate\Http\Request;

class AdminUsersController extends Controller
{
    /**
     * Show all users with role & status
     */
    public function index(Request $request)
    {
        // Filters
        $role = $request->query('role');        // admin, doctor, patient
        $status = $request->query('status');    // active, banned, suspended
        $search = $request->query('search');    // name, email, phone

        $query = User::query();

        // Filter by role
        if ($role) {
            $query->where('role', $role);
        }

        // Filter by USER status
        if ($status) {
            $query->where('status', $status);
        }

        // Search filter
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%$search%")
                  ->orWhere('last_name', 'like', "%$search%")
                  ->orWhere('email', 'like', "%$search%")
                  ->orWhere('phone', 'like', "%$search%");
            });
        }

        // Fetch users
        $users = $query->orderBy('created_at', 'desc')->get();

        // Map to UI format
        $mapped = $users->map(function ($user) {

            // Doctor info (optional)
            $doctor = null;
            if ($user->role === 'doctor') {
                $doctor = Doctor::where('user_id', $user->id)->first();
            }

            return [
                'id' => $user->id,
                'name' => $user->first_name . ' ' . $user->last_name,
                'role' => $user->role,

                // USER STATUS (active, banned, suspended)
                'status' => $user->status,

                // Location
                'latitude' => $user->latitude,
                'longitude' => $user->longitude,

                // Contact
                'phone' => $user->phone,
                'email' => $user->email,

                // Profile image
                'profile_image' => $user->profile_image,

                // Join date
                'joined_at' => $user->created_at->format('Y-m-d'),

                // Doctor-specific fields
                'specialization' => $doctor?->specialization,
                'consultations_count' => $doctor?->appointments_count,
            ];
        });

        return response()->json([
            'status' => 'success',
            'users' => $mapped
        ]);
    }

    /**
     * Ban user (permanent)
     */
    public function ban(User $user)
    {
        if ($user->role === 'admin') {
            return response()->json(['error' => 'Cannot ban an admin'], 403);
        }

        $user->status = 'banned';
        $user->save();

        return response()->json([
            'status' => 'success',
            'message' => 'User banned successfully'
        ]);
    }

    /**
     * Unban user
     */
    public function unban(User $user)
    {
        $user->status = 'active';
        $user->save();

        return response()->json([
            'status' => 'success',
            'message' => 'User unbanned successfully'
        ]);
    }


}
