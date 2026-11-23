<?php

namespace App\Http\Controllers;

use App\Models\MentoringRequest;
use App\Models\MentorProfile;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MentoringController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();

        if ($user->isAlumni()) {
            $mentorProfile = $user->mentorProfile;

            return view('mentoring.alumni', [
                'mentorProfile' => $mentorProfile,
                'requests' => $mentorProfile
                    ? $mentorProfile->requests()->with('mahasiswa.mahasiswaProfile')->latest()->get()
                    : collect(),
            ]);
        }

        $search = trim((string) $request->query('q'));

        $mentors = MentorProfile::query()
            ->with('alumni.alumniProfile')
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('expertise', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%")
                        ->orWhereHas('alumni', fn ($a) => $a->where('name', 'like', "%{$search}%"));
                });
            })
            ->latest()
            ->paginate(9)
            ->withQueryString();

        return view('mentoring.mahasiswa', [
            'mentors' => $mentors,
            'myRequests' => MentoringRequest::with('mentor.alumni')
                ->where('mahasiswa_id', $user->id)->latest()->get(),
        ]);
    }
}
