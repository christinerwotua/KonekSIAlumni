@php
    $user = $talent->user;
    $isMine = $user->id == auth()->id();
@endphp

<x-layouts.app :title="$user->name">
    <x-back-link :href="route('talent.index')">Kembali ke Talent Pool</x-back-link>

    <div class="grid gap-6 lg:grid-cols-[363px_1fr]">
        <aside class="h-fit rounded-xl border border-line bg-white p-8 text-center">
            <x-avatar :user="$user" size="size-32" text="text-4xl" class="mx-auto ring-4 ring-[#BEE9FF]" />
            <h1 class="mt-4 text-2xl font-semibold text-ink">{{ $user->name }}</h1>
            <p class="mt-1">{{ $talent->headline ?: 'Mahasiswa' }} | {{ $user->mahasiswaProfile?->study_program }}</p>
            @unless ($talent->is_visible)
                <x-chip variant="warning" class="mt-3">Draf: belum tampil di Talent Pool</x-chip>
            @endunless

            <div class="mt-6 space-y-3">
                @if ($isMine)
                    <x-button :href="route('talent.edit')" variant="dark" class="w-full"><x-icon name="pencil" /> Edit Profil</x-button>
                @else
                    <x-button href="mailto:{{ $user->email }}" variant="dark" class="w-full"><x-icon name="mail" /> Hubungi</x-button>
                @endif
            </div>

            <ul class="mt-6 space-y-3 border-t border-line pt-6 text-left">
                <li class="flex items-center gap-3"><span class="rounded-lg bg-surface-2 p-1.5"><x-icon name="mail" class="size-4" /></span> <span class="truncate">{{ $user->email }}</span></li>
                <li class="flex items-center gap-3"><span class="rounded-lg bg-surface-2 p-1.5"><x-icon name="academic" class="size-4" /></span> NIM {{ $user->mahasiswaProfile?->nim ?: '-' }}</li>
            </ul>
        </aside>

        <div class="space-y-6">
            <section class="rounded-xl border border-line bg-white p-6 md:p-8">
                <h2 class="flex items-center gap-2 text-2xl font-semibold text-ink"><x-icon name="user" /> Professional Summary</h2>
                <p class="mt-4 whitespace-pre-line">{{ $talent->bio ?: 'Belum ada ringkasan.' }}</p>
            </section>

            <section class="rounded-xl border border-line bg-white p-6 md:p-8">
                <h2 class="flex items-center gap-2 text-2xl font-semibold text-ink"><x-icon name="check-circle" /> Skills &amp; Specialties</h2>
                <div class="mt-4 flex flex-wrap gap-3">
                    @forelse ($talent->skillList() as $i => $skill)
                        <span @class([
                            'rounded-full px-4 py-2',
                            'bg-[#007EA3] text-white' => $i < 3,
                            'bg-[#8EE3FF] text-[#00667C]' => $i >= 3,
                        ])>{{ $skill }}</span>
                    @empty
                        <p>Belum ada keahlian.</p>
                    @endforelse
                </div>
            </section>

            <section class="rounded-xl border border-line bg-white p-6 md:p-8">
                <h2 class="flex items-center gap-2 text-2xl font-semibold text-ink"><x-icon name="briefcase" /> Experience History</h2>
                <p class="mt-4 border-l-2 border-line pl-4 whitespace-pre-line">{{ $talent->experience ?: 'Belum ada pengalaman yang ditulis.' }}</p>
            </section>

            <section class="rounded-xl border border-line bg-white p-6 md:p-8">
                <h2 class="flex items-center gap-2 text-2xl font-semibold text-ink"><x-icon name="document" /> Documents</h2>
                @if ($talent->documents->isEmpty())
                    <p class="mt-4">Belum ada dokumen.</p>
                @else
                    <div class="mt-4 grid gap-4 sm:grid-cols-2">
                        @foreach ($talent->documents as $document)
                            <a href="{{ route('talent.documents.download', $document) }}" class="flex items-center gap-4 rounded-lg border border-line p-4 hover:border-primary">
                                <span class="rounded bg-[#FFDAD6] p-3 text-red-700"><x-icon name="document" /></span>
                                <span class="min-w-0 flex-1">
                                    <span class="block text-ink">{{ $document->typeLabel() }}</span>
                                    <span class="block truncate text-sm">{{ $document->file_name }}</span>
                                </span>
                                <x-icon name="download" class="size-4" />
                            </a>
                        @endforeach
                    </div>
                @endif
            </section>
        </div>
    </div>
</x-layouts.app>
