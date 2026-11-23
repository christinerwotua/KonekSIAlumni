<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class RegisterController extends Controller
{
    public function create(Request $request): View
    {
        $role = $request->query('role') === User::ROLE_MAHASISWA ? User::ROLE_MAHASISWA : User::ROLE_ALUMNI;

        return view('auth.register', ['role' => $role]);
    }

    public function store(Request $request): RedirectResponse
    {
        $isMahasiswa = $request->input('role') === User::ROLE_MAHASISWA;

        $data = $request->validate([
            'role' => ['required', Rule::in([User::ROLE_MAHASISWA, User::ROLE_ALUMNI])],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', Password::min(8)],
            // Mahasiswa: NIM & program studi. Alumni: tahun lulus.
            'nim' => [Rule::requiredIf($isMahasiswa), 'nullable', 'string', 'max:20', 'unique:mahasiswa_profiles,nim'],
            'study_program' => [Rule::requiredIf($isMahasiswa), 'nullable', 'string', 'max:255'],
            'graduation_year' => [Rule::requiredIf(! $isMahasiswa), 'nullable', 'integer', 'between:1960,' . (date('Y') + 1)],
        ], [], [
            'name' => 'nama lengkap',
            'nim' => 'NIM',
            'study_program' => 'program studi',
            'graduation_year' => 'tahun lulus',
        ]);

        $user = DB::transaction(function () use ($data, $isMahasiswa) {
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => $data['password'],
                'role' => $data['role'],
            ]);

            if ($isMahasiswa) {
                $user->mahasiswaProfile()->create([
                    'nim' => $data['nim'],
                    'study_program' => $data['study_program'],
                ]);
            } else {
                $user->alumniProfile()->create(['graduation_year' => $data['graduation_year']]);
            }

            return $user;
        });

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('dashboard')->with('status', 'Akun berhasil dibuat. Selamat datang di KonekSI!');
    }
}
