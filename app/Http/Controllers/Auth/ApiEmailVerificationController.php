<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\Request;

class ApiEmailVerificationController extends Controller
{
    public function send(Request $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return response()->json(['message' => 'Email already verified'], 400);
        }

        $request->user()->sendEmailVerificationNotification();

        return response()->json(['message' => 'Verification link sent']);
    }

    public function verify(Request $request)
{
    // 1. Find user by ID from query string
    $user = \App\Models\User::find($request->id);

    if (! $user) {
        return response()->json(['message' => 'User not found'], 404);
    }

    // 2. Already verified?
    if ($user->hasVerifiedEmail()) {
        return response()->json(['message' => 'Email already verified'], 400);
    }

    // 3. Check signature validity
    if (! $request->hasValidSignature()) {
        return response()->json(['message' => 'Invalid or expired verification link'], 401);
    }

    // 4. Mark as verified
    $user->markEmailAsVerified();
    event(new \Illuminate\Auth\Events\Verified($user));

    return response()->json(['message' => 'Email verified successfully']);
}

}
