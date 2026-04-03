<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\User;
use App\Models\WalletTransaction;
use Carbon\Carbon;

class AdminStatsController extends Controller
{
    public function index()
    {
        return response()->json([
            'status' => 'success',

            // Monthly revenue
            'monthly_revenue' => WalletTransaction::where('type', 'platform_fee')
                ->whereMonth('created_at', now()->month)
                ->sum('amount'),

            // Consultations created today
            'consultations_today' => Appointment::whereDate('created_at', Carbon::today())
                ->count(),

            // Doctors who are approved AND have active user accounts
            'active_doctors' => Doctor::where('status', 'approved')
                ->whereHas('user', function ($q) {
                    $q->where('status', 'active');
                })
                ->count(),

            // Total active users
            'total_users' => User::where('status', 'active')->count(),
        ]);
    }
}
