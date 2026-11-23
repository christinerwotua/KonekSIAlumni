@props(['icon' => 'search', 'title', 'message' => null])

<div {{ $attributes->merge(['class' => 'rounded-xl border border-dashed border-line bg-white px-6 py-12 text-center']) }}>
    <span class="mx-auto flex size-12 items-center justify-center rounded-full bg-primary/10 text-primary">
        <x-icon :name="$icon" class="size-6" />
    </span>
    <p class="mt-4 font-semibold text-ink">{{ $title }}</p>
    @if ($message)
        <p class="mt-1 text-sm">{{ $message }}</p>
    @endif
    @if (! $slot->isEmpty())
        <div class="mt-4">{{ $slot }}</div>
    @endif
</div>
