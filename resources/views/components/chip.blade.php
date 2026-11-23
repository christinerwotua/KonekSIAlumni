@props(['variant' => 'gray'])

@php
    $variants = [
        'gray' => 'bg-surface-3 text-muted',
        'blue' => 'bg-[#BEE9FF] text-[#001F2A]',
        'cyan' => 'bg-[#8EE3FF] text-[#00667C]',
        'primary' => 'bg-primary/10 text-primary',
        'success' => 'bg-emerald-100 text-emerald-800',
        'danger' => 'bg-red-100 text-red-700',
        'warning' => 'bg-amber-100 text-amber-800',
    ];
@endphp

<span {{ $attributes->merge(['class' => 'inline-flex items-center rounded-full px-3 py-1 text-xs font-medium ' . ($variants[$variant] ?? $variants['gray'])]) }}>{{ $slot }}</span>
