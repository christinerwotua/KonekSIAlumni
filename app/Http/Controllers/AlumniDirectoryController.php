<?php

namespace App\Http\Controllers;

use App\Models\Connection;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AlumniDirectoryController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->query('q'));
        $batch = $request->query('batch');

        $alumni = User::query()
            ->where('role', User::ROLE_ALUMNI)
            ->with(['alumniProfile', 'mentorProfile'])
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhereHas('alumniProfile', fn ($p) => $p
                            ->where('company', 'like', "%{$search}%")
                            ->orWhere('job_title', 'like', "%{$search}%"));
                });
            })
            ->when($batch, fn ($query) => $query->whereHas('alumniProfile', fn ($p) => $p->where('graduation_year', $batch)))
            ->orderBy('name')
            ->paginate(9)
            ->withQueryString();

        $batches = \App\Models\AlumniProfile::whereNotNull('graduation_year')
            ->distinct()->orderByDesc('graduation_year')->pluck('graduation_year');

        return view('alumni.index', [
            'alumni' => $alumni,
            'batches' => $batches,
            'total' => User::where('role', User::ROLE_ALUMNI)->count(),
        ]);
    }

    public function show(Request $request, User $user): View
    {
        abort_unless($user->isAlumni(), 404);

        $user->load(['alumniProfile', 'mentorProfile', 'jobReferrals' => fn ($q) => $q->latest()->take(5)]);

        $connection = $request->user()->isMahasiswa()
            ? Connection::where('mahasiswa_id', $request->user()->id)->where('alumni_id', $user->id)->first()
            : null;

        return view('alumni.show', [
            'alumnus' => $user,
            'connection' => $connection,
            'backUrl' => $request->query('back'),
        ]);
    }
}
