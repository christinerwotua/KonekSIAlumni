<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MentorProfileController extends Controller
{
    public function edit(Request $request): View
    {
        return view('mentoring.profile', ['profile' => $request->user()->mentorProfile]);
    }

    public function update(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'expertise' => ['required', 'string', 'max:1000'],
            'description' => ['nullable', 'string', 'max:3000'],
        ], [], ['expertise' => 'bidang keahlian', 'description' => 'deskripsi']);

        $request->user()->mentorProfile()->updateOrCreate([], $data);

        return redirect()->route('mentoring.index')->with('status', 'Profil mentor disimpan. Mahasiswa sekarang bisa mengajukan mentoring kepada Anda.');
    }
}
