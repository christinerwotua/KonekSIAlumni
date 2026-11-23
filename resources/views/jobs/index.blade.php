<x-layouts.app title="Job Referral">
    <x-page-header title="Job Referral" subtitle="Peluang karir yang direkomendasikan langsung oleh alumni.">
        @if (auth()->user()->isAlumni())
            <x-slot:actions>
                <x-button :href="route('jobs.index', ['mine' => 1])" variant="ghost" class="py-2.5! text-sm">Lowongan Saya</x-button>
                <x-button :href="route('jobs.create')" variant="dark" class="py-2.5! text-sm"><x-icon name="plus" class="size-4" /> Post Referral</x-button>
            </x-slot:actions>
        @endif
    </x-page-header>

    <form method="GET" action="{{ route('jobs.index') }}" class="flex flex-col gap-3 sm:flex-row">
        @if (request('mine'))<input type="hidden" name="mine" value="1">@endif
        <div class="relative flex-1">
            <x-icon name="search" class="absolute top-1/2 left-4 size-5 -translate-y-1/2 text-gray-500" />
            <input type="search" name="q" value="{{ request('q') }}" placeholder="Cari posisi, perusahaan, atau lokasi..."
                class="w-full rounded-xl border border-line bg-white py-3.5 pr-4 pl-12 text-ink placeholder:text-gray-500 focus:border-primary-dark focus:ring-2 focus:ring-primary/20 focus:outline-none">
        </div>
        <select name="status" onchange="this.form.submit()" class="rounded-xl border border-line bg-surface px-4 py-3.5 text-sm font-semibold text-ink">
            <option value="">Semua status</option>
            <option value="open" @selected(request('status') === 'open')>Dibuka</option>
            <option value="closed" @selected(request('status') === 'closed')>Ditutup</option>
        </select>
        <x-button type="submit" variant="dark" class="py-3!">Cari</x-button>
    </form>

    @if (request('mine'))
        <p class="mt-4 text-sm">Menampilkan lowongan yang Anda posting. <a href="{{ route('jobs.index') }}" class="font-semibold text-primary-dark hover:underline">Lihat semua</a></p>
    @endif

    @if ($jobs->isEmpty())
        <x-empty-state class="mt-8" icon="briefcase" title="Lowongan tidak ditemukan" message="Coba kata kunci lain atau cek lagi nanti." />
    @else
        <div class="mt-8 grid gap-6 md:grid-cols-2 lg:grid-cols-3">
            @foreach ($jobs as $job)
                <article class="flex flex-col rounded-2xl border border-line bg-white p-6">
                    <div class="flex items-start justify-between">
                        <span class="flex size-12 items-center justify-center rounded-lg bg-[#E5EEFF] text-xl font-bold text-primary-dark">{{ mb_strtoupper(mb_substr($job->company, 0, 1)) }}</span>
                        @if (! $job->isOpen())
                            <x-chip class="bg-line-soft!">Closed</x-chip>
                        @elseif ($job->isNew())
                            <x-chip variant="cyan">New</x-chip>
                        @endif
                    </div>
                    <h3 class="mt-4 text-xl font-semibold text-[#121C28]">{{ $job->title }}</h3>
                    <p class="mt-1 text-sm">{{ $job->company }} &bull; {{ $job->location }}</p>
                    <p class="mt-3 mb-6 flex items-center gap-2 text-sm"><x-avatar :user="$job->alumni" size="size-6" text="text-[10px]" /> Referral oleh {{ $job->alumni->name }}</p>
                    <div class="mt-auto flex items-center justify-between border-t border-line pt-4">
                        <span class="text-xs font-medium">
                            Diposting {{ $job->created_at?->diffForHumans() }}
                            @if (auth()->id() == $job->alumni_id) &bull; {{ $job->applications_count }} pelamar @endif
                        </span>
                        <a href="{{ route('jobs.show', $job) }}" @class(['text-sm font-semibold hover:underline', 'text-primary-dark' => $job->isOpen(), 'text-muted' => ! $job->isOpen()])>Lihat Detail</a>
                    </div>
                </article>
            @endforeach
        </div>
        <div class="mt-8">{{ $jobs->links() }}</div>
    @endif
</x-layouts.app>
