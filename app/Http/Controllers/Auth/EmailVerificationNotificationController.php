<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class EmailVerificationNotificationController extends Controller
{
    public function store(Request $request)
    {
        // If already verified
        if ($request->user()->hasVerifiedEmail()) {
            return response()->json([
                'message' => 'Email already verified',
                'user' => $request->user()
            ]);
        }

        // Send verification email
        $request->user()->sendEmailVerificationNotification();

        return response()->json([
            'message' => 'Verification link sent successfully',
            'user' => $request->user()
        ]);
    }
}
