<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class AccountController extends Controller
{
    public function edit(Request $request): View
    {
        return view('account.edit', [
            'user' => $request->user()->load(['alumniProfile', 'mahasiswaProfile', 'mentorProfile', 'talentProfile']),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $user = $request->user();

        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'photo' => ['nullable', 'image', 'max:2048'],
        ];

        if ($user->isAlumni()) {
            $rules += [
                'graduation_year' => ['nullable', 'integer', 'between:1960,' . (date('Y') + 1)],
                'company' => ['nullable', 'string', 'max:255'],
                'job_title' => ['nullable', 'string', 'max:255'],
                'bio' => ['nullable', 'string', 'max:3000'],
            ];
        } else {
            $rules += [
                'nim' => ['required', 'string', 'max:20', Rule::unique('mahasiswa_profiles', 'nim')->ignore($user->id, 'user_id')],
                'study_program' => ['required', 'string', 'max:255'],
            ];
        }

        $data = $request->validate($rules, [], [
            'name' => 'nama lengkap', 'photo' => 'foto profil', 'graduation_year' => 'tahun lulus',
            'company' => 'perusahaan', 'job_title' => 'jabatan', 'study_program' => 'program studi', 'nim' => 'NIM',
        ]);

        if ($request->hasFile('photo')) {
            if ($user->profile_photo) {
                Storage::disk('local')->delete($user->profile_photo);
            }
            $user->profile_photo = $request->file('photo')->store('profile-photos', 'local');
        }

        $user->fill(['name' => $data['name'], 'email' => $data['email']])->save();

        if ($user->isAlumni()) {
            $user->alumniProfile()->updateOrCreate([], [
                'graduation_year' => $data['graduation_year'] ?? null,
                'company' => $data['company'] ?? null,
                'job_title' => $data['job_title'] ?? null,
                'bio' => $data['bio'] ?? null,
            ]);
        } else {
            $user->mahasiswaProfile()->updateOrCreate([], [
                'nim' => $data['nim'],
                'study_program' => $data['study_program'],
            ]);
        }

        return back()->with('status', 'Perubahan akun disimpan.');
    }

    public function updatePassword(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', Password::min(8)],
        ], [], ['current_password' => 'password saat ini', 'password' => 'password baru']);

        $request->user()->update(['password' => $data['password']]);

        return back()->with('status', 'Password berhasil diganti.');
    }
}
