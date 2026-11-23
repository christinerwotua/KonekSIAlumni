<x-layouts.app title="Apply Job Referral">
    <x-back-link :href="route('jobs.show', $job)">Kembali ke detail lowongan</x-back-link>

    <div class="mx-auto max-w-[672px] rounded-xl border border-line bg-white p-6 shadow-sm md:p-10">
        <h1 class="text-2xl font-semibold text-ink md:text-[32px]">Apply for Job Referral</h1>
        <p class="mt-2">{{ $job->title }} at {{ $job->company }}</p>

        <form method="POST" action="{{ route('applications.store', $job) }}" class="mt-8 space-y-6">
            @csrf
            <div>
                <p class="mb-1.5 text-sm font-semibold text-ink">Resume / CV *</p>
                @if ($documents->isEmpty())
                    <div class="rounded-lg border border-dashed border-line bg-surface p-6 text-center">
                        <x-icon name="upload" class="mx-auto size-6 text-primary-dark" />
                        <p class="mt-2 text-sm">Anda belum mengunggah dokumen.</p>
                        <a href="{{ route('talent.edit') }}" class="mt-1 inline-block text-sm font-semibold text-primary-dark hover:underline">Unggah CV di Profil Talent</a>
                    </div>
                    @error('cv_id')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                @else
                    <div class="space-y-2">
                        @foreach ($documents as $document)
                            <label class="flex cursor-pointer items-center gap-3 rounded-lg border border-line bg-surface px-4 py-3 has-[:checked]:border-primary-dark has-[:checked]:bg-primary/5">
                                <input type="radio" name="cv_id" value="{{ $document->id }}" class="text-primary-dark" @checked(old('cv_id', $documents->firstWhere('type', 'cv')?->id) == $document->id)>
                                <x-icon name="document" class="size-5 text-primary-dark" />
                                <span class="min-w-0 flex-1">
                                    <span class="block truncate text-sm text-ink">{{ $document->file_name }}</span>
                                    <span class="block text-xs">{{ $document->typeLabel() }}</span>
                                </span>
                            </label>
                        @endforeach
                    </div>
                    @error('cv_id')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    <a href="{{ route('talent.edit') }}" class="mt-2 inline-block text-sm text-primary-dark hover:underline">Unggah dokumen lain</a>
                @endif
            </div>

            <x-textarea name="message" label="Kenapa Anda melamar referral ini? *" rows="5"
                placeholder="Jelaskan singkat kenapa Anda cocok dan hubungan Anda dengan pemberi referral..." required />

            <div class="flex justify-end gap-3 border-t border-line pt-6">
                <x-button :href="route('jobs.show', $job)" variant="ghost" class="py-2.5! text-sm">Cancel</x-button>
                <x-button type="submit" variant="dark" class="py-2.5! text-sm">Submit Application</x-button>
            </div>
        </form>
    </div>
</x-layouts.app>
