@php $isAlumni = auth()->user()->isAlumni(); @endphp

<x-layouts.app title="Koneksi">
    <x-back-link :href="route('alumni.index')">Kembali ke Direktori</x-back-link>
    <x-page-header :title="$isAlumni ? 'Permintaan Koneksi' : 'Koneksi Saya'"
        :subtitle="$isAlumni ? 'Mahasiswa yang ingin terhubung dengan Anda.' : 'Alumni yang sudah Anda ajak terhubung.'" />

    @if ($connections->isEmpty())
        <x-empty-state icon="users" title="Belum ada koneksi"
            :message="$isAlumni ? 'Permintaan koneksi dari mahasiswa akan muncul di sini.' : 'Buka Direktori Alumni lalu klik Connect pada profil alumni.'">
            @unless ($isAlumni)
                <x-button :href="route('alumni.index')">Buka Direktori</x-button>
            @endunless
        </x-empty-state>
    @else
        <div class="divide-y divide-line overflow-hidden rounded-xl border border-line bg-white">
            @foreach ($connections as $connection)
                @php $other = $isAlumni ? $connection->mahasiswa : $connection->alumni; @endphp
                <div class="flex flex-col gap-4 p-5 sm:flex-row sm:items-center sm:justify-between">
                    <div class="flex items-center gap-4">
                        <x-avatar :user="$other" />
                        <div>
                            <p class="font-semibold text-ink">{{ $other->name }}</p>
                            <p class="text-sm">
                                @if ($isAlumni)
                                    {{ $other->mahasiswaProfile?->study_program }} &bull; NIM {{ $other->mahasiswaProfile?->nim }}
                                @else
                                    {{ $other->headline() ?: 'Alumni' }}
                                @endif
                            </p>
                            <p class="mt-1 text-xs">Diminta {{ $connection->created_at?->diffForHumans() }}</p>
                        </div>
                    </div>
                    <div class="flex flex-wrap items-center gap-2">
                        <x-status-chip :status="$connection->status" />
                        @if ($isAlumni && $connection->status === 'pending')
                            <form method="POST" action="{{ route('connections.update', $connection) }}">
                                @csrf @method('PATCH')
                                <input type="hidden" name="status" value="accepted">
                                <x-button type="submit" variant="dark" class="px-4! py-2! text-sm">Accept</x-button>
                            </form>
                            <form method="POST" action="{{ route('connections.update', $connection) }}">
                                @csrf @method('PATCH')
                                <input type="hidden" name="status" value="rejected">
                                <x-button type="submit" variant="ghost" class="px-4! py-2! text-sm">Decline</x-button>
                            </form>
                        @elseif (! $isAlumni)
                            <a href="{{ route('alumni.show', $other) }}" class="text-sm font-semibold text-primary-dark hover:underline">Lihat profil</a>
                            @if ($connection->status === 'pending')
                                <form method="POST" action="{{ route('connections.destroy', $connection) }}">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-sm text-red-600 hover:underline">Batalkan</button>
                                </form>
                            @endif
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</x-layouts.app>
