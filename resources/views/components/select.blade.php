@props(['label' => null, 'name', 'options' => [], 'placeholder' => null, 'selected' => null])

<div>
    @if ($label)
        <label for="{{ $name }}" class="mb-1.5 block text-sm font-semibold text-ink">{{ $label }}</label>
    @endif

    <select id="{{ $name }}" name="{{ $name }}"
        {{ $attributes->merge(['class' => 'w-full rounded-lg border bg-white px-4 py-2.5 text-sm text-ink focus:border-primary-dark focus:ring-2 focus:ring-primary/20 focus:outline-none ' . ($errors->has($name) ? 'border-red-500' : 'border-line')]) }}>
        @if ($placeholder)
            <option value="">{{ $placeholder }}</option>
        @endif
        @foreach ($options as $value => $text)
            <option value="{{ $value }}" @selected((string) old($name, $selected) === (string) $value)>{{ $text }}</option>
        @endforeach
    </select>

    @error($name)
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
    @enderror
</div>
