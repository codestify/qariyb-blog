<div>
    {{-- Article Hero --}}
    <header class="pt-24 pb-12 md:pt-28 md:pb-16">
        <div class="max-w-content mx-auto px-6">
            {{-- Breadcrumb --}}
            <nav class="flex items-center gap-2 text-sm text-gray-400 dark:text-gray-500 mb-8" aria-label="Breadcrumb">
                <a href="{{ route('home') }}" class="hover:text-gray-900 dark:hover:text-gray-100 transition-colors">Blog</a>
                <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M9 18l6-6-6-6"/></svg>
                <span>{{ ucfirst($article->category) }}</span>
            </nav>

            {{-- Category pill --}}
            <x-category-badge :category="$article->category" class="mb-6 inline-block" />

            {{-- Title --}}
            <h1 class="text-3xl md:text-4xl font-bold tracking-tight leading-tight text-gray-900 dark:text-gray-100 mb-4">{{ $article->title }}</h1>

            {{-- Subtitle --}}
            <p class="text-lg text-gray-500 dark:text-gray-400 leading-relaxed mb-8 max-w-2xl">{{ $article->excerpt }}</p>

            {{-- Author + Meta --}}
            <div class="flex flex-col sm:flex-row sm:items-center gap-4 sm:gap-6">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-primary-100 dark:bg-primary-400/10 border border-gray-200 dark:border-white/[0.06] flex items-center justify-center text-sm font-semibold text-primary-400 dark:text-primary-400">{{ $article->author_initials }}</div>
                    <div class="flex flex-col">
                        <span class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $article->author_name }}</span>
                        <span class="text-xs text-gray-400 dark:text-gray-500">{{ $article->author_role }}</span>
                    </div>
                </div>
                <div class="flex items-center gap-3 text-[13px] text-gray-400 dark:text-gray-500">
                    <span>{{ \Carbon\Carbon::parse($article->published_at)->format('F j, Y') }}</span>
                    <span class="w-1 h-1 rounded-full bg-gray-300 dark:bg-gray-600"></span>
                    <span>{{ $article->read_time }} min read</span>
                </div>
            </div>
        </div>
    </header>

    {{-- Cover Image --}}
    <div class="max-w-site mx-auto px-6 mb-12">
        <div class="aspect-[21/9] bg-gradient-to-br from-gray-100 dark:from-zinc-800 to-primary-100 dark:to-primary-400/[0.06] rounded-2xl flex items-center justify-center border border-gray-200 dark:border-white/[0.06]">
            <svg width="180" height="180" viewBox="0 0 180 180" fill="none">
                <rect width="180" height="180" rx="20" fill="rgba(139,79,192,0.05)"/>
                <rect x="50" y="50" width="16" height="16" rx="2" fill="rgba(139,79,192,0.25)"/>
                <rect x="70" y="50" width="16" height="16" rx="2" fill="rgba(139,79,192,0.15)"/>
                <rect x="90" y="50" width="16" height="16" rx="2" fill="rgba(139,79,192,0.25)"/>
                <rect x="110" y="50" width="16" height="16" rx="2" fill="rgba(139,79,192,0.1)"/>
                <rect x="50" y="70" width="16" height="16" rx="2" fill="rgba(139,79,192,0.1)"/>
                <rect x="70" y="70" width="16" height="16" rx="2" fill="rgba(139,79,192,0.3)"/>
                <rect x="90" y="70" width="16" height="16" rx="2" fill="rgba(139,79,192,0.1)"/>
                <rect x="110" y="70" width="16" height="16" rx="2" fill="rgba(139,79,192,0.25)"/>
                <rect x="50" y="90" width="16" height="16" rx="2" fill="rgba(139,79,192,0.25)"/>
                <rect x="70" y="90" width="16" height="16" rx="2" fill="rgba(139,79,192,0.1)"/>
                <rect x="90" y="90" width="16" height="16" rx="2" fill="rgba(139,79,192,0.2)"/>
                <rect x="110" y="90" width="16" height="16" rx="2" fill="rgba(139,79,192,0.3)"/>
                <rect x="50" y="110" width="16" height="16" rx="2" fill="rgba(139,79,192,0.15)"/>
                <rect x="70" y="110" width="16" height="16" rx="2" fill="rgba(139,79,192,0.25)"/>
                <rect x="90" y="110" width="16" height="16" rx="2" fill="rgba(139,79,192,0.1)"/>
                <rect x="110" y="110" width="16" height="16" rx="2" fill="rgba(139,79,192,0.2)"/>
                <rect x="40" y="86" width="100" height="2" rx="1" fill="rgba(139,79,192,0.5)"/>
            </svg>
        </div>
    </div>

    {{-- Article Layout: 3-column --}}
    <div class="max-w-site mx-auto px-6 grid grid-cols-1 lg:grid-cols-[220px_1fr_220px] gap-8 lg:gap-12">

        {{-- ToC Sidebar (sticky on desktop) --}}
        <aside class="hidden lg:block" x-data="tableOfContents">
            <div class="sticky top-24">
                <p class="text-xs font-semibold uppercase tracking-widest text-gray-400 dark:text-gray-500 mb-4">On this page</p>
                <ul class="space-y-1">
                    @foreach ($toc as $item)
                        <li>
                            <a
                                href="#{{ $item['id'] }}"
                                class="toc-link block pl-3 py-1 text-[13px] transition-colors"
                                :class="activeId === '{{ $item['id'] }}'
                                    ? 'text-gray-900 dark:text-gray-100 font-medium active'
                                    : 'text-gray-400 dark:text-gray-500 hover:text-gray-900 dark:hover:text-gray-100'"
                            >{{ $item['text'] }}</a>
                        </li>
                    @endforeach
                </ul>
            </div>
        </aside>

        {{-- Article Body --}}
        <article class="prose-content max-w-content mx-auto w-full text-[15px] leading-relaxed text-gray-700 dark:text-gray-300 [&>h2]:text-xl [&>h2]:font-bold [&>h2]:tracking-tight [&>h2]:text-gray-900 [&>h2]:dark:text-gray-100 [&>h2]:mt-12 [&>h2]:mb-4 [&>p]:mb-5 [&>ul]:mb-5 [&>ul]:list-disc [&>ul]:pl-6 [&>ul]:space-y-2 [&>pre]:my-6 [&>pre]:rounded-lg [&>pre]:bg-gray-50 [&>pre]:dark:bg-zinc-900 [&>pre]:border [&>pre]:border-gray-200 [&>pre]:dark:border-white/[0.06] [&>pre]:p-5 [&>pre]:overflow-x-auto [&>pre]:text-sm [&>pre]:font-mono [&>hr]:my-10 [&>hr]:border-gray-200 [&>hr]:dark:border-white/[0.06] [&_strong]:text-gray-900 [&_strong]:dark:text-gray-100">
            {!! $article->body !!}
        </article>

        {{-- Right column (empty spacer for symmetry) --}}
        <div class="hidden lg:block"></div>
    </div>

    {{-- Share buttons --}}
    <div class="max-w-content mx-auto px-6 mt-12 mb-16">
        <div class="flex items-center gap-4 pt-8 border-t border-gray-200 dark:border-white/[0.06]">
            <span class="text-sm text-gray-400 dark:text-gray-500">Share this article</span>
            <div class="flex gap-2" x-data="{ copied: false }">
                <a href="https://x.com/intent/tweet?text={{ urlencode($article->title) }}&url={{ urlencode(route('article.show', $article->slug)) }}" target="_blank" class="w-8 h-8 flex items-center justify-center rounded-md border border-gray-200 dark:border-white/[0.06] text-gray-400 dark:text-gray-500 hover:text-gray-900 dark:hover:text-gray-100 hover:border-gray-300 dark:hover:border-white/[0.16] transition-colors" aria-label="Share on X/Twitter">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                </a>
                <a href="https://www.linkedin.com/sharing/share-offsite/?url={{ urlencode(route('article.show', $article->slug)) }}" target="_blank" class="w-8 h-8 flex items-center justify-center rounded-md border border-gray-200 dark:border-white/[0.06] text-gray-400 dark:text-gray-500 hover:text-gray-900 dark:hover:text-gray-100 hover:border-gray-300 dark:hover:border-white/[0.16] transition-colors" aria-label="Share on LinkedIn">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433a2.062 2.062 0 01-2.063-2.065 2.064 2.064 0 112.063 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>
                </a>
                <button
                    @click="navigator.clipboard.writeText(window.location.href); copied = true; setTimeout(() => copied = false, 2000)"
                    class="w-8 h-8 flex items-center justify-center rounded-md border border-gray-200 dark:border-white/[0.06] text-gray-400 dark:text-gray-500 hover:text-gray-900 dark:hover:text-gray-100 hover:border-gray-300 dark:hover:border-white/[0.16] transition-colors"
                    aria-label="Copy link"
                >
                    <svg x-show="!copied" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M10 13a5 5 0 007.54.54l3-3a5 5 0 00-7.07-7.07l-1.72 1.71"/><path d="M14 11a5 5 0 00-7.54-.54l-3 3a5 5 0 007.07 7.07l1.71-1.71"/></svg>
                    <svg x-show="copied" x-cloak width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M20 6L9 17l-5-5"/></svg>
                </button>
            </div>
        </div>
    </div>

    {{-- Related Posts --}}
    @if ($relatedPosts->isNotEmpty())
        <section class="max-w-site mx-auto px-6 mb-16">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-6">Related articles</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @foreach ($relatedPosts as $post)
                    <x-post-card :post="$post" wire:key="related-{{ $post->slug }}" />
                @endforeach
            </div>
        </section>
    @endif

    <livewire:newsletter-form />
</div>
