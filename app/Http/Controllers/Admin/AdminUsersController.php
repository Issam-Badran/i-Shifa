<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class AdminUsersController extends Controller
{
    /**
     * Show all users with role & status
     */
    public function index()
    {
        $users = User::with(['doctor', 'patient'])
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($user) {
                return [
                    'id' => $user->id,
                    'name' => $user->first_name . ' ' . $user->last_name,
                    'email' => $user->email,
                    'role' => $user->role,
                    'status' => $user->status,
                    'created_at' => $user->created_at,
                ];
            });

        return response()->json([
            'status' => 'success',
            'users' => $users
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
