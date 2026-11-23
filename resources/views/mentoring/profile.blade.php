<x-layouts.app title="Profil Mentor">
    <x-back-link :href="route('mentoring.index')">Kembali ke Mentoring</x-back-link>

    <div class="mx-auto max-w-[720px] rounded-xl border border-line bg-white p-6 md:p-8">
        <h1 class="text-2xl font-semibold text-ink md:text-[32px]">{{ $profile ? 'Edit Profil Mentor' : 'Daftar Menjadi Mentor' }}</h1>
        <p class="mt-2">Profil ini tampil di Mentoring Hub dan di halaman profil alumni Anda.</p>

        <form method="POST" action="{{ route('mentor-profile.update') }}" class="mt-6 space-y-5">
            @csrf @method('PUT')
            <x-input name="expertise" label="Bidang keahlian (pisahkan dengan koma) *" :value="$profile?->expertise"
                placeholder="Analisis Data, SQL, Persiapan Magang" required />
            <x-textarea name="description" label="Deskripsi singkat" rows="5" :value="$profile?->description"
                placeholder="Ceritakan pengalaman Anda dan topik yang bisa Anda bimbing." />
            <div class="flex justify-end gap-3">
                <x-button :href="route('mentoring.index')" variant="outline" class="py-2.5! text-sm">Batal</x-button>
                <x-button type="submit" variant="dark" class="py-2.5! text-sm">Simpan</x-button>
            </div>
        </form>
    </div>
</x-layouts.app>
