<!DOCTYPE html>
{{-- class scroll-smooth bikin semua anchor link (#themes, #pricing, dll) otomatis smooth-scroll --}}
<html lang="id" class="scroll-smooth">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    {{-- title bisa diganti tiap halaman lewat @section('title', '...') --}}
    <title>@yield('title', 'Undanganku - Undangan Digital Modern')</title>

    {{-- Google Fonts buat Playfair Display (font serif di heading) --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">

    {{-- compile CSS & JS lewat Vite --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- style bawaan Livewire, wajib ada biar komponen livewire tampil bener --}}
    @livewireStyles
</head>

<body class="antialiased text-gray-900 bg-white">
    {{-- konten tiap halaman diisi lewat @section('content') --}}
    @yield('content')

    {{-- script Livewire, termasuk Alpine.js otomatis kebawa dari sini --}}
    @livewireScripts
</body>

</html>