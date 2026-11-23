<x-layouts.app title="Talent Pool">
    @if (auth()->user()->isMahasiswa())
        {{-- Versi mahasiswa: profil sendiri di atas --}}
        <h1 class="text-3xl font-semibold text-ink md:text-[32px]">Profil Talent Anda</h1>
        <section class="mt-6 flex flex-col gap-6 rounded-xl border border-line bg-surface p-6 shadow-sm md:flex-row md:items-center md:justify-between md:p-8">
            <div class="flex items-center gap-5">
                <x-avatar :user="auth()->user()" size="size-20" text="text-2xl" />
                <div>
                    <p class="text-2xl font-semibold text-ink">{{ auth()->user()->name }}</p>
                    <p>{{ auth()->user()->mahasiswaProfile?->study_program }} &bull; NIM {{ auth()->user()->mahasiswaProfile?->nim }}</p>
                    <div class="mt-2 flex flex-wrap gap-2">
                        @if ($myProfile?->headline)
                            <span class="rounded-full bg-[#BFE9FD] px-3 py-1 text-xs font-medium text-[#214C5C]">{{ $myProfile->headline }}</span>
                        @endif
                        @foreach (array_slice($myProfile?->skillList() ?? [], 0, 4) as $skill)
                            <x-chip variant="gray" class="bg-surface-2!">{{ $skill }}</x-chip>
                        @endforeach
                        @if ($myProfile)
                            <x-chip :variant="$myProfile->is_visible ? 'success' : 'warning'">{{ $myProfile->is_visible ? 'Tampil di Talent Pool' : 'Draf (belum tampil)' }}</x-chip>
                        @else
                            <x-chip variant="warning">Belum dibuat</x-chip>
                        @endif
                    </div>
                </div>
            </div>
            <div class="flex gap-3">
                @if ($myProfile)
                    <x-button :href="route('talent.show', $myProfile)" variant="ghost" class="py-2.5! text-sm">Lihat</x-button>
                @endif
                <x-button :href="route('talent.edit')" variant="outline" class="py-2.5! text-sm"><x-icon name="pencil" class="size-4" /> Edit Profil</x-button>
            </div>
        </section>

        <div class="mt-12 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
            <h2 class="text-2xl font-semibold text-ink md:text-[32px]">Talent Lainnya</h2>
        </div>
    @else
        <x-page-header title="Talent Pool" subtitle="Temukan mahasiswa berbakat sesuai keahlian yang Anda butuhkan." />
    @endif

    <form method="GET" action="{{ route('talent.index') }}" class="relative mt-6">
        <x-icon name="search" class="absolute top-1/2 left-4 size-5 -translate-y-1/2 text-gray-500" />
        <input type="search" name="q" value="{{ request('q') }}" placeholder="Cari nama, peran, atau keahlian..."
            class="w-full rounded-xl border border-line bg-[#F3F4F5] py-3.5 pr-4 pl-12 text-ink placeholder:text-gray-500 focus:border-primary-dark focus:ring-2 focus:ring-primary/20 focus:outline-none md:max-w-md">
    </form>

    @if ($talents->isEmpty())
        <x-empty-state class="mt-8" icon="academic" title="Belum ada talent yang tampil"
            message="Profil muncul di sini setelah mahasiswa mengaktifkan opsi tampil di Talent Pool." />
    @else
        <div class="mt-8 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
            @foreach ($talents as $talent)
                @include('talent.partials-card', ['talent' => $talent])
            @endforeach
        </div>
        <div class="mt-8">{{ $talents->links() }}</div>
    @endif
</x-layouts.app>
