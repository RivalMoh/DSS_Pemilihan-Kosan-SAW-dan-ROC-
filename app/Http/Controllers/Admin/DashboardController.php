<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kosan;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $stats = [
            'total_kosan' => Kosan::count(),
            'available_kosan' => Kosan::count(), // All kosan are considered available
            'total_users' => User::count(),
            'total_admins' => User::where('is_admin', true)->count(),
        ];

        $recentKosan = Kosan::latest()->take(5)->get();
        
        return view('admin.dashboard', compact('stats', 'recentKosan'));
    }
}
