<x-layouts.app title="Account Settings">
    <h1 class="text-3xl font-bold text-ink md:text-[40px]">Account Settings</h1>
    <p class="mt-1">Kelola informasi profil dan keamanan akun Anda.</p>

    <div class="mt-8 grid gap-6 lg:grid-cols-[1fr_360px]">
        <form method="POST" action="{{ route('account.update') }}" enctype="multipart/form-data" class="rounded-lg border border-line bg-white p-6">
            @csrf @method('PUT')
            <h2 class="flex items-center gap-2 border-b border-line pb-3 text-2xl font-semibold text-ink"><x-icon name="user" /> Basic Information</h2>

            <div class="mt-5 flex items-center gap-4">
                <x-avatar :user="$user" size="size-20" text="text-2xl" />
                <div class="flex-1">
                    <x-input name="photo" type="file" label="Foto profil" accept="image/*" class="file:mr-3 file:rounded file:border-0 file:bg-primary/10 file:px-3 file:py-1 file:text-primary-dark" hint="JPG/PNG, maksimal 2MB." />
                </div>
            </div>

            <div class="mt-5 grid gap-5 md:grid-cols-2">
                <div class="md:col-span-2"><x-input name="name" label="Nama Lengkap" :value="$user->name" required /></div>
                <div class="md:col-span-2"><x-input name="email" type="email" label="Email" :value="$user->email" required /></div>

                @if ($user->isAlumni())
                    <x-select name="graduation_year" label="Tahun Lulus" placeholder="Pilih tahun" :selected="$user->alumniProfile?->graduation_year"
                        :options="collect(range(date('Y'), 1980))->mapWithKeys(fn ($y) => [$y => $y])->all()" />
                    <x-input name="company" label="Perusahaan" :value="$user->alumniProfile?->company" placeholder="Tempat bekerja saat ini" />
                    <div class="md:col-span-2"><x-input name="job_title" label="Jabatan" :value="$user->alumniProfile?->job_title" placeholder="Contoh: Data Analyst" /></div>
                    <div class="md:col-span-2"><x-textarea name="bio" label="Tentang saya" rows="4" :value="$user->alumniProfile?->bio" placeholder="Ceritakan pengalaman dan minat Anda" /></div>
                @else
                    <x-input name="nim" label="NIM" :value="$user->mahasiswaProfile?->nim" required />
                    <x-input name="study_program" label="Program Studi" :value="$user->mahasiswaProfile?->study_program" required />
                @endif
            </div>

            <x-button type="submit" variant="dark" class="mt-6 py-2.5! text-sm">Save Changes</x-button>
        </form>

        <div class="space-y-6">
            <form method="POST" action="{{ route('account.password') }}" class="rounded-lg border border-line bg-white p-6">
                @csrf @method('PUT')
                <h2 class="flex items-center gap-2 border-b border-line pb-3 text-xl font-semibold text-ink"><x-icon name="lock" /> Ganti Password</h2>
                <div class="mt-4 space-y-4">
                    <x-input name="current_password" type="password" label="Password saat ini" autocomplete="current-password" required />
                    <x-input name="password" type="password" label="Password baru" autocomplete="new-password" required />
                    <x-input name="password_confirmation" type="password" label="Ulangi password baru" autocomplete="new-password" required />
                </div>
                <x-button type="submit" variant="outline" class="mt-5 w-full py-2.5! text-sm">Ganti Password</x-button>
            </form>

            <section class="rounded-lg border border-line bg-white p-6">
                <h2 class="flex items-center gap-2 border-b border-line pb-3 text-xl font-semibold text-ink"><x-icon name="link" /> Pintasan</h2>
                <ul class="mt-4 space-y-3 text-sm font-semibold text-primary-dark">
                    @if ($user->isAlumni())
                        <li><a href="{{ route('mentor-profile.edit') }}" class="hover:underline">{{ $user->mentorProfile ? 'Edit profil mentor' : 'Daftar menjadi mentor' }}</a></li>
                        <li><a href="{{ route('jobs.index', ['mine' => 1]) }}" class="hover:underline">Lowongan yang saya posting</a></li>
                        <li><a href="{{ route('alumni.show', $user) }}" class="hover:underline">Lihat profil publik saya</a></li>
                    @else
                        <li><a href="{{ route('talent.edit') }}" class="hover:underline">Edit profil talent &amp; unggah CV</a></li>
                        <li><a href="{{ route('connections.index') }}" class="hover:underline">Koneksi saya</a></li>
                    @endif
                </ul>
            </section>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="inline-flex w-full items-center justify-center gap-2 rounded-lg bg-[#BA1A1A] px-6 py-3 text-sm font-semibold text-white hover:bg-[#9A1414]">
                    <x-icon name="logout" class="size-4" /> Keluar
                </button>
            </form>
        </div>
    </div>
</x-layouts.app>
