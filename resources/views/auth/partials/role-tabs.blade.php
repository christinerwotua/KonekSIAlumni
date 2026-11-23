<div class="my-8 grid grid-cols-2 border-b border-line-soft text-center text-sm font-semibold">
    @foreach (['mahasiswa' => 'Mahasiswa', 'alumni' => 'Alumni'] as $value => $label)
        <a href="{{ route($route, ['role' => $value]) }}" @class([
            'border-b-2 pb-2.5',
            'border-primary-dark text-primary-dark' => $role === $value,
            'border-transparent text-muted hover:text-primary-dark' => $role !== $value,
        ])>{{ $label }}</a>
    @endforeach
</div>
