<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class LoginController extends Controller
{
    public function create(Request $request): View
    {
        $role = $request->query('role') === User::ROLE_MAHASISWA ? User::ROLE_MAHASISWA : User::ROLE_ALUMNI;

        return view('auth.login', ['role' => $role]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'role' => ['required', Rule::in([User::ROLE_MAHASISWA, User::ROLE_ALUMNI])],
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (! Auth::attempt(['email' => $data['email'], 'password' => $data['password']], $request->boolean('remember'))) {
            return back()->withErrors(['email' => 'Email atau password salah.'])->onlyInput('email', 'role');
        }

        if ($request->user()->role !== $data['role']) {
            $actual = $request->user()->role;
            Auth::logout();

            return back()->withErrors(['email' => "Akun ini terdaftar sebagai {$actual}. Pilih tab " . ucfirst($actual) . '.'])
                ->onlyInput('email', 'role');
        }

        $request->session()->regenerate();

        return redirect()->intended(route('dashboard'));
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
