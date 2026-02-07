// Dark mode toggle — Alpine.js data component
// Registered via alpine:init event dispatched by Livewire before Alpine boots
document.addEventListener('alpine:init', () => {
    Alpine.data('darkMode', () => ({
        isDark: localStorage.getItem('theme') === 'dark' ||
            (!localStorage.getItem('theme') && window.matchMedia('(prefers-color-scheme: dark)').matches),

        toggle() {
            document.documentElement.classList.add('transitioning');
            this.isDark = !this.isDark;
            localStorage.setItem('theme', this.isDark ? 'dark' : 'light');
            document.documentElement.classList.toggle('dark', this.isDark);
            setTimeout(() => document.documentElement.classList.remove('transitioning'), 300);
        },
    }));

    Alpine.data('tableOfContents', () => ({
        activeId: '',
        headingIds: [],

        init() {
            this.$nextTick(() => {
                const headings = document.querySelectorAll('.prose-content h2[id]');
                this.headingIds = Array.from(headings).map(h => h.id);

                if (this.headingIds.length === 0) return;

                this.activeId = this.headingIds[0];

                const observer = new IntersectionObserver(entries => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            this.activeId = entry.target.id;
                        }
                    });
                }, { rootMargin: '-80px 0px -70% 0px', threshold: 0 });

                headings.forEach(heading => observer.observe(heading));
            });
        },
    }));
});
