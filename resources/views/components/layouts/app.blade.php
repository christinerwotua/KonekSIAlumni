@props(['title' => null, 'wide' => false])

@php
    $user = auth()->user();
    $menu = [
        ['label' => 'Dashboard', 'route' => 'dashboard', 'active' => 'dashboard', 'icon' => 'home'],
        ['label' => 'Directory', 'route' => 'alumni.index', 'active' => ['alumni.*', 'connections.*'], 'icon' => 'users'],
        ['label' => 'Talent Pool', 'route' => 'talent.index', 'active' => 'talent.*', 'icon' => 'academic'],
        ['label' => 'Job Referral', 'route' => 'jobs.index', 'active' => ['jobs.*', 'applications.*'], 'icon' => 'briefcase'],
        ['label' => 'Mentoring', 'route' => 'mentoring.index', 'active' => ['mentoring.*', 'mentor-profile.*'], 'icon' => 'chat'],
    ];
    $unread = $user ? $user->inbox()->where('is_read', false)->count() : 0;
@endphp

<!DOCTYPE html>
<html lang="id">
<head>
    @include('partials.head', ['title' => $title])
</head>
<body class="min-h-screen bg-page font-sans text-muted antialiased">

    <header class="sticky top-0 z-30 border-b border-line bg-surface/95 backdrop-blur">
        <nav class="mx-auto flex h-16 max-w-[1200px] items-center justify-between gap-4 px-4 lg:px-12">
            <a href="{{ route('dashboard') }}" class="text-2xl font-bold text-primary-dark">KonekSI</a>

            <div class="hidden items-center gap-8 md:flex">
                @foreach ($menu as $item)
                    @php $active = request()->routeIs(...(array) $item['active']); @endphp
                    <a href="{{ route($item['route']) }}" @class([
                        'border-b-2 pb-1 text-base font-bold transition',
                        'border-primary-dark text-primary-dark' => $active,
                        'border-transparent text-muted hover:text-primary-dark' => ! $active,
                    ])>{{ $item['label'] }}</a>
                @endforeach
            </div>

            <div class="flex items-center gap-1">
                <a href="{{ route('notifications.index') }}" class="relative rounded-full p-2 text-muted hover:bg-surface-2" aria-label="Notifikasi">
                    <x-icon name="bell" />
                    @if ($unread)
                        <span class="absolute top-1 right-1 flex min-w-4 items-center justify-center rounded-full bg-red-600 px-1 text-[10px] font-bold text-white">{{ $unread > 9 ? '9+' : $unread }}</span>
                    @endif
                </a>
                <a href="{{ route('account.edit') }}" class="rounded-full p-2 text-muted hover:bg-surface-2" aria-label="Account Settings">
                    <x-icon name="cog" />
                </a>
                <a href="{{ route('account.edit') }}" class="ml-1" aria-label="Akun saya">
                    <x-avatar :user="$user" size="size-8" text="text-xs" />
                </a>
            </div>
        </nav>
    </header>

    <main @class(['mx-auto px-4 pt-8 pb-28 md:pb-16 lg:px-12', 'max-w-[1200px]' => ! $wide])>
        @include('partials.flash')
        {{ $slot }}
    </main>

    <footer class="hidden border-t border-line bg-surface-2 px-4 py-8 text-center md:block">
        <p class="font-bold text-ink">KonekSI</p>
        <div class="mt-4 flex justify-center gap-6 text-sm">
            <a href="{{ route('landing') }}" class="hover:text-primary-dark">About Us</a>
            <a href="mailto:koneksi.alumni@example.test" class="hover:text-primary-dark">Contact</a>
            <a href="{{ route('landing') }}#privasi" class="hover:text-primary-dark">Privacy Policy</a>
            <a href="{{ route('landing') }}#privasi" class="hover:text-primary-dark">Terms of Service</a>
        </div>
        <p class="mt-4 text-sm">&copy; {{ date('Y') }} KonekSI Alumni Platform. All rights reserved.</p>
    </footer>

    {{-- Navigasi bawah untuk HP (tampilan aplikasi PWA) --}}
    <nav class="fixed inset-x-0 bottom-0 z-30 grid grid-cols-5 border-t border-line bg-surface pb-[env(safe-area-inset-bottom)] md:hidden">
        @foreach ($menu as $item)
            @php $active = request()->routeIs(...(array) $item['active']); @endphp
            <a href="{{ route($item['route']) }}" @class([
                'flex flex-col items-center gap-1 py-2.5 text-[11px] font-semibold',
                'text-primary-dark' => $active,
                'text-muted' => ! $active,
            ])>
                <x-icon :name="$item['icon']" class="size-6" />
                {{ $item['label'] }}
            </a>
        @endforeach
    </nav>

    @include('partials.sw')
</body>
</html>
