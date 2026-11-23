@php
    $alumniCount = \App\Models\User::where('role', 'alumni')->count();
    $mentorCount = \App\Models\MentorProfile::count();
    $jobCount = \App\Models\JobReferral::where('status', 'open')->count();
@endphp

<x-layouts.guest title="Jejaring Alumni Sistem Informasi">
    <div class="bg-white">
        {{-- Header --}}
        <header class="sticky top-0 z-30 border-b border-slate-200 bg-white/90 backdrop-blur">
            <div class="mx-auto flex h-20 max-w-[1216px] items-center justify-between px-4">
                <a href="{{ route('landing') }}" class="flex items-center gap-3">
                    <span class="flex size-10 items-center justify-center rounded-lg bg-primary-dark/10 text-primary-dark">
                        <x-icon name="academic" class="size-6" />
                    </span>
                    <span>
                        <span class="block text-xl font-bold leading-tight text-slate-800">KonekSI</span>
                        <span class="block text-xs text-slate-500">Alumni Sistem Informasi</span>
                    </span>
                </a>
                <div class="flex items-center gap-3">
                    @auth
                        <a href="{{ route('dashboard') }}" class="rounded-lg bg-slate-800 px-4 py-2.5 text-sm font-semibold text-white hover:bg-slate-700">Buka Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="hidden px-3 py-2 text-sm font-semibold text-slate-700 hover:text-primary-dark sm:block">Masuk</a>
                        <a href="{{ route('register') }}" class="rounded-lg bg-slate-800 px-4 py-2.5 text-sm font-semibold text-white hover:bg-slate-700">Daftar Sekarang</a>
                    @endauth
                </div>
            </div>
        </header>

        {{-- Hero --}}
        <section class="mx-auto max-w-[1024px] px-4 py-20 text-center md:py-28">
            <span class="inline-flex items-center gap-2 rounded-full border border-slate-200 bg-slate-100 px-4 py-1.5 text-xs font-semibold tracking-wide text-slate-600">
                <x-icon name="users" class="size-4" /> JEJARING KOLABORASI ALUMNI SISTEM INFORMASI ITS
            </span>
            <h1 class="mt-6 text-4xl font-extrabold leading-tight text-slate-900 md:text-6xl">
                Hubungkan Ambisi dan Karir <span class="text-primary-dark">Anda di KonekSI</span>
            </h1>
            <p class="mx-auto mt-6 max-w-[768px] text-lg text-slate-600">
                Satu portal interaktif yang menyatukan alumni Sistem Informasi dengan mahasiswa aktif dalam ekosistem karir,
                bimbingan mentoring 1-on-1, serta rujukan kerja dari dalam jejaring alumni.
            </p>
            <div class="mt-10 flex flex-col justify-center gap-4 sm:flex-row">
                <a href="{{ route('login') }}" class="inline-flex items-center justify-center gap-2 rounded-lg bg-slate-800 px-8 py-3.5 font-semibold text-white hover:bg-slate-700">
                    Masuk &amp; Masuki Portal <x-icon name="arrow-right" class="size-5" />
                </a>
                <a href="{{ route('register') }}" class="inline-flex items-center justify-center rounded-lg border border-slate-300 bg-white px-8 py-3.5 font-semibold text-slate-800 hover:bg-slate-50">
                    Daftar Akun Baru (Register)
                </a>
            </div>
        </section>

        {{-- Fitur --}}
        <section class="bg-white py-16">
            <div class="mx-auto max-w-[1216px] px-4">
                <div class="text-center">
                    <h2 class="text-3xl font-bold text-slate-800">Apa yang Kami Tawarkan di KonekSI?</h2>
                    <p class="mt-3 text-slate-500">Fitur yang dirancang langsung untuk mempercepat langkah karir mahasiswa dan alumni.</p>
                </div>
                @php
                    $features = [
                        ['users', 'bg-blue-100 text-blue-700', 'Direktori Alumni', 'Cari alumni berdasarkan keahlian industri, angkatan, maupun perusahaan tempat mereka bekerja.'],
                        ['briefcase', 'bg-emerald-100 text-emerald-700', 'Rujukan Kerja & Referal', 'Peluang karir jalur prioritas dari alumni yang bekerja di berbagai perusahaan.'],
                        ['chat', 'bg-purple-100 text-purple-700', 'Mentoring Hub 1-on-1', 'Konsultasi langsung bersama mentor: review portofolio, latihan interview, hingga persiapan karir.'],
                        ['academic', 'bg-orange-100 text-orange-700', 'Katalog Pencarian Talenta', 'Membantu alumni dan rekruter menemukan talenta terbaik mahasiswa Sistem Informasi.'],
                    ];
                @endphp
                <div class="mt-12 grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
                    @foreach ($features as [$icon, $color, $name, $text])
                        <div class="rounded-2xl border border-slate-100 bg-slate-50 p-8">
                            <span class="flex size-14 items-center justify-center rounded-xl {{ $color }}"><x-icon :name="$icon" class="size-7" /></span>
                            <h3 class="mt-6 text-xl font-bold text-slate-800">{{ $name }}</h3>
                            <p class="mt-3 text-sm leading-relaxed text-slate-600">{{ $text }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>

        {{-- Metrik --}}
        <section class="mx-auto max-w-[1216px] px-4 py-8">
            <div class="grid gap-8 rounded-3xl bg-slate-800 p-8 md:grid-cols-2 md:items-center md:p-12">
                <div>
                    <p class="text-xs font-bold tracking-widest text-slate-400">REAL-TIME PORTAL METRICS</p>
                    <h2 class="mt-3 text-3xl font-bold text-white md:text-4xl">Kekuatan Jejaring Alumni Riil</h2>
                    <p class="mt-4 text-slate-300">KonekSI mempercepat proses lamaran kerja lewat rujukan alumni dan memberi bimbingan berbasis pengalaman nyata.</p>
                </div>
                <div class="grid grid-cols-3 gap-4">
                    @foreach ([[$alumniCount, 'Alumni Tergabung'], [$mentorCount, 'Mentor Aktif'], [$jobCount, 'Lowongan Dibuka']] as [$number, $label])
                        <div class="rounded-2xl border border-slate-700 bg-slate-800/50 p-4 text-center md:p-6">
                            <p class="text-3xl font-extrabold text-sky-500 md:text-4xl">{{ $number }}</p>
                            <p class="mt-2 text-sm font-medium text-slate-300">{{ $label }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>

        {{-- Cara kerja --}}
        <section id="privasi" class="mx-auto grid max-w-[1216px] gap-12 px-4 py-20 md:grid-cols-2 md:items-center">
            <div>
                <h2 class="text-3xl font-bold text-slate-800">Cara Kerja Platform KonekSI</h2>
                <p class="mt-4 text-slate-600">Sistem dirancang sesederhana mungkin untuk menjaga keamanan pertukaran data rujukan internal. Semua anggota memilih peran yang tepat saat membuat akun.</p>
                <ol class="mt-8 space-y-6">
                    @foreach ([
                        ['Registrasi / Masuk Menggunakan Peran', 'Pilih peran Anda (Alumni atau Mahasiswa Aktif) agar fitur menyesuaikan.'],
                        ['Lengkapi Profil Anda', 'Tulis ringkasan singkat, keahlian, dan unggah CV atau portofolio.'],
                        ['Mulai Membangun Koneksi Nyata', 'Ajukan referal, lamar lowongan, atau jadwalkan sesi mentoring.'],
                    ] as $i => [$step, $text])
                        <li class="flex gap-4">
                            <span class="text-2xl font-extrabold text-primary">{{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}</span>
                            <div>
                                <h3 class="text-lg font-bold text-slate-800">{{ $step }}</h3>
                                <p class="mt-1 text-sm text-slate-600">{{ $text }}</p>
                            </div>
                        </li>
                    @endforeach
                </ol>
            </div>
            <div class="rounded-3xl border border-slate-200 bg-white p-10 shadow-xl shadow-slate-200/60">
                <span class="flex size-14 items-center justify-center rounded-xl border border-indigo-100 bg-indigo-50 text-indigo-600"><x-icon name="shield" class="size-7" /></span>
                <h3 class="mt-8 text-xl font-bold text-slate-800">Privasi &amp; Kehormatan Akademik Terjaga</h3>
                <p class="mt-4 text-sm leading-relaxed text-slate-600">
                    KonekSI tidak mempublikasikan kontak pribadi Anda ke luar platform. Direktori dan Talent Pool hanya bisa diakses
                    oleh anggota yang sudah masuk, dan profil talent hanya tampil jika Anda mengaktifkannya.
                </p>
                <a href="{{ route('register') }}" class="mt-10 flex w-full items-center justify-center rounded-lg bg-slate-800 py-3.5 font-semibold text-white hover:bg-slate-700">Mulai Eksplorasi Sekarang</a>
            </div>
        </section>

        <footer class="border-t border-slate-200 py-8 text-center text-sm text-slate-500">
            &copy; {{ date('Y') }} KonekSI Alumni Platform. Jejaring alumni Sistem Informasi.
        </footer>
    </div>
</x-layouts.guest>
