<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <title>Coming Soon — Qariyb Blog</title>
        <meta name="description" content="We're crafting thoughtful stories on community building, event technology, and the Muslim tech ecosystem. Be the first to read them.">

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
        {{-- Header --}}
        <header x-data="darkMode" class="sticky top-0 z-50 bg-white/80 dark:bg-zinc-950/80 backdrop-blur-xl border-b border-gray-200 dark:border-white/[0.06]">
            <div class="max-w-site mx-auto px-6 flex items-center justify-between h-16">
                <div class="flex items-center gap-2">
                    <span class="flex items-center">
                        <img src="{{ asset('images/qariyb-logo.svg') }}" alt="Qariyb" class="h-6 dark:hidden">
                        <img src="{{ asset('images/qariyb-logo-light.svg') }}" alt="Qariyb" class="h-6 hidden dark:block">
                    </span>
                    <div class="w-px h-5 bg-gray-300 dark:bg-white/10 mx-1"></div>
                    <span class="text-sm text-gray-400 dark:text-gray-500">Blog</span>
                </div>

                <button @click="toggle()" class="theme-toggle w-8 h-8 flex items-center justify-center rounded-md text-gray-500 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 hover:bg-gray-100 dark:hover:bg-white/[0.06] transition-colors" aria-label="Toggle theme">
                    <svg x-show="isDark" class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="5"/><path d="M12 1v2M12 21v2M4.22 4.22l1.42 1.42M18.36 18.36l1.42 1.42M1 12h2M21 12h2M4.22 19.78l1.42-1.42M18.36 5.64l1.42-1.42"/></svg>
                    <svg x-show="!isDark" x-cloak class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 12.79A9 9 0 1111.21 3 7 7 0 0021 12.79z"/></svg>
                </button>
            </div>
        </header>

        {{-- Main content — vertically centered --}}
        <main class="flex flex-col items-center justify-center px-6 py-16 md:py-24 min-h-[calc(100vh-theme(spacing.16)-theme(spacing.20))]">
            {{-- Decorative grid --}}
            <div class="relative mb-12">
                {{-- Radial glow behind grid --}}
                <div class="absolute inset-0 -inset-x-8 -inset-y-8 bg-radial from-primary-400/10 dark:from-primary-400/5 to-transparent rounded-full blur-2xl"></div>

                {{-- 3x3 grid of purple squares --}}
                <div class="relative grid grid-cols-3 gap-2.5">
                    <div class="w-10 h-10 md:w-12 md:h-12 rounded-lg bg-primary-500/25 dark:bg-primary-500/20"></div>
                    <div class="w-10 h-10 md:w-12 md:h-12 rounded-lg bg-primary-500/10 dark:bg-primary-500/8"></div>
                    <div class="w-10 h-10 md:w-12 md:h-12 rounded-lg bg-primary-500/20 dark:bg-primary-500/15"></div>
                    <div class="w-10 h-10 md:w-12 md:h-12 rounded-lg bg-primary-500/8 dark:bg-primary-500/5"></div>
                    <div class="w-10 h-10 md:w-12 md:h-12 rounded-lg bg-primary-500/20 dark:bg-primary-500/15"></div>
                    <div class="w-10 h-10 md:w-12 md:h-12 rounded-lg bg-primary-500/10 dark:bg-primary-500/8"></div>
                    <div class="w-10 h-10 md:w-12 md:h-12 rounded-lg bg-primary-500/15 dark:bg-primary-500/10"></div>
                    <div class="w-10 h-10 md:w-12 md:h-12 rounded-lg bg-primary-500/5 dark:bg-primary-500/5"></div>
                    <div class="w-10 h-10 md:w-12 md:h-12 rounded-lg bg-primary-500/25 dark:bg-primary-500/20"></div>
                </div>
            </div>

            {{-- Headline --}}
            <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-gray-100 text-center mb-4">Something great is brewing.</h1>

            {{-- Subtitle --}}
            <p class="text-base text-gray-500 dark:text-gray-400 text-center max-w-md leading-relaxed mb-10">
                We're crafting thoughtful stories on community building, event technology, and the Muslim tech ecosystem. Be the first to read them.
            </p>

            {{-- Newsletter form --}}
            <div class="w-full max-w-md">
                <livewire:newsletter-form :compact="true" />
            </div>

            {{-- "In the meantime" divider --}}
            <div class="flex items-center gap-4 mt-14 mb-6 w-full max-w-xs">
                <div class="flex-1 h-px bg-gray-200 dark:bg-white/[0.06]"></div>
                <span class="text-xs text-gray-400 dark:text-gray-500 whitespace-nowrap">In the meantime</span>
                <div class="flex-1 h-px bg-gray-200 dark:bg-white/[0.06]"></div>
            </div>

            {{-- Social links --}}
            <div class="flex items-center gap-6">
                <a href="https://x.com/qariyb" target="_blank" class="inline-flex items-center gap-1.5 text-sm text-gray-400 dark:text-gray-500 hover:text-gray-900 dark:hover:text-gray-100 transition-colors">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                    @qariyb
                </a>
                <a href="https://qariyb.com" target="_blank" class="inline-flex items-center gap-1.5 text-sm text-gray-400 dark:text-gray-500 hover:text-gray-900 dark:hover:text-gray-100 transition-colors">
                    qariyb.com
                    <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><path d="M7 17L17 7"/><path d="M7 7h10v10"/></svg>
                </a>
            </div>
        </main>

        {{-- Footer --}}
        <footer class="py-6 border-t border-gray-200 dark:border-white/[0.06]">
            <div class="max-w-site mx-auto px-6 text-center">
                <span class="text-[13px] text-gray-400 dark:text-gray-500">&copy; {{ date('Y') }} Qariyb &middot; <a href="https://qariyb.com" target="_blank" class="hover:text-gray-900 dark:hover:text-gray-100 transition-colors">qariyb.com</a></span>
            </div>
        </footer>

        @livewireScripts
    </body>
</html>
