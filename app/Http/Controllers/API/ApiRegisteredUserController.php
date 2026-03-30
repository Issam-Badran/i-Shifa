<?php


namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Doctor;
use App\Models\Patient;
use Illuminate\Support\Facades\Hash;

class ApiRegisteredUserController extends Controller
{
    public function store(Request $request)
    {
       
        // 1. Validation
        $validated = $request->validate([
            'first_name'   => 'required|string|max:255',
            'last_name'    => 'required|string|max:255',
            'email'        => 'required|email|unique:users,email',
            'password'     => 'required|min:6',
            'role'         => 'required|in:doctor,patient',

            'latitude'     => 'nullable|numeric',
            'longitude'    => 'nullable|numeric',

            // Only required for doctors
            'degree_file'  => 'required_if:role,doctor|mimes:pdf,jpg,jpeg,png|max:4096',
        ]);

        // 2. Create User
        $user = User::create([
            'first_name' => $validated['first_name'],
            'last_name'  => $validated['last_name'],
            'email'      => $validated['email'],
            'password'   => Hash::make($validated['password']),
            'role'       => $validated['role'],
            'latitude'   => $validated['latitude'] ?? null,
            'longitude'  => $validated['longitude'] ?? null,
            'status'     => 'active',
        ]);

        // 3. Create Doctor or Patient Profile
        if ($validated['role'] === 'doctor') {

            // Upload degree file
            $degreePath = $request->file('degree_file')->store('degrees', 'public');

            Doctor::create([
                'user_id'            => $user->id,
                'specialization'     => null, // doctor will fill later
                'degree_file'        => $degreePath,
                'clinic_start_time'  => null,
                'clinic_end_time'    => null,
                'consultation_fee'   => 0,
                'doctor_share'       => 0,
                'total_earnings'     => 0,
                'appointments_count' => 0,
                'balance'            => 0,
                'status'             => 'pending', // WAITING FOR ADMIN APPROVAL
            ]);

        } elseif ($validated['role'] === 'patient') {

            Patient::create([
                'user_id'             => $user->id,
                'balance'             => 0,
                'appointments_count'  => 0,
                'cancellations_count' => 0,
            ]);
        }

        // 4. Return Response
        return response()->json([
            'message' => 'User registered successfully',
            'user'    => $user,
        ], 201);
    }
}
