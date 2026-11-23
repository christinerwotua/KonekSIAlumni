<?php

namespace App\Http\Controllers;

use App\Models\TalentDocument;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TalentProfileController extends Controller
{
    public function edit(Request $request): View
    {
        $profile = $request->user()->talentProfile()->firstOrCreate([], ['is_visible' => false]);
        $profile->load('documents');

        return view('talent.edit', [
            'profile' => $profile,
            'user' => $request->user()->load('mahasiswaProfile'),
            'documentTypes' => TalentDocument::TYPES,
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'headline' => ['nullable', 'string', 'max:255'],
            'skills' => ['nullable', 'string', 'max:1000'],
            'bio' => ['nullable', 'string', 'max:3000'],
            'experience' => ['nullable', 'string', 'max:3000'],
        ], [], ['headline' => 'peran saat ini', 'skills' => 'keahlian', 'bio' => 'ringkasan profesional', 'experience' => 'pengalaman']);

        $user = $request->user();
        $user->update(['name' => $data['name']]);

        $user->talentProfile()->updateOrCreate([], [
            'headline' => $data['headline'] ?? null,
            'skills' => $data['skills'] ?? null,
            'bio' => $data['bio'] ?? null,
            'experience' => $data['experience'] ?? null,
            'is_visible' => $request->boolean('is_visible'),
        ]);

        return redirect()->route('talent.index')->with('status', 'Profil talent berhasil disimpan.');
    }
}
