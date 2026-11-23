<x-layouts.app title="Detail Notifikasi">
    <x-back-link :href="route('notifications.index')">Kembali ke Daftar Notifikasi</x-back-link>

    <article class="mx-auto max-w-[768px] rounded-lg border border-[#E1E3E4] bg-white p-6 md:p-8">
        <header class="flex flex-col gap-3 border-b border-[#E1E3E4] pb-5 sm:flex-row sm:items-center sm:justify-between">
            <h1 class="text-2xl font-semibold text-ink">{{ $notification->title }}</h1>
            <div class="flex items-center gap-3">
                <span class="rounded-full bg-[#007EA3] px-3 py-1 text-xs font-medium text-white">
                    {{ ['job_referral' => 'Job Referral', 'mentoring' => 'Mentoring', 'connection' => 'Koneksi'][$notification->type] ?? 'Info' }}
                </span>
                <span class="text-sm text-[#6E797F]">{{ $notification->created_at?->translatedFormat('d M Y, H:i') }}</span>
            </div>
        </header>
        <p class="mt-6 text-ink">{{ $notification->message }}</p>
        @if ($notification->link)
            <div class="mt-8 border-t border-[#E1E3E4] pt-6">
                <x-button :href="$notification->link" variant="dark" class="py-2.5! text-sm">Buka <x-icon name="arrow-right" class="size-4" /></x-button>
            </div>
        @endif
    </article>
</x-layouts.app>
