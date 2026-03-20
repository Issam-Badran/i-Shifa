<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class ApiEmailVerificationController extends Controller
{
    public function send(Request $request)
    {
        $user = $request->user();

        if ($user->hasVerifiedEmail()) {
            return response()->json([
                'message' => 'Email already verified.'
            ], 400);
        }

        // Generate a verification code
        $code = rand(100000, 999999);

        // Store it temporarily (you can use cache or DB)
        cache()->put('email_verification_'.$user->id, $code, now()->addMinutes(15));

        // Send email
        Mail::raw("Your verification code is: $code", function ($message) use ($user) {
            $message->to($user->email)
                    ->subject('Email Verification Code');
        });

        return response()->json([
            'message' => 'Verification code sent.'
        ]);
    }

    public function verify(Request $request)
    {
        $request->validate([
            'code' => ['required', 'numeric']
        ]);

        $user = $request->user();

        $storedCode = cache()->get('email_verification_'.$user->id);

        if (!$storedCode || $storedCode != $request->code) {
            return response()->json([
                'message' => 'Invalid or expired verification code.'
            ], 422);
        }

        // Mark email as verified
        $user->email_verified_at = now();
        $user->save();

        // Remove code
        cache()->forget('email_verification_'.$user->id);

        return response()->json([
            'message' => 'Email verified successfully.'
        ]);
    }
}
