<x-layouts.app title="Alumni Directory">
    <div class="grid gap-8 lg:grid-cols-[256px_1fr]">
        {{-- Filter --}}
        <aside class="h-fit rounded-xl bg-[#F3F4F5] p-6">
            <form method="GET" action="{{ route('alumni.index') }}" class="space-y-5">
                <p class="flex items-center gap-2 text-sm font-semibold tracking-wide"><x-icon name="search" class="size-4" /> FILTERS</p>
                <input type="hidden" name="q" value="{{ request('q') }}">
                <x-select name="batch" label="Angkatan (tahun lulus)" placeholder="Semua angkatan"
                    :options="$batches->mapWithKeys(fn ($y) => [$y => 'Class of ' . $y])->all()" :selected="request('batch')" />
                <x-button type="submit" class="w-full py-2.5! text-sm">Terapkan</x-button>
                <a href="{{ route('alumni.index') }}" class="block rounded-lg bg-[#8EE3FF] py-2.5 text-center text-sm font-semibold text-[#00667C] hover:opacity-90">Reset Filters</a>
                @if (auth()->user()->isMahasiswa())
                    <a href="{{ route('connections.index') }}" class="flex items-center justify-center gap-2 text-sm font-semibold text-primary-dark hover:underline"><x-icon name="users" class="size-4" /> Koneksi Saya</a>
                @else
                    <a href="{{ route('connections.index') }}" class="flex items-center justify-center gap-2 text-sm font-semibold text-primary-dark hover:underline"><x-icon name="user-plus" class="size-4" /> Permintaan Koneksi</a>
                @endif
            </form>
        </aside>

        <div>
            <h1 class="text-3xl font-semibold text-ink md:text-[32px]">Alumni Directory</h1>
            <p class="mt-1">Terhubung dengan {{ number_format($total) }} alumni di jejaring KonekSI.</p>

            <form method="GET" action="{{ route('alumni.index') }}" class="relative mt-6">
                <input type="hidden" name="batch" value="{{ request('batch') }}">
                <x-icon name="search" class="absolute top-1/2 left-4 size-5 -translate-y-1/2 text-gray-500" />
                <input type="search" name="q" value="{{ request('q') }}" placeholder="Cari nama, perusahaan, atau jabatan..."
                    class="w-full rounded-xl border border-line bg-white py-4 pr-4 pl-12 text-ink placeholder:text-gray-500 focus:border-primary-dark focus:ring-2 focus:ring-primary/20 focus:outline-none">
            </form>

            @if ($alumni->isEmpty())
                <x-empty-state class="mt-8" title="Alumni tidak ditemukan" message="Coba kata kunci atau filter angkatan yang lain." />
            @else
                <div class="mt-8 grid gap-6 sm:grid-cols-2 xl:grid-cols-3">
                    @foreach ($alumni as $alumnus)
                        <article class="flex flex-col items-center rounded-xl border border-line bg-white p-6 text-center">
                            <span class="rounded-full border-2 border-[#BEE9FF] p-0.5"><x-avatar :user="$alumnus" size="size-[88px]" text="text-2xl" /></span>
                            <h3 class="mt-4 text-xl font-semibold text-ink md:text-2xl">{{ $alumnus->name }}</h3>
                            <div class="mt-2 flex flex-wrap items-center justify-center gap-2">
                                @if ($year = $alumnus->alumniProfile?->graduation_year)
                                    <x-chip variant="blue">Class of '{{ substr($year, -2) }}</x-chip>
                                @endif
                                @if ($alumnus->mentorProfile)
                                    <x-chip variant="cyan">Mentor</x-chip>
                                @endif
                            </div>
                            <p class="mt-3 font-semibold text-ink">{{ $alumnus->alumniProfile?->job_title ?: 'Alumni Sistem Informasi' }}</p>
                            <p class="text-sm">{{ $alumnus->alumniProfile?->company ?: '-' }}</p>
                            <a href="{{ route('alumni.show', $alumnus) }}" class="mt-5 w-full rounded-lg border border-primary-dark py-2 text-sm font-semibold text-primary-dark hover:bg-primary-dark/5">View Profile</a>
                        </article>
                    @endforeach
                </div>
                <div class="mt-8">{{ $alumni->links() }}</div>
            @endif
        </div>
    </div>
</x-layouts.app>
