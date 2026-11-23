@php $alumnus = $mentor->alumni; @endphp

<x-layouts.app title="Pengajuan Mentoring">
    <x-back-link :href="route('mentoring.index')">Kembali ke Mentoring</x-back-link>
    <h1 class="text-3xl font-semibold text-ink md:text-[32px]">Formulir Pengajuan Mentoring</h1>
    <p class="mt-2 max-w-[760px]">Lengkapi formulir di bawah ini. Informasi ini membantu mentor memahami kebutuhan dan ekspektasi Anda.</p>

    <div class="mt-8 grid gap-6 lg:grid-cols-[1fr_363px]">
        <form method="POST" action="{{ route('mentoring.store', $mentor) }}" class="space-y-6">
            @csrf
            <section class="rounded-lg border border-line bg-white p-6">
                <h2 class="border-b border-[#E1E3E4] pb-3 text-2xl font-semibold text-ink">1. Tujuan Mentoring</h2>
                <div class="mt-5 space-y-5">
                    <x-textarea name="goal" label="Apa tujuan utama Anda mengikuti sesi mentoring ini? *" rows="4"
                        placeholder="Jelaskan singkat apa yang ingin Anda capai, juga latar belakang atau pengalaman Anda saat ini..." required />
                    <x-input name="topic" label="Topik spesifik yang ingin dibahas *" placeholder="Contoh: Persiapan karir Data Science, Review CV" required />
                </div>
            </section>

            <section class="rounded-lg border border-line bg-white p-6">
                <h2 class="border-b border-[#E1E3E4] pb-3 text-2xl font-semibold text-ink">2. Dokumen Pendukung</h2>
                <div class="mt-5">
                    @if ($documents->isEmpty())
                        <p class="text-sm">Belum ada CV/portofolio. <a href="{{ route('talent.edit') }}" class="font-semibold text-primary-dark hover:underline">Unggah di Profil Talent</a> (opsional).</p>
                    @else
                        <x-select name="cv_id" label="Lampirkan CV / Portofolio (opsional)" placeholder="Tanpa lampiran"
                            :options="$documents->mapWithKeys(fn ($d) => [$d->id => $d->typeLabel() . ' - ' . $d->file_name])->all()" />
                    @endif
                </div>
            </section>

            <section class="rounded-lg border border-line bg-white p-6">
                <h2 class="border-b border-[#E1E3E4] pb-3 text-2xl font-semibold text-ink">3. Ketersediaan Waktu</h2>
                <div class="mt-5">
                    <x-input name="proposed_schedule" type="datetime-local" label="Pilih jadwal yang diharapkan *" :min="now()->format('Y-m-d\TH:i')" required />
                </div>
            </section>

            <div class="flex justify-end gap-3">
                <x-button :href="route('mentoring.index')" variant="outline" class="py-2.5! text-sm">Batal</x-button>
                <x-button type="submit" variant="dark" class="py-2.5! text-sm">Kirim Pengajuan</x-button>
            </div>
        </form>

        <aside class="h-fit rounded-lg border border-line bg-white p-6 shadow-sm">
            <p class="text-sm font-semibold tracking-wide">INFORMASI MENTOR</p>
            <div class="mt-4 flex items-center gap-4">
                <x-avatar :user="$alumnus" size="size-16" text="text-lg" />
                <div>
                    <p class="text-xl font-semibold text-ink">{{ $alumnus->name }}</p>
                    <p class="text-sm">{{ $alumnus->headline() ?: 'Alumni' }}</p>
                </div>
            </div>
            <div class="mt-4 space-y-4 border-t border-[#E1E3E4] pt-4">
                <div>
                    <p class="text-xs font-medium">Bidang Keahlian</p>
                    <div class="mt-2 flex flex-wrap gap-2">
                        @foreach ($mentor->expertiseList() as $skill)
                            <x-chip variant="blue">{{ $skill }}</x-chip>
                        @endforeach
                    </div>
                </div>
                @if ($year = $alumnus->alumniProfile?->graduation_year)
                    <div>
                        <p class="text-xs font-medium">Alumni</p>
                        <p class="text-sm text-ink">Angkatan lulus {{ $year }}</p>
                    </div>
                @endif
            </div>
        </aside>
    </div>
</x-layouts.app>
