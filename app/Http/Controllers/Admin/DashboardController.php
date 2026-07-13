<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Actual;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $totalPicAktif = User::where('role', 'pic')->where('is_aktif', true)->count();
        $totalPicNonaktif = User::where('role', 'pic')->where('is_aktif', false)->count();
        $totalPending = Actual::where('status', 'pending')->count();
        $totalApproved = Actual::where('status', 'approved')
            ->whereMonth('updated_at', now()->month)
            ->whereYear('updated_at', now()->year)
            ->count();
        $totalDraft = Actual::where('status', 'draft')->count();

        return view('admin.dashboard', compact(
            'totalPicAktif',
            'totalPicNonaktif',
            'totalPending',
            'totalApproved',
            'totalDraft'
        ));
    }
}
