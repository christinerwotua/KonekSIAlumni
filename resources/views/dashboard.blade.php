<x-layouts.app title="Dashboard">
    {{-- Hero --}}
    <section class="relative overflow-hidden rounded-xl bg-linear-to-r from-primary/95 via-primary-dark/90 to-primary/60">
        <div class="absolute inset-0 opacity-20" style="background-image: radial-gradient(circle at 80% 20%, #fff 0, transparent 40%), radial-gradient(circle at 10% 90%, #fff 0, transparent 35%);"></div>
        <div class="relative px-6 py-12 md:px-16 md:py-20">
            <h1 class="max-w-[520px] text-3xl font-bold leading-tight text-white md:text-[40px]">
                Selamat datang kembali, {{ \Illuminate\Support\Str::of($user->name)->before(' ') }}!
            </h1>
            <p class="mt-4 max-w-[500px] text-lg text-white/90">
                @if ($user->isAlumni())
                    Bagikan peluang karir, bimbing mahasiswa, dan temukan talenta terbaik di jejaring KonekSI.
                @else
                    Terhubung dengan alumni, temukan referal kerja eksklusif, dan ajukan sesi mentoring.
                @endif
            </p>
            <div class="mt-8 flex flex-wrap gap-4">
                <x-button variant="light" :href="route('alumni.index')"><x-icon name="users" /> Explore Directory</x-button>
                <a href="{{ $user->isMahasiswa() ? route('talent.edit') : route('account.edit') }}" class="inline-flex items-center gap-2 rounded-lg border border-white/90 px-6 py-3 font-semibold text-white hover:bg-white/10">
                    <x-icon name="pencil" /> Update Profile
                </a>
            </div>
        </div>
    </section>

    @if ($unread)
        <a href="{{ route('notifications.index') }}" class="mt-6 flex items-center justify-between rounded-lg border border-primary/30 bg-primary/5 px-4 py-3 text-sm text-primary-dark hover:bg-primary/10">
            <span class="flex items-center gap-2"><x-icon name="bell" /> Anda punya {{ $unread }} notifikasi baru.</span>
            <x-icon name="arrow-right" class="size-4" />
        </a>
    @endif

    {{-- Quick Access --}}
    <section class="mt-12">
        <h2 class="text-2xl font-semibold text-ink md:text-[32px]">Quick Access</h2>
        <div class="mt-6 grid gap-6 md:grid-cols-3">
            @php
                $quick = [
                    ['users', 'bg-primary/10 text-primary', 'Alumni Directory', 'Terhubung kembali dengan alumni dan temukan mentor di industrimu.', 'Browse Now', route('alumni.index')],
                    ['academic', 'bg-[#00677D]/10 text-[#00677D]', 'Talent Pool',
                        $user->isAlumni() ? 'Sedang merekrut? Lihat profil, keahlian, dan CV mahasiswa di satu tempat.' : 'Tampilkan profil, keahlian, dan CV-mu supaya mudah ditemukan alumni.',
                        $user->isAlumni() ? 'View Talent' : 'Kelola Profil', route('talent.index')],
                    ['chat', 'bg-[#386172]/10 text-[#386172]', 'Mentoring',
                        $user->isAlumni() ? 'Berbagi pengalaman dengan menjadi mentor bagi mahasiswa.' : 'Ajukan sesi mentoring 1-on-1 dengan alumni berpengalaman.',
                        'Join Program', route('mentoring.index')],
                ];
            @endphp
            @foreach ($quick as [$icon, $color, $name, $text, $cta, $url])
                <a href="{{ $url }}" class="group flex flex-col rounded-xl border border-line {{ $user->isAlumni() ? 'bg-surface-2' : 'bg-surface' }} p-8 transition hover:border-primary">
                    <span class="flex size-12 items-center justify-center rounded-lg {{ $color }}"><x-icon :name="$icon" class="size-7" /></span>
                    <h3 class="mt-4 text-2xl font-semibold text-ink">{{ $name }}</h3>
                    <p class="mt-2 grow">{{ $text }}</p>
                    <span class="mt-6 inline-flex items-center gap-2 text-primary group-hover:underline">{{ $cta }} <x-icon name="chevron-right" class="size-4" /></span>
                </a>
            @endforeach
        </div>
    </section>

    {{-- Job Referral Highlights --}}
    <section class="mt-12">
        <div class="flex items-end justify-between gap-4">
            <div>
                <h2 class="text-2xl font-semibold text-ink md:text-[32px]">Job Referral Highlights</h2>
                <p class="mt-1">Peluang eksklusif yang direkomendasikan alumni KonekSI.</p>
            </div>
            <a href="{{ route('jobs.index') }}" class="hidden shrink-0 items-center gap-2 text-primary hover:underline sm:inline-flex">View All Jobs <x-icon name="arrow-right" class="size-4" /></a>
        </div>

        @if ($jobs->isEmpty())
            <x-empty-state class="mt-6" icon="briefcase" title="Belum ada lowongan dibuka"
                :message="$user->isAlumni() ? 'Jadilah yang pertama membagikan peluang karir.' : 'Cek lagi nanti, alumni akan membagikan lowongan di sini.'">
                @if ($user->isAlumni())
                    <x-button :href="route('jobs.create')"><x-icon name="plus" /> Post Referral</x-button>
                @endif
            </x-empty-state>
        @else
            <div class="mt-6 grid gap-6 md:grid-cols-3">
                @foreach ($jobs as $job)
                    @include('jobs.partials.card', ['job' => $job])
                @endforeach
            </div>
        @endif
    </section>
</x-layouts.app>
