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
        $now = Carbon::now();

        // ============================
        // 1. Monthly Revenue
        // ============================
        $currentRevenue = WalletTransaction::where('type', 'platform_fee')
            ->whereMonth('created_at', $now->month)
            ->sum('amount');

        $previousRevenue = WalletTransaction::where('type', 'platform_fee')
            ->whereMonth('created_at', $now->copy()->subMonth()->month)
            ->sum('amount');

        $revenueGrowth = (($currentRevenue - $previousRevenue) / max($previousRevenue, 1)) * 100;

        // ============================
        // 2. appointmetns Today
        // ============================
        $appointmetnsToday = Appointment::whereDate('created_at', Carbon::today())->count();
        $appointmetnsYesterday = Appointment::whereDate('created_at', Carbon::yesterday())->count();

        $appointmetnsGrowth = (($appointmetnsToday - $appointmetnsYesterday) / max($appointmetnsYesterday, 1)) * 100;

        // ============================
        // 3. New Doctors Approved This Month
        // ============================


        $newDoctorsThisMonth = Doctor::where('status', 'approved')
            ->whereMonth('created_at', $now->month)
            ->count();


        // ============================
        // 4. Total Active Users Growth
        // ============================
        $activeUsersThisMonth = User::where('status', 'active')
            ->whereMonth('created_at', $now->month)
            ->count();

        $activeUsersLastMonth = User::where('status', 'active')
            ->whereMonth('created_at', $now->copy()->subMonth()->month)
            ->count();

        $userGrowth = (($activeUsersThisMonth - $activeUsersLastMonth) / max($activeUsersLastMonth, 1)) * 100;

        // ============================
        // Final Response
        // ============================
        return response()->json([
            'status' => 'success',

            // Monthly revenue
            'monthly_revenue' => $currentRevenue,
            'monthly_revenue_growth' => round($revenueGrowth, 2),

            // appointmetns today
            'appointmetns_today' => $appointmetnsToday,
            'appointmetns_today_growth' => round($appointmetnsGrowth, 2),

            // New doctors approved this month
            'active_doctors' => Doctor::where('status', 'approved')
                ->whereHas('user', function ($q) {
                    $q->where('status', 'active');
                })
                ->count(),
            'new_doctors_this_month' => $newDoctorsThisMonth,

            // Active users
            'total_users' => User::where('status', 'active')->count(),

            'active_users_growth' => round($userGrowth, 2),
        ]);
    }
}
