@props(['label' => null, 'name', 'rows' => 4, 'hint' => null])

<div>
    @if ($label)
        <label for="{{ $name }}" class="mb-1.5 block text-sm font-semibold text-ink">{{ $label }}</label>
    @endif

    <textarea id="{{ $name }}" name="{{ $name }}" rows="{{ $rows }}"
        {{ $attributes->except('value')->merge(['class' => 'w-full rounded-lg border bg-white px-4 py-2.5 text-sm text-ink placeholder:text-gray-500 focus:border-primary-dark focus:ring-2 focus:ring-primary/20 focus:outline-none ' . ($errors->has($name) ? 'border-red-500' : 'border-line')]) }}>{{ old($name, $attributes->get('value')) }}</textarea>

    @if ($hint)
        <p class="mt-1 text-xs text-muted">{{ $hint }}</p>
    @endif
    @error($name)
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
    @enderror
</div>
