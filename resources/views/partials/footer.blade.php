<footer class="py-10 border-t border-gray-200 dark:border-white/[0.06]">
    <div class="max-w-site mx-auto px-6 flex flex-col md:flex-row items-center justify-between gap-4">
        <div class="flex flex-col md:flex-row items-center gap-2 md:gap-6">
            <a href="{{ route('home') }}" class="flex items-center">
                <img src="{{ asset('images/qariyb-logo.svg') }}" alt="Qariyb" class="h-5 dark:hidden">
                <img src="{{ asset('images/qariyb-logo-light.svg') }}" alt="Qariyb" class="h-5 hidden dark:block">
            </a>
            <span class="text-[13px] text-gray-400 dark:text-gray-500">&copy; {{ date('Y') }} Qariyb. All rights reserved.</span>
        </div>
        <div class="flex gap-6">
            <a href="https://qariyb.com" target="_blank" class="text-[13px] text-gray-400 dark:text-gray-500 hover:text-gray-900 dark:hover:text-gray-100 transition-colors">Platform</a>
            <a href="https://qariyb.com/pricing" target="_blank" class="text-[13px] text-gray-400 dark:text-gray-500 hover:text-gray-900 dark:hover:text-gray-100 transition-colors">Pricing</a>
            <a href="https://qariyb.com/faqs" target="_blank" class="text-[13px] text-gray-400 dark:text-gray-500 hover:text-gray-900 dark:hover:text-gray-100 transition-colors">FAQs</a>
            <a href="https://x.com/qariyb" target="_blank" class="text-[13px] text-gray-400 dark:text-gray-500 hover:text-gray-900 dark:hover:text-gray-100 transition-colors">X / Twitter</a>
        </div>
    </div>
</footer>
