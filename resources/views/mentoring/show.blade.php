@php
    $student = $req->mahasiswa;
    $mentorUser = $req->mentor->alumni;
    $other = $isMentor ? $student : $mentorUser;
@endphp

<x-layouts.app title="Pengajuan Mentoring">
    <x-back-link :href="route('mentoring.index')">Kembali ke Mentoring</x-back-link>

    <article class="mx-auto max-w-[768px] rounded-lg border border-[#E1E3E4] bg-white p-6 md:p-8">
        <header class="flex flex-col gap-4 border-b border-[#E1E3E4] pb-6 sm:flex-row sm:items-start sm:justify-between">
            <div class="flex items-center gap-4">
                <x-avatar :user="$other" size="size-16" text="text-lg" />
                <div>
                    <p class="text-2xl font-semibold text-ink">{{ $other->name }}</p>
                    <p class="text-sm">
                        @if ($isMentor)
                            Mahasiswa {{ $student->mahasiswaProfile?->study_program }} &bull; NIM {{ $student->mahasiswaProfile?->nim }}
                        @else
                            Mentor &bull; {{ $mentorUser->headline() ?: 'Alumni' }}
                        @endif
                    </p>
                </div>
            </div>
            <div class="flex flex-col items-start gap-2 sm:items-end">
                <span class="rounded-full bg-[#007EA3] px-3 py-1 text-xs font-medium text-white">Permintaan Mentoring</span>
                <x-status-chip :status="$req->status" />
                <span class="text-sm text-[#6E797F]">{{ $req->created_at?->translatedFormat('d M Y, H:i') }}</span>
            </div>
        </header>

        <dl class="mt-6 space-y-5 text-ink">
            <div>
                <dt class="text-sm font-semibold text-muted">Topik</dt>
                <dd class="mt-1 text-lg">{{ $req->topic }}</dd>
            </div>
            <div>
                <dt class="text-sm font-semibold text-muted">Tujuan</dt>
                <dd class="mt-1 whitespace-pre-line">{{ $req->goal }}</dd>
            </div>
            <div>
                <dt class="text-sm font-semibold text-muted">Jadwal usulan</dt>
                <dd class="mt-1 flex items-center gap-2"><x-icon name="calendar" class="size-4" /> {{ $req->proposed_schedule?->translatedFormat('l, d F Y \p\u\k\u\l H:i') }}</dd>
            </div>
            @if ($req->cv)
                <div>
                    <dt class="text-sm font-semibold text-muted">Lampiran</dt>
                    <dd class="mt-1"><a href="{{ route('talent.documents.download', $req->cv) }}" class="inline-flex items-center gap-2 font-semibold text-primary-dark hover:underline"><x-icon name="download" class="size-4" /> {{ $req->cv->file_name }}</a></dd>
                </div>
            @endif
            @if ($req->response_message || $req->responded_at)
                <div class="rounded-lg bg-surface p-4">
                    <dt class="text-sm font-semibold text-muted">Tanggapan mentor {{ $req->responded_at ? '(' . $req->responded_at->diffForHumans() . ')' : '' }}</dt>
                    <dd class="mt-1 whitespace-pre-line">{{ $req->response_message ?: '-' }}</dd>
                </div>
            @endif
        </dl>

        @if ($isMentor && $req->status === 'pending')
            <form method="POST" action="{{ route('mentoring.respond', $req) }}" class="mt-8 space-y-4 border-t border-[#E1E3E4] pt-6">
                @csrf @method('PATCH')
                <x-textarea name="response_message" label="Tanggapan untuk mahasiswa" rows="3" placeholder="Contoh: Boleh, siapkan CV yang akan dibahas. Kita bertemu lewat Google Meet." />
                <div class="flex gap-3">
                    <x-button type="submit" name="status" value="accepted" variant="dark" class="py-2.5! text-sm">Terima Permintaan</x-button>
                    <x-button type="submit" name="status" value="rejected" variant="ghost" class="py-2.5! text-sm">Tolak</x-button>
                </div>
            </form>
        @endif
    </article>
</x-layouts.app>
