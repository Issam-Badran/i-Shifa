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
    // 1. Find user by ID
    $user = \App\Models\User::find($request->id);

    if (! $user) {
        return response()->json(['message' => 'User not found'], 404);
    }
    // 2. Already verified?
    if ($user->hasVerifiedEmail()) {
        
        // Auto-login anyway
        $token = $user->createToken('auth_token')->plainTextToken;

        return redirect(config('app.frontend_url') . "?token={$token}");
    }

    // 3. Validate signature
    if (! $request->hasValidSignature()) {
        return response()->json(['message' => 'Invalid or expired verification link'], 401);
    }

    // 4. Mark as verified
    $user->markEmailAsVerified();
    event(new Verified($user));

    // 5. Auto-login: create Sanctum token
    $token = $user->createToken('auth_token')->plainTextToken;

    // 6. Redirect to frontend with token
    return redirect(config('app.frontend_url') . "?token={$token}");
}


}
