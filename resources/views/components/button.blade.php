@props(['variant' => 'primary', 'href' => null, 'type' => 'button'])

@php
    $variants = [
        'primary' => 'bg-primary text-white hover:bg-primary-dark',
        'dark'    => 'bg-primary-dark text-white hover:bg-primary-dark/90',
        'outline' => 'border border-primary text-primary hover:bg-primary/5',
        'light'   => 'bg-page text-primary hover:bg-white',
        'ghost'   => 'border border-line-soft bg-white text-ink hover:bg-surface',
    ];
    $classes = 'inline-flex items-center justify-center gap-2 rounded-lg px-6 py-3 text-base font-semibold transition '
        . ($variants[$variant] ?? $variants['primary']);
@endphp

@if ($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>{{ $slot }}</a>
@else
    <button type="{{ $type }}" {{ $attributes->merge(['class' => $classes]) }}>{{ $slot }}</button>
@endif
