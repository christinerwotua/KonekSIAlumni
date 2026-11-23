<x-layouts.app title="Mentoring">
    <section class="relative overflow-hidden rounded-xl bg-linear-to-r from-[#E5EEFF] to-[#BEE9FF] p-6 md:p-12">
        <h1 class="max-w-[620px] text-3xl font-bold text-primary-dark md:text-[40px] md:leading-tight">Empower the Next Generation of Alumni</h1>
        <p class="mt-4 max-w-[620px] text-lg">Bagikan keahlianmu, bimbing mahasiswa melewati masa transisi karir, dan bangun koneksi profesional di jejaring KonekSI.</p>
        <x-button :href="route('mentor-profile.edit')" variant="dark" class="mt-6 py-3! text-sm">
            {{ $mentorProfile ? 'Edit Profil Mentor' : 'Apply to be a Mentor' }}
        </x-button>
    </section>

    @if ($mentorProfile)
        <section class="mt-8 rounded-xl border border-line bg-surface p-6">
            <p class="text-sm font-semibold tracking-wide">PROFIL MENTOR ANDA</p>
            <div class="mt-3 flex flex-wrap gap-2">
                @foreach ($mentorProfile->expertiseList() as $skill)
                    <x-chip variant="cyan">{{ $skill }}</x-chip>
                @endforeach
            </div>
            @if ($mentorProfile->description)
                <p class="mt-3">{{ $mentorProfile->description }}</p>
            @endif
        </section>

        <section class="mt-10">
            <h2 class="text-2xl font-semibold text-ink">Pengajuan Masuk</h2>
            @if ($requests->isEmpty())
                <x-empty-state class="mt-4" icon="chat" title="Belum ada pengajuan" message="Pengajuan mentoring dari mahasiswa akan muncul di sini." />
            @else
                <div class="mt-4 divide-y divide-line overflow-hidden rounded-xl border border-line bg-white">
                    @foreach ($requests as $req)
                        <a href="{{ route('mentoring.show', $req) }}" class="flex flex-col gap-3 p-5 hover:bg-surface sm:flex-row sm:items-center sm:justify-between">
                            <div class="flex items-center gap-4">
                                <x-avatar :user="$req->mahasiswa" />
                                <div class="min-w-0">
                                    <p class="font-semibold text-ink">{{ $req->mahasiswa->name }} &middot; <span class="font-normal">{{ $req->topic }}</span></p>
                                    <p class="text-sm">Jadwal usulan: {{ $req->proposed_schedule?->translatedFormat('d M Y, H:i') }} &bull; diajukan {{ $req->created_at?->diffForHumans() }}</p>
                                </div>
                            </div>
                            <x-status-chip :status="$req->status" />
                        </a>
                    @endforeach
                </div>
            @endif
        </section>
    @else
        <x-empty-state class="mt-8" icon="user-plus" title="Anda belum menjadi mentor"
            message="Isi bidang keahlian Anda supaya mahasiswa bisa mengajukan sesi mentoring.">
            <x-button :href="route('mentor-profile.edit')" variant="dark">Apply to be a Mentor</x-button>
        </x-empty-state>
    @endif
</x-layouts.app>
