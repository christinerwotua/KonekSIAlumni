{{-- Kartu lowongan (dipakai di Beranda & halaman Job Referral) --}}
<article class="flex flex-col rounded-xl border border-line bg-white p-6 shadow-xs">
    <div class="flex items-start justify-between">
        <span class="flex size-12 items-center justify-center rounded bg-surface-2 text-xl font-bold text-primary-dark ring-1 ring-line">
            {{ mb_strtoupper(mb_substr($job->company, 0, 1)) }}
        </span>
        @if ($job->status === 'closed')
            <x-status-chip status="closed" />
        @elseif ($job->isNew())
            <x-chip variant="primary" class="font-bold">NEW</x-chip>
        @endif
    </div>

    <h3 class="mt-4 text-xl font-semibold text-ink md:text-2xl">{{ $job->title }}</h3>
    <p class="mt-1 text-primary">{{ $job->company }}</p>

    <div class="mt-3 mb-6 flex flex-wrap gap-2">
        <x-chip><x-icon name="map-pin" class="mr-1 size-3.5" />{{ $job->location }}</x-chip>
        @if ($job->application_deadline)
            <x-chip><x-icon name="calendar" class="mr-1 size-3.5" />s.d. {{ $job->application_deadline->translatedFormat('d M Y') }}</x-chip>
        @endif
    </div>

    <div class="mt-auto flex items-center justify-between gap-3 border-t border-line pt-4">
        <span class="flex min-w-0 items-center gap-2 text-sm">
            <x-avatar :user="$job->alumni" size="size-6" text="text-[10px]" />
            <span class="truncate">Referred by {{ $job->alumni->name }}@if ($year = $job->alumni->alumniProfile?->graduation_year) '{{ substr($year, -2) }}@endif</span>
        </span>
        <x-button :href="route('jobs.show', $job)" class="px-4! py-2! text-sm">Lihat</x-button>
    </div>
</article>
