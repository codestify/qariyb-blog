<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <title>{{ $title ?? 'Blog — Qariyb' }}</title>
        <meta name="description" content="{{ $metaDescription ?? 'Insights on community building, event technology, and the Muslim tech ecosystem.' }}">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">

        <!-- Favicon -->
        <link rel="icon" type="image/svg+xml" href="{{ asset('images/qariyb-icon.svg') }}">

        @vite(['resources/css/app.css', 'resources/js/app.js'])

        @livewireStyles

        <!-- Prevent FOUC: apply dark mode before paint -->
        <script>
            (function() {
                const theme = localStorage.getItem('theme');
                if (theme === 'dark' || (!theme && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                    document.documentElement.classList.add('dark');
                } else {
                    document.documentElement.classList.remove('dark');
                }
            })();
        </script>
    </head>
    <body class="bg-surface text-gray-900 dark:text-gray-100 font-sans min-h-screen transition-colors">
        @include('partials.nav')

        {{ $slot }}

        @include('partials.footer')

        @livewireScripts
    </body>
</html>
