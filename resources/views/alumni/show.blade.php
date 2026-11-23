@php $profile = $alumnus->alumniProfile; @endphp

<x-layouts.app :title="$alumnus->name">
    <x-back-link :href="$backUrl && str_starts_with($backUrl, url('/')) ? $backUrl : route('alumni.index')">
        {{ $backUrl ? 'Kembali' : 'Kembali ke Direktori' }}
    </x-back-link>

    <div class="grid gap-6 lg:grid-cols-[352px_1fr]">
        <div class="space-y-6">
            {{-- Kartu profil --}}
            <section class="overflow-hidden rounded-xl border border-line bg-white">
                <div class="h-24 bg-primary-dark/10"></div>
                <div class="-mt-16 px-6 pb-6 text-center">
                    <x-avatar :user="$alumnus" size="size-32" text="text-4xl" class="mx-auto border-4 border-white" />
                    <h1 class="mt-4 text-2xl font-semibold text-ink">{{ $alumnus->name }}</h1>
                    <p class="mt-1">{{ $alumnus->headline() ?: 'Alumni Sistem Informasi' }}</p>

                    @if (auth()->user()->isMahasiswa())
                        <div class="mt-6 flex gap-3">
                            @if (! $connection || $connection->status === 'rejected')
                                <form method="POST" action="{{ route('connections.store', $alumnus) }}" class="flex-1">
                                    @csrf
                                    <x-button type="submit" variant="dark" class="w-full py-2.5! text-sm"><x-icon name="user-plus" class="size-4" /> Connect</x-button>
                                </form>
                            @else
                                <span class="flex flex-1 items-center justify-center gap-2 rounded-lg bg-surface-2 py-2.5 text-sm font-semibold">
                                    <x-icon name="{{ $connection->status === 'accepted' ? 'check' : 'clock' }}" class="size-4" />
                                    {{ $connection->status === 'accepted' ? 'Terhubung' : 'Menunggu' }}
                                </span>
                            @endif
                            <a href="mailto:{{ $alumnus->email }}" class="flex flex-1 items-center justify-center gap-2 rounded-lg border border-primary-dark py-2.5 text-sm font-semibold text-primary-dark hover:bg-primary-dark/5"><x-icon name="mail" class="size-4" /> Message</a>
                        </div>
                    @endif
                </div>
            </section>

            {{-- Kontak --}}
            <section class="rounded-xl border border-line bg-white p-6">
                <p class="text-sm font-semibold tracking-wide">CONTACT INFO</p>
                <dl class="mt-4 space-y-4">
                    <div>
                        <dt class="text-sm text-[#6E797F]">Email</dt>
                        <dd><a href="mailto:{{ $alumnus->email }}" class="text-primary-dark hover:underline">{{ $alumnus->email }}</a></dd>
                    </div>
                    <div>
                        <dt class="text-sm text-[#6E797F]">Class of</dt>
                        <dd class="text-ink">{{ $profile?->graduation_year ?: '-' }}</dd>
                    </div>
                </dl>
            </section>
        </div>

        <div class="space-y-6">
            <section class="rounded-xl border border-line bg-white p-6 md:p-8">
                <h2 class="text-2xl font-semibold text-ink">About</h2>
                <p class="mt-4 text-lg whitespace-pre-line">{{ $profile?->bio ?: 'Alumni ini belum menulis deskripsi.' }}</p>
            </section>

            <section class="rounded-xl border border-line bg-white p-6 md:p-8">
                <h2 class="flex items-center gap-2 text-2xl font-semibold text-ink"><x-icon name="briefcase" /> Experience</h2>
                @if ($profile?->job_title || $profile?->company)
                    <div class="mt-6 border-l-2 border-primary-dark pl-4">
                        <p class="font-bold text-ink">{{ $profile->job_title ?: 'Profesional' }}</p>
                        <p class="text-primary-dark">{{ $profile->company ?: '-' }}</p>
                        <p class="text-sm text-[#6E797F]">Posisi saat ini</p>
                    </div>
                @else
                    <p class="mt-4">Belum ada informasi pekerjaan.</p>
                @endif
            </section>

            @if ($alumnus->mentorProfile)
                <section class="rounded-xl border border-line bg-white p-6 md:p-8">
                    <h2 class="flex items-center gap-2 text-2xl font-semibold text-ink"><x-icon name="chat" /> Skills &amp; Expertise</h2>
                    <div class="mt-4 flex flex-wrap gap-2">
                        @foreach ($alumnus->mentorProfile->expertiseList() as $skill)
                            <span class="rounded-full border border-primary-dark bg-primary-dark/10 px-4 py-1 text-sm font-semibold text-[#007EA3]">{{ $skill }}</span>
                        @endforeach
                    </div>
                    @if ($alumnus->mentorProfile->description)
                        <p class="mt-4">{{ $alumnus->mentorProfile->description }}</p>
                    @endif
                    @if (auth()->user()->isMahasiswa())
                        <x-button :href="route('mentoring.create', $alumnus->mentorProfile)" variant="outline" class="mt-6 py-2.5! text-sm">Ajukan Mentoring</x-button>
                    @endif
                </section>
            @endif

            @if ($alumnus->jobReferrals->isNotEmpty())
                <section class="rounded-xl border border-line bg-white p-6 md:p-8">
                    <h2 class="text-2xl font-semibold text-ink">Job Referral dari {{ \Illuminate\Support\Str::of($alumnus->name)->before(' ') }}</h2>
                    <ul class="mt-4 divide-y divide-line">
                        @foreach ($alumnus->jobReferrals as $job)
                            <li class="flex items-center justify-between gap-4 py-3">
                                <div>
                                    <p class="font-semibold text-ink">{{ $job->title }}</p>
                                    <p class="text-sm">{{ $job->company }} &bull; {{ $job->location }}</p>
                                </div>
                                <a href="{{ route('jobs.show', $job) }}" class="shrink-0 text-sm font-semibold text-primary-dark hover:underline">Lihat</a>
                            </li>
                        @endforeach
                    </ul>
                </section>
            @endif
        </div>
    </div>
</x-layouts.app>
