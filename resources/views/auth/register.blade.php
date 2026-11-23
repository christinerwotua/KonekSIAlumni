<x-layouts.guest title="Daftar Akun">
    <main class="flex min-h-screen items-center justify-center px-4 py-10">
        <div class="w-full max-w-[480px] rounded-lg border border-line-soft bg-white p-8 shadow-sm md:p-10">
            <div class="text-center">
                <a href="{{ route('landing') }}" class="text-[32px] font-semibold leading-tight text-ink">KonekSI</a>
                <p class="mt-1 text-sm">Buat akun {{ $role === 'mahasiswa' ? 'Mahasiswa' : 'Alumni' }} Anda</p>
            </div>

            @include('auth.partials.role-tabs', ['route' => 'register'])

            <form method="POST" action="{{ route('register.store') }}" class="space-y-4">
                @csrf
                <input type="hidden" name="role" value="{{ $role }}">

                <x-input name="name" label="Nama Lengkap" placeholder="Masukkan nama lengkap" autocomplete="name" required />
                <x-input name="email" type="email" label="Email" placeholder="nama@email.com" autocomplete="email" required />

                @if ($role === 'mahasiswa')
                    <div class="grid gap-4 sm:grid-cols-2">
                        <x-input name="nim" label="NIM" placeholder="5026231001" required />
                        <x-input name="study_program" label="Program Studi" value="Sistem Informasi" required />
                    </div>
                @else
                    <x-select name="graduation_year" label="Tahun Lulus" placeholder="Pilih tahun"
                        :options="collect(range(date('Y'), 1980))->mapWithKeys(fn ($y) => [$y => $y])->all()" required />
                @endif

                <x-input name="password" type="password" label="Password" placeholder="Minimal 8 karakter" autocomplete="new-password" required />
                <x-input name="password_confirmation" type="password" label="Konfirmasi Password" placeholder="Ulangi password" autocomplete="new-password" required />

                <x-button type="submit" class="w-full">Daftar</x-button>
            </form>

            <p class="mt-6 text-center text-sm">
                Sudah punya akun?
                <a href="{{ route('login', ['role' => $role]) }}" class="font-semibold text-primary hover:underline">Masuk</a>
            </p>
        </div>
    </main>
</x-layouts.guest>
