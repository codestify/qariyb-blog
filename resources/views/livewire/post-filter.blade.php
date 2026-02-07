<div>
    {{-- Search Input --}}
    <section class="pb-6 md:pb-8">
        <div class="max-w-site mx-auto px-6">
            <div class="max-w-md mx-auto relative">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 dark:text-gray-500 pointer-events-none" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="11" cy="11" r="8" />
                    <path d="M21 21l-4.35-4.35" />
                </svg>
                <input
                    wire:model.live.debounce.250ms="search"
                    type="text"
                    placeholder="Search articles..."
                    aria-label="Search articles"
                    class="w-full pl-10 pr-4 py-2.5 text-sm font-sans text-gray-900 dark:text-gray-100 bg-surface-alt border border-gray-200 dark:border-white/10 rounded-lg outline-none focus:border-primary-400 focus:ring-2 focus:ring-primary-400/20 transition placeholder:text-gray-400 dark:placeholder:text-gray-600"
                >
            </div>
        </div>
    </section>

    {{-- Category Filter --}}
    <section class="pb-10 md:pb-12">
        <div class="max-w-site mx-auto px-6">
            <div class="flex items-center gap-2 flex-wrap justify-center">
                <button
                    wire:click="filterByCategory('all')"
                    class="px-4 py-1.5 text-[13px] font-medium rounded-full border transition-all whitespace-nowrap cursor-pointer {{ $activeCategory === 'all' ? 'bg-gray-100 dark:bg-white/[0.08] text-gray-900 dark:text-gray-100 border-gray-300 dark:border-white/[0.16]' : 'bg-transparent text-gray-500 dark:text-gray-400 border-gray-200 dark:border-white/[0.06] hover:text-gray-900 dark:hover:text-gray-100 hover:border-gray-300 dark:hover:border-white/[0.16]' }}"
                >All Posts</button>

                @foreach ($categories as $category)
                    <button
                        wire:click="filterByCategory('{{ $category }}')"
                        wire:key="cat-{{ $category }}"
                        class="px-4 py-1.5 text-[13px] font-medium rounded-full border transition-all whitespace-nowrap cursor-pointer {{ $activeCategory === $category ? 'bg-gray-100 dark:bg-white/[0.08] text-gray-900 dark:text-gray-100 border-gray-300 dark:border-white/[0.16]' : 'bg-transparent text-gray-500 dark:text-gray-400 border-gray-200 dark:border-white/[0.06] hover:text-gray-900 dark:hover:text-gray-100 hover:border-gray-300 dark:hover:border-white/[0.16]' }}"
                    >{{ ucfirst($category) }}</button>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Loading Indicator --}}
    <div wire:loading class="flex justify-center pb-6">
        <svg class="animate-spin h-5 w-5 text-primary-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
    </div>

    {{-- Featured Post (only on page 1, all categories, no search) --}}
    @if ($featured)
        <section class="mb-16">
            <div class="max-w-site mx-auto px-6">
                <x-featured-post :post="$featured" />
            </div>
        </section>
    @endif

    {{-- Posts Grid --}}
    <section wire:loading.class="opacity-50">
        <div class="max-w-site mx-auto px-6">
            <div class="flex items-center justify-between mb-8">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                    @if ($search !== '')
                        Search Results
                    @elseif ($activeCategory === 'all')
                        All Posts
                    @else
                        {{ ucfirst($activeCategory) }}
                    @endif
                </h2>
                <span class="text-[13px] text-gray-400 dark:text-gray-500">{{ $totalCount }} {{ Str::plural('article', $totalCount) }}</span>
            </div>

            @if ($posts->isEmpty())
                <div class="text-center py-16">
                    <p class="text-gray-500 dark:text-gray-400 mb-4">
                        No articles found{{ $search !== '' ? " for '{$search}'" : '' }}.
                    </p>
                    <button
                        wire:click="clearFilters"
                        class="text-sm font-medium text-primary-400 dark:text-primary-400 hover:text-primary-500 dark:hover:text-primary-300 transition-colors"
                    >Clear filters</button>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-12">
                    @foreach ($posts as $post)
                        <x-post-card :post="$post" wire:key="post-{{ $post->slug }}" />
                    @endforeach
                </div>
            @endif

            {{-- Pagination --}}
            @if ($posts->hasPages())
                <div class="flex items-center justify-center gap-4 pb-24">
                    <button
                        wire:click="previousPage"
                        @if ($posts->onFirstPage()) disabled @endif
                        class="text-sm font-medium transition-colors {{ $posts->onFirstPage() ? 'text-gray-300 dark:text-gray-600 cursor-not-allowed' : 'text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100' }}"
                    >&larr; Previous</button>

                    <span class="text-[13px] text-gray-400 dark:text-gray-500">
                        Page {{ $posts->currentPage() }} of {{ $posts->lastPage() }}
                    </span>

                    <button
                        wire:click="nextPage"
                        @if ($posts->onLastPage()) disabled @endif
                        class="text-sm font-medium transition-colors {{ $posts->onLastPage() ? 'text-gray-300 dark:text-gray-600 cursor-not-allowed' : 'text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100' }}"
                    >Next &rarr;</button>
                </div>
            @else
                <div class="pb-24"></div>
            @endif
        </div>
    </section>
</div>
