@php $alumnus = $job->alumni; @endphp

<x-layouts.app :title="$job->title">
    <x-back-link :href="route('jobs.index')">Back to Job Board</x-back-link>

    <div class="grid gap-6 lg:grid-cols-[1fr_347px]">
        <div class="space-y-6">
            <section class="rounded-xl border border-line bg-white p-6 md:p-8">
                <div class="flex flex-col gap-6 sm:flex-row">
                    <span class="flex size-20 shrink-0 items-center justify-center rounded-lg border border-line bg-surface-2 text-3xl font-bold text-primary-dark">{{ mb_strtoupper(mb_substr($job->company, 0, 1)) }}</span>
                    <div>
                        <div class="flex flex-wrap gap-2">
                            <x-status-chip :status="$job->isOpen() ? 'open' : 'closed'" />
                            @if ($job->isNew())<x-chip variant="blue">Baru</x-chip>@endif
                        </div>
                        <h1 class="mt-2 text-2xl font-semibold text-ink md:text-[32px] md:leading-tight">{{ $job->title }}</h1>
                        <div class="mt-3 flex flex-wrap gap-x-5 gap-y-2 text-sm">
                            <span class="flex items-center gap-1.5 font-medium"><x-icon name="building" class="size-4" /> {{ $job->company }}</span>
                            <span class="flex items-center gap-1.5"><x-icon name="map-pin" class="size-4" /> {{ $job->location }}</span>
                            <span class="flex items-center gap-1.5"><x-icon name="clock" class="size-4" /> Diposting {{ $job->created_at?->diffForHumans() }}</span>
                        </div>
                    </div>
                </div>
            </section>

            <section class="rounded-xl border border-line bg-white p-6 md:p-8">
                <h2 class="border-b border-[#E1E3E4] pb-3 text-2xl font-semibold text-ink">Job Description</h2>
                <p class="mt-4 whitespace-pre-line">{{ $job->description }}</p>
            </section>

            <section class="rounded-xl border border-line bg-white p-6 md:p-8">
                <h2 class="flex items-center gap-3 border-b border-[#E1E3E4] pb-3 text-2xl font-semibold text-ink">
                    <span class="flex size-10 items-center justify-center rounded-full bg-[#BEE9FF] text-primary-dark"><x-icon name="check-circle" /></span> Requirements
                </h2>
                <ul class="mt-4 space-y-3">
                    @foreach ($job->requirementList() as $item)
                        <li class="flex gap-3"><x-icon name="check" class="mt-0.5 size-5 shrink-0 text-primary-dark" /> {{ $item }}</li>
                    @endforeach
                </ul>
            </section>

            @if ($isOwner)
                <section class="rounded-xl border border-line bg-white p-6 md:p-8">
                    <h2 class="text-2xl font-semibold text-ink">Pelamar ({{ $applications->count() }})</h2>
                    @if ($applications->isEmpty())
                        <p class="mt-4">Belum ada pelamar.</p>
                    @else
                        <div class="mt-4 divide-y divide-line">
                            @foreach ($applications as $application)
                                <div class="py-4">
                                    <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
                                        <div class="flex items-center gap-3">
                                            <x-avatar :user="$application->mahasiswa" size="size-10" text="text-sm" />
                                            <div>
                                                <p class="font-semibold text-ink">{{ $application->mahasiswa->name }}</p>
                                                <p class="text-sm">{{ $application->mahasiswa->mahasiswaProfile?->study_program }} &bull; melamar {{ $application->created_at?->diffForHumans() }}</p>
                                            </div>
                                        </div>
                                        <x-status-chip :status="$application->status" />
                                    </div>
                                    <p class="mt-3 rounded-lg bg-surface p-3 text-sm whitespace-pre-line">{{ $application->message }}</p>
                                    <div class="mt-3 flex flex-wrap items-center gap-3 text-sm">
                                        @if ($application->cv)
                                            <a href="{{ route('talent.documents.download', $application->cv) }}" class="inline-flex items-center gap-1 font-semibold text-primary-dark hover:underline"><x-icon name="download" class="size-4" /> {{ $application->cv->file_name }}</a>
                                        @endif
                                        @if ($application->mahasiswa->talentProfile?->is_visible)
                                            <a href="{{ route('talent.show', $application->mahasiswa->talentProfile) }}" class="font-semibold text-primary-dark hover:underline">Lihat profil talent</a>
                                        @endif
                                        @if ($application->status === 'pending')
                                            <span class="ml-auto flex gap-2">
                                                @foreach (['accepted' => ['Terima', 'dark'], 'rejected' => ['Tolak', 'ghost']] as $status => [$label, $variant])
                                                    <form method="POST" action="{{ route('applications.update', $application) }}">
                                                        @csrf @method('PATCH')
                                                        <input type="hidden" name="status" value="{{ $status }}">
                                                        <x-button type="submit" :variant="$variant" class="px-4! py-1.5! text-sm">{{ $label }}</x-button>
                                                    </form>
                                                @endforeach
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </section>
            @endif
        </div>

        <aside class="space-y-6">
            <section class="rounded-xl border border-line bg-white p-6">
                @if ($isOwner)
                    <p class="text-ink">Kelola lowongan ini</p>
                    <p class="mt-1 text-sm">Tutup lowongan jika sudah tidak menerima pelamar.</p>
                    <form method="POST" action="{{ route('jobs.status', $job) }}" class="mt-5">
                        @csrf @method('PATCH')
                        <x-button type="submit" :variant="$job->status === 'open' ? 'ghost' : 'dark'" class="w-full py-2.5! text-sm">
                            {{ $job->status === 'open' ? 'Tutup Lowongan' : 'Buka Kembali' }}
                        </x-button>
                    </form>
                @elseif (auth()->user()->isMahasiswa())
                    <p class="text-ink">Apply for this role</p>
                    @if ($myApplication)
                        <p class="mt-1 text-sm">Anda sudah melamar {{ $myApplication->created_at?->diffForHumans() }}.</p>
                        <div class="mt-4 flex items-center gap-2 text-sm">Status: <x-status-chip :status="$myApplication->status" /></div>
                    @elseif ($job->isOpen())
                        <p class="mt-1 text-sm">Pastikan CV di Profil Talent sudah terbaru sebelum melamar.</p>
                        <x-button :href="route('applications.create', $job)" variant="dark" class="mt-5 w-full py-2.5! text-sm">Apply Now <x-icon name="arrow-right" class="size-4" /></x-button>
                    @else
                        <p class="mt-1 text-sm">Lowongan ini sudah ditutup.</p>
                    @endif
                @else
                    <p class="text-ink">Lowongan dari sesama alumni</p>
                    <p class="mt-1 text-sm">Hanya mahasiswa yang bisa melamar lowongan ini.</p>
                @endif
            </section>

            <section class="rounded-xl border border-line bg-white p-6">
                <p class="text-sm font-semibold tracking-wide">REFERRED BY</p>
                <div class="mt-4 flex items-center gap-3">
                    <x-avatar :user="$alumnus" />
                    <div>
                        <p class="font-semibold text-ink">{{ $alumnus->name }}</p>
                        <p class="text-sm">{{ $alumnus->alumniProfile?->graduation_year ? "Class of '" . substr($alumnus->alumniProfile->graduation_year, -2) . ' • ' : '' }}{{ $alumnus->alumniProfile?->job_title ?: 'Alumni' }}</p>
                    </div>
                </div>
                <a href="{{ route('alumni.show', ['user' => $alumnus, 'back' => url()->current()]) }}" class="mt-4 flex items-center gap-2 border-t border-[#E1E3E4] pt-4 text-sm font-semibold text-primary-dark hover:underline">
                    <x-icon name="user" class="size-4" /> Lihat profil pemberi referral
                </a>
            </section>

            <section class="rounded-xl border border-line bg-white p-6">
                <p class="text-sm font-semibold tracking-wide">JOB DETAILS</p>
                <dl class="mt-4 space-y-3 text-sm">
                    <div class="flex justify-between gap-4"><dt>Perusahaan</dt><dd class="text-right font-medium text-ink">{{ $job->company }}</dd></div>
                    <div class="flex justify-between gap-4"><dt>Lokasi</dt><dd class="text-right font-medium text-ink">{{ $job->location }}</dd></div>
                    <div class="flex justify-between gap-4"><dt>Status</dt><dd class="text-right font-medium text-ink">{{ $job->isOpen() ? 'Dibuka' : 'Ditutup' }}</dd></div>
                    <div class="flex justify-between gap-4"><dt>Deadline</dt><dd class="text-right font-medium text-ink">{{ $job->application_deadline?->translatedFormat('d F Y') ?? '-' }}</dd></div>
                </dl>
            </section>
        </aside>
    </div>
</x-layouts.app>
