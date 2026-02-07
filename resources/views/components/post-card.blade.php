@props(['post'])

<a href="{{ route('article.show', $post->slug) }}" class="post-card group flex flex-col bg-surface-alt border border-gray-200 dark:border-white/[0.06] rounded-xl overflow-hidden hover:border-gray-300 dark:hover:border-white/[0.16] hover:-translate-y-0.5 transition-all">
    <div class="aspect-[16/9] bg-gradient-to-br from-gray-50 dark:from-zinc-800 to-gray-100 dark:to-zinc-900 relative flex items-center justify-center">
        <svg class="w-12 h-12 opacity-10" viewBox="0 0 48 48" fill="none"><rect x="8" y="8" width="32" height="32" rx="4" stroke="currentColor" stroke-width="1.5"/><path d="M16 24L22 30L32 18" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
        <x-category-badge :category="$post->category" class="absolute top-4 left-4" />
    </div>
    <div class="p-5 flex flex-col flex-1">
        <h3 class="text-base font-semibold tracking-tight leading-snug mb-2 text-gray-900 dark:text-gray-100 group-hover:text-primary-500 dark:group-hover:text-primary-400 transition-colors">{{ $post->title }}</h3>
        <p class="text-sm text-gray-500 dark:text-gray-400 leading-relaxed mb-5 flex-1 line-clamp-3">{{ $post->excerpt }}</p>
        <div class="flex items-center justify-between text-[13px] text-gray-400 dark:text-gray-500 pt-4 border-t border-gray-100 dark:border-white/[0.06]">
            <div class="flex items-center gap-2">
                <div class="w-5 h-5 rounded-full bg-primary-100 dark:bg-primary-400/10 flex items-center justify-center text-[10px] font-semibold text-primary-400 dark:text-primary-400">{{ $post->author_initials }}</div>
                <span>{{ $post->author_name }}</span>
            </div>
            <span class="flex items-center gap-1">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>
                {{ $post->read_time }} min
            </span>
        </div>
    </div>
</a>
