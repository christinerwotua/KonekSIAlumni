{{-- Kartu talent (dipakai di Talent Pool) --}}
@php $skills = $talent->skillList(); @endphp
<article class="flex flex-col rounded-xl border border-line bg-white p-6">
    <div class="flex items-start justify-between">
        <x-avatar :user="$talent->user" size="size-16" text="text-xl" />
        @if ($nim = $talent->user->mahasiswaProfile?->nim)
            <x-chip variant="blue">NIM {{ $nim }}</x-chip>
        @endif
    </div>
    <h3 class="mt-4 text-xl font-semibold text-ink md:text-2xl">{{ $talent->user->name }}</h3>
    <p class="mt-1 text-sm font-semibold text-primary-dark uppercase">{{ $talent->headline ?: 'Mahasiswa' }}</p>
    <div class="mt-4 mb-6 flex flex-wrap gap-2">
        @foreach (array_slice($skills, 0, 3) as $skill)
            <span class="rounded bg-surface-3 px-2 py-1 text-xs font-medium">{{ $skill }}</span>
        @endforeach
        @if (count($skills) > 3)
            <span class="rounded bg-surface-3 px-2 py-1 text-xs font-medium">+{{ count($skills) - 3 }}</span>
        @endif
    </div>
    <div class="mt-auto flex gap-2">
        <a href="{{ route('talent.show', $talent) }}" class="flex-1 rounded-lg border border-primary-dark py-2.5 text-center text-sm font-semibold text-primary-dark hover:bg-primary-dark/5">View Portfolio</a>
        <a href="mailto:{{ $talent->user->email }}" class="flex items-center rounded-lg bg-primary-dark px-3 text-white hover:opacity-90" aria-label="Kirim email ke {{ $talent->user->name }}"><x-icon name="mail" /></a>
    </div>
</article>
