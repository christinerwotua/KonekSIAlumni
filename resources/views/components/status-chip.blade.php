@props(['status'])

@php
    $map = [
        'pending' => ['warning', 'Menunggu'],
        'accepted' => ['success', 'Diterima'],
        'rejected' => ['danger', 'Ditolak'],
        'open' => ['success', 'Dibuka'],
        'closed' => ['gray', 'Ditutup'],
    ];
    [$variant, $label] = $map[$status] ?? ['gray', ucfirst($status)];
@endphp

<x-chip :variant="$variant" {{ $attributes }}>{{ $label }}</x-chip>
