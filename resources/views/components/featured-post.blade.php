@props(['post'])

<a href="{{ route('article.show', $post->slug) }}" class="group grid grid-cols-1 lg:grid-cols-2 gap-0 bg-surface-alt border border-gray-200 dark:border-white/[0.06] rounded-2xl overflow-hidden hover:border-gray-300 dark:hover:border-white/[0.16] transition-all">
    {{-- Image --}}
    <div class="aspect-[16/10] bg-gradient-to-br from-gray-100 dark:from-zinc-800 to-primary-100 dark:to-primary-400/[0.06] flex items-center justify-center">
        <svg width="120" height="120" viewBox="0 0 120 120" fill="none">
            <rect width="120" height="120" rx="16" class="fill-primary-100 dark:fill-primary-400/[0.05]"/>
            <circle cx="45" cy="50" r="18" class="stroke-primary-300 dark:stroke-primary-400/30" stroke-width="2"/>
            <path d="M60 40L90 80H30L60 40Z" class="stroke-primary-300 dark:stroke-primary-400/30" stroke-width="2"/>
            <circle cx="80" cy="35" r="6" class="fill-primary-200 dark:fill-primary-400/20"/>
        </svg>
    </div>
    {{-- Body --}}
    <div class="p-8 md:p-10 flex flex-col justify-center">
        <span class="featured-dot inline-flex items-center text-xs font-semibold uppercase tracking-widest text-primary-400 dark:text-primary-400 mb-4">Featured</span>
        <h2 class="text-xl md:text-2xl font-bold tracking-tight leading-snug mb-3 text-gray-900 dark:text-gray-100 group-hover:text-primary-500 dark:group-hover:text-primary-400 transition-colors">{{ $post->title }}</h2>
        <p class="text-[15px] text-gray-500 dark:text-gray-400 leading-relaxed mb-6">{{ $post->excerpt }}</p>
        <div class="flex items-center gap-3 text-[13px] text-gray-400 dark:text-gray-500">
            <div class="w-7 h-7 rounded-full bg-primary-100 dark:bg-primary-400/10 border border-gray-200 dark:border-white/[0.06] flex items-center justify-center text-[11px] font-semibold text-primary-400 dark:text-primary-400">{{ $post->author_initials }}</div>
            <span>{{ $post->author_name }}</span>
            <span class="w-1 h-1 rounded-full bg-gray-300 dark:bg-gray-600"></span>
            <span>{{ \Carbon\Carbon::parse($post->published_at)->format('M j, Y') }}</span>
            <span class="w-1 h-1 rounded-full bg-gray-300 dark:bg-gray-600"></span>
            <span>{{ $post->read_time }} min read</span>
        </div>
    </div>
</a>
