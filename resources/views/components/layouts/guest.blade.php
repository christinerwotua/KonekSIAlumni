@props(['title' => null])

<!DOCTYPE html>
<html lang="id">
<head>
    @include('partials.head', ['title' => $title])
</head>
<body class="min-h-screen bg-linear-to-br from-page to-[#E6F0FB] font-sans text-muted antialiased">
    {{ $slot }}
    @include('partials.sw')
</body>
</html>
