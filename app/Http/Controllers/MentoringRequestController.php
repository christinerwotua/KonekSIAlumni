<?php

namespace App\Http\Controllers;

use App\Models\MentoringRequest;
use App\Models\MentorProfile;
use App\Models\UserNotification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class MentoringRequestController extends Controller
{
    public function create(Request $request, MentorProfile $mentor): View
    {
        $mentor->load('alumni.alumniProfile');

        return view('mentoring.create', [
            'mentor' => $mentor,
            'documents' => $request->user()->talentProfile?->documents()->latest()->get() ?? collect(),
        ]);
    }

    public function store(Request $request, MentorProfile $mentor): RedirectResponse
    {
        $myDocumentIds = $request->user()->talentProfile?->documents()->pluck('id')->all() ?? [];

        $data = $request->validate([
            'goal' => ['required', 'string', 'max:3000'],
            'topic' => ['required', 'string', 'max:255'],
            'proposed_schedule' => ['required', 'date', 'after:now'],
            'cv_id' => ['nullable', Rule::in($myDocumentIds)],
        ], [], ['goal' => 'tujuan mentoring', 'topic' => 'topik', 'proposed_schedule' => 'jadwal', 'cv_id' => 'CV']);

        $mentoringRequest = $mentor->requests()->create($data + [
            'mahasiswa_id' => $request->user()->id,
            'status' => 'pending',
        ]);

        UserNotification::send($mentor->alumni, 'mentoring', 'Permintaan mentoring baru',
            "{$request->user()->name} mengajukan mentoring: {$data['topic']}.",
            route('mentoring.show', $mentoringRequest));

        return redirect()->route('mentoring.show', $mentoringRequest)->with('status', 'Pengajuan mentoring terkirim.');
    }

    public function show(Request $request, MentoringRequest $mentoringRequest): View
    {
        $mentoringRequest->load(['mentor.alumni.alumniProfile', 'mahasiswa.mahasiswaProfile', 'cv']);
        $user = $request->user();

        abort_unless(
            $mentoringRequest->mahasiswa_id == $user->id || $mentoringRequest->mentor->alumni_id == $user->id,
            403
        );

        return view('mentoring.show', [
            'req' => $mentoringRequest,
            'isMentor' => $mentoringRequest->mentor->alumni_id == $user->id,
        ]);
    }

    /** Alumni (mentor) menerima atau menolak pengajuan. */
    public function respond(Request $request, MentoringRequest $mentoringRequest): RedirectResponse
    {
        $mentoringRequest->load('mentor');
        abort_unless($mentoringRequest->mentor->alumni_id == $request->user()->id, 403);

        $data = $request->validate([
            'status' => ['required', Rule::in(['accepted', 'rejected'])],
            'response_message' => ['nullable', 'string', 'max:3000'],
        ], [], ['response_message' => 'tanggapan']);

        $mentoringRequest->update($data + ['responded_at' => now()]);

        UserNotification::send($mentoringRequest->mahasiswa, 'mentoring',
            'Pengajuan mentoring ' . ($data['status'] === 'accepted' ? 'diterima' : 'ditolak'),
            "{$request->user()->name} " . ($data['status'] === 'accepted' ? 'menerima' : 'menolak') .
            " pengajuan mentoring \"{$mentoringRequest->topic}\".",
            route('mentoring.show', $mentoringRequest));

        return back()->with('status', 'Tanggapan terkirim ke mahasiswa.');
    }
}
