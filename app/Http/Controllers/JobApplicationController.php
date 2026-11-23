<?php

namespace App\Http\Controllers;

use App\Models\JobApplication;
use App\Models\JobReferral;
use App\Models\UserNotification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class JobApplicationController extends Controller
{
    public function create(Request $request, JobReferral $job): View|RedirectResponse
    {
        if ($redirect = $this->guard($request, $job)) {
            return $redirect;
        }

        $documents = $request->user()->talentProfile?->documents()->latest()->get() ?? collect();

        return view('jobs.apply', ['job' => $job, 'documents' => $documents]);
    }

    public function store(Request $request, JobReferral $job): RedirectResponse
    {
        if ($redirect = $this->guard($request, $job)) {
            return $redirect;
        }

        $myDocumentIds = $request->user()->talentProfile?->documents()->pluck('id')->all() ?? [];

        $data = $request->validate([
            'cv_id' => ['required', Rule::in($myDocumentIds)],
            'message' => ['required', 'string', 'max:3000'],
        ], ['cv_id.required' => 'Pilih CV yang akan dikirim. Unggah dulu di Profil Talent jika belum ada.'],
            ['cv_id' => 'CV', 'message' => 'alasan melamar']);

        $application = $job->applications()->create($data + [
            'mahasiswa_id' => $request->user()->id,
            'status' => 'pending',
        ]);

        UserNotification::send($job->alumni, 'job_referral', 'Ada pelamar baru',
            "{$request->user()->name} melamar lowongan {$job->title}.", route('jobs.show', $job));

        return redirect()->route('jobs.show', $job)->with('status', 'Lamaran terkirim. Pantau statusnya di halaman ini.');
    }

    /** Alumni pemilik lowongan menerima / menolak lamaran. */
    public function update(Request $request, JobApplication $application): RedirectResponse
    {
        $application->load('jobReferral');
        abort_unless($application->jobReferral->alumni_id == $request->user()->id, 403);

        $data = $request->validate(['status' => ['required', Rule::in(['pending', 'accepted', 'rejected'])]]);
        $application->update($data);

        UserNotification::send($application->mahasiswa, 'job_referral',
            'Status lamaran: ' . JobApplication::STATUS_LABELS[$data['status']],
            "Lamaran Anda untuk {$application->jobReferral->title} di {$application->jobReferral->company} " .
            strtolower(JobApplication::STATUS_LABELS[$data['status']]) . '.',
            route('jobs.show', $application->jobReferral));

        return back()->with('status', 'Status lamaran diperbarui.');
    }

    /** Lowongan harus masih buka dan belum pernah dilamar oleh mahasiswa ini. */
    private function guard(Request $request, JobReferral $job): ?RedirectResponse
    {
        if (! $job->isOpen()) {
            return redirect()->route('jobs.show', $job)->with('status', 'Lowongan ini sudah ditutup.');
        }

        if ($job->applications()->where('mahasiswa_id', $request->user()->id)->exists()) {
            return redirect()->route('jobs.show', $job)->with('status', 'Anda sudah melamar lowongan ini.');
        }

        return null;
    }
}
