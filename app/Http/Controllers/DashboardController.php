<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Models\User;
use App\Models\ActivityLog;
use App\Models\IdleSession;
use App\Models\Penalty;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin']);
    }

    public function index(Request $request)
    {
        $data = [
            'users_count'        => User::where('role', User::ROLE_USER)->count(),
            'admins_count'       => User::where('role', User::ROLE_ADMIN)->count(),
            'activities_count'   => ActivityLog::count(),
            'penalty_count'    => Penalty::count(),
            'idle_count' => IdleSession::count(),
            'latest_activities'  => ActivityLog::with('user')->latest()->take(10)->get(),
            'latest_idles'       => IdleSession::with('user')->latest()->take(10)->get(),
            'latest_penalties'   => Penalty::with('user')->latest()->take(10)->get(),
        ];

        if ($request->ajax()) {
            return response()->json(['status' => 'ok', 'data' => $data]);
        }

        return view('admin.dashboard', $data);
    }
}
