<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ApiNewPasswordController extends Controller
{
    public function store(Request $request)
    {
        // 1. Validate input manually for API-friendly errors
        $validator = Validator::make($request->all(), [
            'token'                 => 'required',
            'email'                 => 'required|email',
            'password'              => 'required|confirmed|min:6',
            'password_confirmation' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        // 2. Attempt password reset
        $status = Password::reset(
            $validator->validated(),
            function (User $user) use ($request) {
                $user->forceFill([
                    'password'       => Hash::make($request->password),
                    'remember_token' => Str::random(60),
                ])->save();

                event(new PasswordReset($user));
            }
        );

        // 3. Handle failure
        if ($status !== Password::PASSWORD_RESET) {
            return response()->json([
                'status'  => 'error',
                'message' => __($status)
            ], 422);
        }

        // 4. Success
        return response()->json([
            'status'  => 'success',
            'message' => 'Password has been reset successfully.'
        ], 200);
    }
}
