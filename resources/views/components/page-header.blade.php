@props(['title', 'subtitle' => null])

<div class="mb-8 flex flex-col gap-4 md:flex-row md:items-end md:justify-between">
    <div>
        <h1 class="text-3xl font-bold text-ink md:text-[40px] md:leading-tight">{{ $title }}</h1>
        @if ($subtitle)
            <p class="mt-2 text-base md:text-lg">{{ $subtitle }}</p>
        @endif
    </div>
    @isset($actions)
        <div class="flex flex-wrap gap-3">{{ $actions }}</div>
    @endisset
</div>
