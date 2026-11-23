@props(['variant' => 'muted'])

@php
    $variants = [
        'muted' => 'border-line bg-surface-2',
        'white' => 'border-line bg-white shadow-xs',
    ];
@endphp

<div {{ $attributes->merge(['class' => 'rounded-xl border p-6 md:p-8 ' . ($variants[$variant] ?? $variants['muted'])]) }}>
    {{ $slot }}
</div>
