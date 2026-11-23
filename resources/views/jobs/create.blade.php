<x-layouts.app title="Tambah Job Referral">
    <x-back-link :href="route('jobs.index')">Kembali ke Job Referral</x-back-link>
    <h1 class="text-3xl font-semibold text-primary-dark md:text-[32px]">Tambah Job Referral</h1>
    <p class="mt-1">Bagikan peluang karier dengan komunitas KonekSI.</p>

    <form method="POST" action="{{ route('jobs.store') }}" class="mt-8 space-y-6">
        @csrf
        <section class="rounded-lg border border-[#E1E3E4] bg-white p-6">
            <h2 class="border-b border-[#E1E3E4] pb-3 text-2xl font-semibold text-ink">Informasi Dasar</h2>
            <div class="mt-5 grid gap-5 md:grid-cols-2">
                <x-input name="company" label="Nama Perusahaan *" placeholder="Contoh: PT KonekSI Teknologi" required />
                <x-input name="title" label="Posisi / Jabatan *" placeholder="Contoh: Software Engineer" required />
                <x-input name="location" label="Lokasi Kerja *" placeholder="Contoh: Jakarta Selatan / Remote" required />
                <x-input name="application_deadline" type="date" label="Batas Waktu Lamaran *" :min="now()->toDateString()" required />
            </div>
        </section>

        <section class="rounded-lg border border-[#E1E3E4] bg-white p-6">
            <h2 class="border-b border-[#E1E3E4] pb-3 text-2xl font-semibold text-ink">Detail Pekerjaan</h2>
            <div class="mt-5 space-y-5">
                <x-textarea name="description" label="Deskripsi Pekerjaan *" rows="5" placeholder="Jelaskan gambaran umum peran ini, tanggung jawab utama, dan tipe pekerjaan (full-time, magang, kontrak)." required />
                <x-textarea name="requirements" label="Kualifikasi / Persyaratan *" rows="5" placeholder="Tulis satu persyaratan per baris" hint="Tulis satu persyaratan per baris supaya tampil sebagai daftar." required />
            </div>
        </section>

        <div class="flex justify-end gap-3 border-t border-[#E1E3E4] pt-6">
            <x-button :href="route('jobs.index')" variant="outline" class="py-2.5! text-sm">Batal</x-button>
            <x-button type="submit" variant="dark" class="py-2.5! text-sm">Publish Lowongan</x-button>
        </div>
    </form>
</x-layouts.app>
