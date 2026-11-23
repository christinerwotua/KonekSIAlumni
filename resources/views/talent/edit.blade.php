<x-layouts.app title="Edit Profil Talent">
    <x-back-link :href="route('talent.index')">Kembali ke Talent Pool</x-back-link>
    <h1 class="text-2xl font-semibold text-ink">Edit Profil Talent</h1>

    <div class="mt-6 grid gap-6 lg:grid-cols-[1fr_360px]">
        <form method="POST" action="{{ route('talent.update') }}" id="talent-form" class="space-y-6">
            @csrf @method('PUT')

            <section class="rounded-xl border border-l-4 border-line border-l-primary-dark bg-white p-6">
                <h2 class="flex items-center gap-2 text-lg font-semibold text-primary-dark"><x-icon name="user" class="size-4" /> Informasi Pribadi</h2>
                <div class="mt-4 space-y-4">
                    <x-input name="name" label="Nama Lengkap" :value="$user->name" required />
                    <x-input name="headline" label="Peran Saat Ini" :value="$profile->headline" placeholder="Contoh: Frontend Developer" />
                </div>
            </section>

            <section class="rounded-xl border border-l-4 border-line border-l-primary-dark bg-white p-6">
                <h2 class="flex items-center gap-2 text-lg font-semibold text-primary-dark"><x-icon name="check-circle" class="size-4" /> Keahlian &amp; Spesialisasi</h2>
                <div class="mt-4">
                    <x-input name="skills" label="Keahlian (pisahkan dengan koma)" :value="$profile->skills" placeholder="Python, UI Design, Data Analysis" />
                    @if ($profile->skillList())
                        <div class="mt-3 flex flex-wrap gap-2">
                            @foreach ($profile->skillList() as $skill)
                                <span class="rounded-full border border-line bg-[#BFE9FD] px-3 py-1 text-xs font-medium text-[#214C5C]">{{ $skill }}</span>
                            @endforeach
                        </div>
                    @endif
                </div>
            </section>

            <section class="rounded-xl border border-l-4 border-line border-l-primary-dark bg-white p-6">
                <h2 class="flex items-center gap-2 text-lg font-semibold text-primary-dark"><x-icon name="document" class="size-4" /> Ringkasan &amp; Pengalaman</h2>
                <div class="mt-4 space-y-4">
                    <x-textarea name="bio" label="Ringkasan profesional" :value="$profile->bio" rows="4" placeholder="Deskripsikan profil profesional Anda secara singkat" />
                    <x-textarea name="experience" label="Pengalaman" :value="$profile->experience" rows="4" placeholder="Magang, organisasi, proyek, atau asisten praktikum" />
                </div>
            </section>

            <section class="flex items-center justify-between gap-4 rounded-xl border border-l-4 border-line border-l-primary-dark bg-white p-6">
                <div>
                    <h2 class="text-lg font-semibold text-primary-dark">Tampil di Talent Pool</h2>
                    <p class="text-sm">Aktifkan supaya alumni dan rekruter bisa menemukan profil Anda.</p>
                </div>
                <label class="relative inline-flex cursor-pointer items-center">
                    <input type="checkbox" name="is_visible" value="1" class="peer sr-only" @checked(old('is_visible', $profile->is_visible))>
                    <span class="h-7 w-14 rounded-full bg-line transition peer-checked:bg-primary-dark"></span>
                    <span class="absolute left-1 size-5 rounded-full bg-white shadow transition peer-checked:translate-x-7"></span>
                </label>
            </section>

            <div class="flex justify-end gap-3">
                <x-button :href="route('talent.index')" variant="outline" class="py-2.5! text-sm">Batal</x-button>
                <x-button type="submit" variant="dark" class="py-2.5! text-sm">Simpan Perubahan</x-button>
            </div>
        </form>

        {{-- Dokumen pendukung (form terpisah karena unggah file) --}}
        <aside class="h-fit rounded-xl border border-l-4 border-line border-l-primary-dark bg-white p-6">
            <h2 class="flex items-center gap-2 text-lg font-semibold text-primary-dark"><x-icon name="upload" class="size-4" /> Dokumen Pendukung</h2>
            <p class="mt-1 text-sm">CV dipakai saat melamar lowongan dan mengajukan mentoring.</p>

            <form method="POST" action="{{ route('talent.documents.store') }}" enctype="multipart/form-data" class="mt-4 space-y-3">
                @csrf
                <x-select name="type" label="Jenis dokumen" :options="$documentTypes" selected="cv" />
                <x-input name="file" type="file" label="File (PDF/DOC/DOCX, maks. 10MB)" accept=".pdf,.doc,.docx" class="file:mr-3 file:rounded file:border-0 file:bg-primary/10 file:px-3 file:py-1 file:text-primary-dark" required />
                <x-button type="submit" variant="outline" class="w-full py-2.5! text-sm"><x-icon name="upload" class="size-4" /> Unggah</x-button>
            </form>

            <ul class="mt-6 space-y-2">
                @forelse ($profile->documents as $document)
                    <li class="flex items-center gap-3 rounded-lg border border-line bg-surface px-3 py-2">
                        <x-icon name="document" class="size-5 shrink-0 text-primary-dark" />
                        <a href="{{ route('talent.documents.download', $document) }}" class="min-w-0 flex-1 text-sm">
                            <span class="block truncate text-ink">{{ $document->file_name }}</span>
                            <span class="block text-xs">{{ $document->typeLabel() }}</span>
                        </a>
                        <form method="POST" action="{{ route('talent.documents.destroy', $document) }}" onsubmit="return confirm('Hapus dokumen ini?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="rounded p-1 text-muted hover:text-red-600" aria-label="Hapus dokumen"><x-icon name="trash" class="size-4" /></button>
                        </form>
                    </li>
                @empty
                    <li class="text-sm">Belum ada dokumen.</li>
                @endforelse
            </ul>
        </aside>
    </div>
</x-layouts.app>
