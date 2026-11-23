<?php

namespace App\Http\Controllers;

use App\Models\JobReferral;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(Request $request): View
    {
        $jobs = JobReferral::with('alumni.alumniProfile')
            ->where('status', 'open')
            ->latest()
            ->take(3)
            ->get();

        return view('dashboard', [
            'user' => $request->user(),
            'jobs' => $jobs,
            'unread' => $request->user()->inbox()->where('is_read', false)->count(),
        ]);
    }
}
