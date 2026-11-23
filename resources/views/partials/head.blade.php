<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
<meta name="csrf-token" content="{{ csrf_token() }}">
<meta name="theme-color" content="#006481">
<meta name="description" content="KonekSI Alumni: jejaring alumni dan mahasiswa Sistem Informasi.">
<title>{{ $title ? $title . ' | ' : '' }}KonekSI Alumni</title>

{{-- PWA --}}
<link rel="manifest" href="/manifest.webmanifest">
<link rel="icon" href="/icons/icon-192.png" type="image/png">
<link rel="apple-touch-icon" href="/icons/apple-touch-icon.png">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-title" content="KonekSI">

@fonts
@vite(['resources/css/app.css', 'resources/js/app.js'])
