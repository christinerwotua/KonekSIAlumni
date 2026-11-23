@if (session('status'))
    <div class="mb-6 flex items-start gap-3 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800" role="status">
        <x-icon name="check-circle" class="mt-0.5 size-5 shrink-0" />
        <span>{{ session('status') }}</span>
    </div>
@endif
@if ($errors->any() && ! isset($hideErrorSummary))
    <div class="mb-6 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700" role="alert">
        Ada isian yang perlu diperbaiki. Cek kolom yang ditandai merah.
    </div>
@endif
