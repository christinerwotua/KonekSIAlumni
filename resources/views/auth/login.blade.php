<x-layouts.guest title="Masuk">
    <main class="flex min-h-screen items-center justify-center px-4 py-10">
        <div class="w-full max-w-[480px] rounded-lg border border-line-soft bg-white p-8 shadow-sm md:p-10">
            <div class="text-center">
                <a href="{{ route('landing') }}" class="text-[40px] font-bold leading-tight text-primary-dark">KonekSI</a>
                <p class="mt-1">Alumni Network Portal</p>
            </div>

            @include('auth.partials.role-tabs', ['route' => 'login'])

            @include('partials.flash', ['hideErrorSummary' => true])

            <form method="POST" action="{{ route('login.store') }}" class="space-y-5">
                @csrf
                <input type="hidden" name="role" value="{{ $role }}">

                <x-input name="email" type="email" label="Email" placeholder="Masukkan email Anda" autocomplete="email" required autofocus />
                <x-input name="password" type="password" label="Password" placeholder="Masukkan password" autocomplete="current-password" required />

                <label class="flex items-center gap-2 text-sm">
                    <input type="checkbox" name="remember" value="1" class="size-4 rounded border-line text-primary-dark">
                    Ingat saya
                </label>

                <x-button type="submit" variant="dark" class="w-full">
                    Masuk sebagai {{ $role === 'mahasiswa' ? 'Mahasiswa' : 'Alumni' }} <x-icon name="arrow-right" class="size-4" />
                </x-button>
            </form>

            <p class="mt-8 text-center">
                Belum punya akun?
                <a href="{{ route('register', ['role' => $role]) }}" class="font-semibold text-primary hover:underline">Daftar</a>
            </p>
        </div>
    </main>
</x-layouts.guest>
