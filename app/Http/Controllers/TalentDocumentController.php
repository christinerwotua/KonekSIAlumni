<?php

namespace App\Http\Controllers;

use App\Models\TalentDocument;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\StreamedResponse;

class TalentDocumentController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        // Kalau PHP menolak file sebelum sampai ke Laravel (ukuran melebihi php.ini,
        // folder sementara tidak ada, dll.), tampilkan alasan aslinya supaya mudah diperbaiki.
        $upload = $request->file('file');
        if ($upload && ! $upload->isValid()) {
            return back()->withErrors([
                'file' => 'Upload gagal: ' . $upload->getErrorMessage()
                    . ' (upload_max_filesize=' . ini_get('upload_max_filesize')
                    . ', post_max_size=' . ini_get('post_max_size') . ')',
            ]);
        }

        $data = $request->validate([
            'type' => ['required', Rule::in(array_keys(TalentDocument::TYPES))],
            'file' => ['required', 'file', 'mimes:pdf,doc,docx', 'max:10240'],
        ], [], ['type' => 'jenis dokumen', 'file' => 'file']);

        $profile = $request->user()->talentProfile()->firstOrCreate([], ['is_visible' => false]);
        $file = $data['file'];

        $profile->documents()->create([
            'type' => $data['type'],
            'file_name' => $file->getClientOriginalName(),
            'file_path' => $file->store('talent-documents', 'local'),
        ]);

        return back()->with('status', 'Dokumen berhasil diunggah.');
    }

    public function download(Request $request, TalentDocument $document): StreamedResponse
    {
        $document->load('talentProfile');
        $owner = $document->talentProfile->user_id == $request->user()->id;

        // Pemilik selalu bisa; orang lain hanya jika profilnya aktif di Talent Pool
        // atau dokumen dilampirkan ke lamaran/pengajuan mentoring yang ditujukan kepadanya.
        $allowed = $owner
            || $document->talentProfile->is_visible
            || \App\Models\JobApplication::where('cv_id', $document->id)
                ->whereHas('jobReferral', fn ($q) => $q->where('alumni_id', $request->user()->id))->exists()
            || \App\Models\MentoringRequest::where('cv_id', $document->id)
                ->whereHas('mentor', fn ($q) => $q->where('alumni_id', $request->user()->id))->exists();

        abort_unless($allowed, 403);
        abort_unless(Storage::disk('local')->exists($document->file_path), 404, 'File tidak ditemukan.');

        return Storage::disk('local')->download($document->file_path, $document->file_name);
    }

    public function destroy(Request $request, TalentDocument $document): RedirectResponse
    {
        abort_unless($document->talentProfile->user_id == $request->user()->id, 403);

        // Lepaskan dulu dari lamaran/pengajuan yang memakai dokumen ini (foreign key cv_id).
        \App\Models\JobApplication::where('cv_id', $document->id)->update(['cv_id' => null]);
        \App\Models\MentoringRequest::where('cv_id', $document->id)->update(['cv_id' => null]);

        Storage::disk('local')->delete($document->file_path);
        $document->delete();

        return back()->with('status', 'Dokumen dihapus.');
    }
}
