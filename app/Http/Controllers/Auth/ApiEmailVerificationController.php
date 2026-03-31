<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class ApiEmailVerificationController extends Controller
{
    public function send(Request $request)
    {
        $user = $request->user();

        if ($user->hasVerifiedEmail()) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Email already verified.'
            ], 400);
        }

        // Generate a 6‑digit code
        $code = rand(100000, 999999);

        // Store temporarily (15 minutes)
        cache()->put('email_verification_'.$user->id, $code, now()->addMinutes(15));

        // Send email
        Mail::raw("Your verification code is: $code", function ($message) use ($user) {
            $message->to($user->email)
                    ->subject('Email Verification Code');
        });

        return response()->json([
            'status'  => 'success',
            'message' => 'Verification code sent.'
        ], 200);
    }

    public function verify(Request $request)
    {
        // 1. Validate input manually
        $validator = Validator::make($request->all(), [
            'code' => 'required|numeric'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = $request->user();
        $storedCode = cache()->get('email_verification_'.$user->id);

        // 2. Check code validity
        if (!$storedCode || $storedCode != $request->code) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Invalid or expired verification code.'
            ], 422);
        }

        // 3. Mark email as verified
        $user->email_verified_at = now();
        $user->save();

        // 4. Remove code
        cache()->forget('email_verification_'.$user->id);

        return response()->json([
            'status'  => 'success',
            'message' => 'Email verified successfully.'
        ], 200);
    }
}
