<x-layouts.app title="Mentoring">
    <section class="grid gap-8 rounded-2xl border border-line bg-white p-6 md:grid-cols-[1fr_320px] md:items-center md:p-10">
        <div>
            <h1 class="text-3xl font-bold text-primary-dark md:text-[40px] md:leading-tight">Elevate Your Career with Mentorship</h1>
            <p class="mt-4 text-lg">Terhubung dengan alumni berpengalaman yang siap membimbing perjalanan profesionalmu, dari review CV sampai persiapan interview.</p>
            <a href="#pengajuan-saya" class="mt-6 inline-flex rounded-lg border border-primary-dark px-5 py-2.5 text-sm font-semibold text-primary-dark hover:bg-primary-dark/5">Lihat pengajuan saya ({{ $myRequests->count() }})</a>
        </div>
        <div class="hidden h-56 items-center justify-center rounded-xl bg-linear-to-br from-[#E5EEFF] to-[#BEE9FF] md:flex">
            <x-icon name="chat" class="size-24 text-primary-dark/60" />
        </div>
    </section>

    <form method="GET" action="{{ route('mentoring.index') }}" class="mt-8 rounded-2xl border border-line bg-white p-4 md:p-6">
        <div class="relative">
            <x-icon name="search" class="absolute top-1/2 left-4 size-5 -translate-y-1/2 text-gray-500" />
            <input type="search" name="q" value="{{ request('q') }}" placeholder="Cari mentor berdasarkan keahlian atau nama..."
                class="w-full rounded-lg border border-line bg-[#F8F9FF] py-3 pr-4 pl-12 text-ink placeholder:text-gray-500 focus:border-primary-dark focus:ring-2 focus:ring-primary/20 focus:outline-none">
        </div>
    </form>

    @if ($mentors->isEmpty())
        <x-empty-state class="mt-8" icon="chat" title="Belum ada mentor" message="Mentor akan muncul setelah alumni mengisi profil mentor." />
    @else
        <div class="mt-8 grid gap-6 md:grid-cols-2 lg:grid-cols-3">
            @foreach ($mentors as $mentor)
                <article class="flex flex-col rounded-2xl border border-line bg-white p-6">
                    <div class="flex items-center gap-4">
                        <x-avatar :user="$mentor->alumni" size="size-16" text="text-lg" class="bg-[#E5EEFF]! text-primary-dark!" />
                        <div class="min-w-0">
                            <h3 class="truncate text-xl font-semibold text-[#121C28]">{{ $mentor->alumni->name }}</h3>
                            <p class="truncate text-sm">{{ $mentor->alumni->headline() ?: 'Alumni' }}</p>
                            @if ($year = $mentor->alumni->alumniProfile?->graduation_year)
                                <p class="text-xs font-medium text-primary-dark">Class of '{{ substr($year, -2) }}</p>
                            @endif
                        </div>
                    </div>
                    @if ($mentor->description)
                        <p class="mt-4 line-clamp-3 text-sm">{{ $mentor->description }}</p>
                    @endif
                    <div class="mt-4 mb-6 flex flex-wrap gap-2">
                        @foreach (array_slice($mentor->expertiseList(), 0, 4) as $skill)
                            <span class="rounded-full bg-[#E5EEFF] px-3 py-1 text-xs font-medium">{{ $skill }}</span>
                        @endforeach
                    </div>
                    <div class="mt-auto flex gap-2">
                        <a href="{{ route('alumni.show', ['user' => $mentor->alumni, 'back' => route('mentoring.index')]) }}" class="rounded-lg border border-line px-3 py-2 text-sm font-semibold text-ink hover:bg-surface">Profil</a>
                        <a href="{{ route('mentoring.create', $mentor) }}" class="flex-1 rounded-lg border border-primary-dark bg-[#F8F9FF] py-2 text-center text-sm font-semibold text-primary-dark hover:bg-primary-dark/5">Book Session</a>
                    </div>
                </article>
            @endforeach
        </div>
        <div class="mt-8">{{ $mentors->links() }}</div>
    @endif

    <section id="pengajuan-saya" class="mt-12 scroll-mt-24">
        <h2 class="text-2xl font-semibold text-ink">Pengajuan Saya</h2>
        @if ($myRequests->isEmpty())
            <p class="mt-3">Belum ada pengajuan mentoring.</p>
        @else
            <div class="mt-4 divide-y divide-line overflow-hidden rounded-xl border border-line bg-white">
                @foreach ($myRequests as $req)
                    <a href="{{ route('mentoring.show', $req) }}" class="flex items-center justify-between gap-4 p-4 hover:bg-surface">
                        <div class="min-w-0">
                            <p class="truncate font-semibold text-ink">{{ $req->topic }}</p>
                            <p class="text-sm">dengan {{ $req->mentor->alumni->name }} &bull; {{ $req->proposed_schedule?->translatedFormat('d M Y, H:i') }}</p>
                        </div>
                        <x-status-chip :status="$req->status" />
                    </a>
                @endforeach
            </div>
        @endif
    </section>
</x-layouts.app>
