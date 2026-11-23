@php
    $icons = ['job_referral' => 'briefcase', 'mentoring' => 'chat', 'connection' => 'user-plus'];
@endphp

<x-layouts.app title="Notifikasi">
    <div class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <h1 class="text-3xl font-bold text-primary md:text-[40px]">Notifications</h1>
            <p class="mt-1">Pantau aktivitas terbaru di jejaringmu.</p>
        </div>
        @if ($notifications->total())
            <form method="POST" action="{{ route('notifications.read-all') }}">
                @csrf
                <button type="submit" class="inline-flex items-center gap-2 rounded-lg px-3 py-2 text-sm font-semibold text-primary-dark hover:bg-primary/5"><x-icon name="check" class="size-4" /> Tandai semua sudah dibaca</button>
            </form>
        @endif
    </div>

    @if ($notifications->isEmpty())
        <x-empty-state icon="bell" title="Belum ada notifikasi" message="Notifikasi tentang koneksi, lamaran, dan mentoring akan muncul di sini." />
    @else
        <div class="overflow-hidden rounded-xl border border-line bg-white">
            @foreach ($notifications as $notification)
                <a href="{{ route('notifications.show', $notification) }}" @class([
                    'flex gap-4 border-b border-line p-5 last:border-b-0 transition',
                    'bg-[#BEE9FF]/60 hover:bg-[#BEE9FF]' => ! $notification->is_read,
                    'hover:bg-surface' => $notification->is_read,
                ])>
                    <span class="flex size-10 shrink-0 items-center justify-center rounded-full bg-white text-primary-dark ring-1 ring-line">
                        <x-icon :name="$icons[$notification->type] ?? 'bell'" />
                    </span>
                    <div class="min-w-0 flex-1">
                        <div class="flex items-start justify-between gap-3">
                            <p @class(['text-sm text-[#121C28]', 'font-semibold' => ! $notification->is_read])>{{ $notification->title }}</p>
                            <span class="shrink-0 text-xs font-medium">{{ $notification->created_at?->diffForHumans() }}</span>
                        </div>
                        <p class="mt-1 text-sm">{{ $notification->message }}</p>
                    </div>
                </a>
            @endforeach
        </div>
        <div class="mt-6">{{ $notifications->links() }}</div>
    @endif
</x-layouts.app>
