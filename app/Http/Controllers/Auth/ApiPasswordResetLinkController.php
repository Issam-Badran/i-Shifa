<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;

class ApiPasswordResetLinkController extends Controller
{
    public function store(Request $request)
    {
        // 1. Validate input manually for API-friendly errors
        $validator = Validator::make($request->all(), [
            'email' => 'required|email'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        // 2. Attempt to send reset link
        $status = Password::sendResetLink(
            $validator->validated()
        );

        // 3. Handle failure
        if ($status !== Password::RESET_LINK_SENT) {
            return response()->json([
                'status'  => 'error',
                'message' => __($status)
            ], 422);
        }

        // 4. Success response
        return response()->json([
            'status'  => 'success',
            'message' => 'Password reset link sent successfully'
        ], 200);
    }
}
