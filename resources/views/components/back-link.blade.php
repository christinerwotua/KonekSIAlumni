@props(['href'])

<a href="{{ $href }}" class="mb-6 inline-flex items-center gap-2 text-sm font-semibold text-primary-dark hover:underline">
    <x-icon name="arrow-left" class="size-4" /> {{ $slot }}
</a>
