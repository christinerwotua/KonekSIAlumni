<?php

namespace App\Http\Controllers;

use App\Models\JobReferral;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class JobReferralController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->query('q'));
        $status = $request->query('status');

        $jobs = JobReferral::query()
            ->with('alumni.alumniProfile')
            ->withCount('applications')
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%")
                        ->orWhere('company', 'like', "%{$search}%")
                        ->orWhere('location', 'like', "%{$search}%");
                });
            })
            ->when(in_array($status, ['open', 'closed'], true), fn ($q) => $q->where('status', $status))
            ->when($request->boolean('mine') && $request->user()->isAlumni(), fn ($q) => $q->where('alumni_id', $request->user()->id))
            ->latest()
            ->paginate(9)
            ->withQueryString();

        return view('jobs.index', ['jobs' => $jobs]);
    }

    public function create(): View
    {
        return view('jobs.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'company' => ['required', 'string', 'max:255'],
            'title' => ['required', 'string', 'max:255'],
            'location' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:5000'],
            'requirements' => ['required', 'string', 'max:5000'],
            'application_deadline' => ['required', 'date', 'after_or_equal:today'],
        ], [], [
            'company' => 'nama perusahaan',
            'title' => 'posisi',
            'location' => 'lokasi kerja',
            'description' => 'deskripsi pekerjaan',
            'requirements' => 'kualifikasi',
            'application_deadline' => 'batas waktu lamaran',
        ]);

        $job = $request->user()->jobReferrals()->create($data + ['status' => 'open']);

        return redirect()->route('jobs.show', $job)->with('status', 'Lowongan berhasil dipublikasikan.');
    }

    public function show(Request $request, JobReferral $job): View
    {
        $job->load('alumni.alumniProfile');
        $user = $request->user();
        $isOwner = $job->alumni_id == $user->id;

        return view('jobs.show', [
            'job' => $job,
            'isOwner' => $isOwner,
            'applications' => $isOwner
                ? $job->applications()->with(['mahasiswa.mahasiswaProfile', 'mahasiswa.talentProfile', 'cv'])->latest()->get()
                : collect(),
            'myApplication' => $user->isMahasiswa()
                ? $job->applications()->where('mahasiswa_id', $user->id)->first()
                : null,
        ]);
    }

    /** Alumni pemilik membuka / menutup lowongan. */
    public function toggleStatus(Request $request, JobReferral $job): RedirectResponse
    {
        abort_unless($job->alumni_id == $request->user()->id, 403);

        $job->update(['status' => $job->status === 'open' ? 'closed' : 'open']);

        return back()->with('status', $job->status === 'open' ? 'Lowongan dibuka kembali.' : 'Lowongan ditutup.');
    }
}
