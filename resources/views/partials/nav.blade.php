<nav x-data="darkMode" class="sticky top-0 z-50 bg-white/80 dark:bg-zinc-950/80 backdrop-blur-xl border-b border-gray-200 dark:border-white/[0.06]">
    <div class="max-w-site mx-auto px-6 flex items-center justify-between h-16">
        {{-- Brand --}}
        <div class="flex items-center gap-2">
            <a href="{{ route('home') }}" class="flex items-center">
                <img src="{{ asset('images/qariyb-logo.svg') }}" alt="Qariyb" class="h-6 dark:hidden">
                <img src="{{ asset('images/qariyb-logo-light.svg') }}" alt="Qariyb" class="h-6 hidden dark:block">
            </a>
            <div class="w-px h-5 bg-gray-300 dark:bg-white/10 mx-1"></div>
            <span class="text-sm text-gray-400 dark:text-gray-500">Blog</span>
        </div>

        {{-- Desktop Links --}}
        <div class="hidden md:flex items-center gap-6">
            <a href="{{ route('home') }}" class="relative text-sm {{ request()->routeIs('home') ? 'text-gray-900 dark:text-gray-100 nav-link-active' : 'text-gray-500 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 transition-colors' }}">Blog</a>
            <a href="#" class="text-sm text-gray-500 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 transition-colors">Changelog</a>
            <a href="https://qariyb.com" target="_blank" class="text-sm text-gray-500 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 transition-colors">Platform</a>

            {{-- Theme toggle --}}
            <button @click="toggle()" class="theme-toggle w-8 h-8 flex items-center justify-center rounded-md text-gray-500 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 hover:bg-gray-100 dark:hover:bg-white/[0.06] transition-colors" aria-label="Toggle theme">
                <svg x-show="isDark" class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="5"/><path d="M12 1v2M12 21v2M4.22 4.22l1.42 1.42M18.36 18.36l1.42 1.42M1 12h2M21 12h2M4.22 19.78l1.42-1.42M18.36 5.64l1.42-1.42"/></svg>
                <svg x-show="!isDark" x-cloak class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 12.79A9 9 0 1111.21 3 7 7 0 0021 12.79z"/></svg>
            </button>

            <a href="https://qariyb.com/discover" target="_blank" class="inline-flex items-center gap-1.5 px-4 py-2 text-[13px] font-medium bg-primary-500 dark:bg-gray-100 text-white dark:text-gray-900 rounded-md hover:bg-primary-600 dark:hover:bg-white transition-all hover:-translate-y-px">
                Discover Events
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><path d="M7 17L17 7"/><path d="M7 7h10v10"/></svg>
            </a>
        </div>

        {{-- Mobile: theme toggle + hamburger --}}
        <div x-data="{ open: false }" class="flex md:hidden items-center gap-2">
            <button @click="toggle()" class="theme-toggle w-8 h-8 flex items-center justify-center rounded-md text-gray-500 dark:text-gray-400" aria-label="Toggle theme">
                <svg x-show="isDark" class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="5"/><path d="M12 1v2M12 21v2M4.22 4.22l1.42 1.42M18.36 18.36l1.42 1.42M1 12h2M21 12h2M4.22 19.78l1.42-1.42M18.36 5.64l1.42-1.42"/></svg>
                <svg x-show="!isDark" x-cloak class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 12.79A9 9 0 1111.21 3 7 7 0 0021 12.79z"/></svg>
            </button>
            <button @click="open = !open" class="p-2 text-gray-900 dark:text-gray-100" aria-label="Toggle menu">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="18" x2="21" y2="18"/></svg>
            </button>

            {{-- Mobile menu dropdown --}}
            <div x-show="open" x-cloak @click.away="open = false" class="absolute top-16 left-0 right-0 md:hidden border-t border-gray-200 dark:border-white/[0.06] bg-white dark:bg-zinc-900 px-6 py-4 space-y-3">
                <a href="{{ route('home') }}" class="block text-sm font-medium text-gray-900 dark:text-gray-100">Blog</a>
                <a href="#" class="block text-sm text-gray-500 dark:text-gray-400">Changelog</a>
                <a href="https://qariyb.com" target="_blank" class="block text-sm text-gray-500 dark:text-gray-400">Platform</a>
                <a href="https://qariyb.com/discover" target="_blank" class="inline-flex items-center gap-1.5 mt-2 px-4 py-2 text-sm font-medium bg-primary-500 dark:bg-gray-100 text-white dark:text-gray-900 rounded-md">Discover Events</a>
            </div>
        </div>
    </div>
</nav>
