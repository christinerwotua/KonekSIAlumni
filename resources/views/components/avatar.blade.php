@props(['user', 'size' => 'size-12', 'text' => 'text-base'])

@if ($user->photoUrl())
    <img src="{{ $user->photoUrl() }}" alt="{{ $user->name }}" {{ $attributes->merge(['class' => "$size shrink-0 rounded-full object-cover"]) }}>
@else
    <span {{ $attributes->merge(['class' => "$size $text flex shrink-0 items-center justify-center rounded-full bg-primary font-semibold text-white"]) }}>
        {{ $user->initials() }}
    </span>
@endif
