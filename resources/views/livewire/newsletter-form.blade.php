<div>
    <section @class([
        'py-16 border-t border-gray-200 dark:border-white/[0.06]' => ! $compact,
    ])>
        <div @class([
            'mx-auto text-center px-6' => true,
            'max-w-lg' => ! $compact,
        ])>
            @unless ($compact)
                <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100 mb-2">Stay in the loop</h2>
                <p class="text-[15px] text-gray-500 dark:text-gray-400 mb-6">Get engineering insights and product updates delivered to your inbox. No spam, unsubscribe anytime.</p>
            @endunless

            @if ($isSubscribed)
                <div class="flex items-center justify-center gap-2 text-emerald-500 dark:text-emerald-400">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M22 11.08V12a10 10 0 11-5.93-9.14"/><path d="M22 4L12 14.01l-3-3"/></svg>
                    <span class="text-sm font-medium">You're subscribed! Check your inbox.</span>
                </div>
            @else
                <form wire:submit="subscribe" class="flex flex-col sm:flex-row gap-2">
                    <input
                        wire:model="email"
                        type="email"
                        required
                        placeholder="you@example.com"
                        aria-label="Email address"
                        class="flex-1 px-4 py-2.5 text-sm font-sans text-gray-900 dark:text-gray-100 bg-surface-alt border border-gray-200 dark:border-white/10 rounded-lg outline-none focus:border-primary-400 focus:ring-2 focus:ring-primary-400/20 transition placeholder:text-gray-400 dark:placeholder:text-gray-600"
                    >
                    <button
                        type="submit"
                        wire:loading.attr="disabled"
                        class="px-5 py-2.5 text-sm font-medium bg-primary-500 dark:bg-gray-100 text-white dark:text-gray-900 rounded-lg hover:bg-primary-600 dark:hover:bg-white transition-all hover:-translate-y-px whitespace-nowrap disabled:opacity-50"
                    >
                        <span wire:loading.remove>{{ $compact ? 'Notify Me' : 'Subscribe' }}</span>
                        <span wire:loading>{{ $compact ? 'Subscribing...' : 'Subscribing...' }}</span>
                    </button>
                </form>

                @error('email')
                    <p class="mt-2 text-sm text-red-500 dark:text-red-400">{{ $message }}</p>
                @enderror
            @endif
        </div>
    </section>
</div>
