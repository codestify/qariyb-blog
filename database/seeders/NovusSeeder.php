<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Shah\Novus\Enums\PostStatus;
use Shah\Novus\Models\Author;
use Shah\Novus\Models\Category;
use Shah\Novus\Models\Post;
use Shah\Novus\Models\SeoMeta;
use Shah\Novus\Models\Subscriber;
use Shah\Novus\Models\Tag;

class NovusSeeder extends Seeder
{
    public function run(): void
    {
        $authors = $this->seedAuthors();
        $categories = $this->seedCategories();
        $tags = $this->seedTags();
        $posts = $this->seedPosts($authors, $categories, $tags);
        $this->seedSubscribers();
    }

    /**
     * @return array<string, Author>
     */
    private function seedAuthors(): array
    {
        $authorsData = [
            'abubakar' => [
                'name' => 'Abubakar Sheriff',
                'slug' => 'abubakar-sheriff',
                'bio' => 'Founder & CEO of Qariyb. Passionate about building technology that brings Muslim communities closer together. Previously led engineering teams at scale-ups in London and Dubai.',
                'email' => 'abubakar@qariyb.com',
                'password' => bcrypt('password'),
                'website' => 'https://qariyb.com',
                'social_links' => ['twitter' => 'https://x.com/qariyb', 'linkedin' => 'https://linkedin.com/in/abubakar-sheriff'],
            ],
            'zainab' => [
                'name' => 'Zainab Rahman',
                'slug' => 'zainab-rahman',
                'bio' => 'Community Lead at Qariyb. Connecting Muslim event organizers worldwide and amplifying the stories that matter.',
                'email' => 'zainab@qariyb.com',
                'password' => bcrypt('password'),
                'website' => null,
                'social_links' => ['twitter' => 'https://x.com/zainabr'],
            ],
            'fatima' => [
                'name' => 'Fatima Khan',
                'slug' => 'fatima-khan',
                'bio' => 'Frontend Engineer at Qariyb. Advocate for accessible, performant web experiences. WCAG enthusiast and CSS architecture nerd.',
                'email' => 'fatima@qariyb.com',
                'password' => bcrypt('password'),
                'website' => null,
                'social_links' => ['github' => 'https://github.com/fatimakhan'],
            ],
            'omar' => [
                'name' => 'Omar Ahmed',
                'slug' => 'omar-ahmed',
                'bio' => 'Backend Engineer at Qariyb. Focused on distributed systems, search infrastructure, and making APIs that developers love.',
                'email' => 'omar@qariyb.com',
                'password' => bcrypt('password'),
                'website' => null,
                'social_links' => ['github' => 'https://github.com/omarahmed'],
            ],
            'nadia' => [
                'name' => 'Nadia Yusuf',
                'slug' => 'nadia-yusuf',
                'bio' => 'Product Designer at Qariyb. Turning complex workflows into intuitive interfaces. Believes good design is invisible.',
                'email' => 'nadia@qariyb.com',
                'password' => bcrypt('password'),
                'website' => null,
                'social_links' => ['dribbble' => 'https://dribbble.com/nadiayusuf'],
            ],
        ];

        $authors = [];
        foreach ($authorsData as $key => $data) {
            $authors[$key] = Author::create($data);
        }

        return $authors;
    }

    /**
     * @return array<string, Category>
     */
    private function seedCategories(): array
    {
        $categoriesData = [
            'engineering' => [
                'name' => 'Engineering',
                'slug' => 'engineering',
                'description' => 'Deep dives into architecture, performance, and the technical decisions behind Qariyb.',
            ],
            'product' => [
                'name' => 'Product',
                'slug' => 'product',
                'description' => 'New features, design decisions, and product thinking at Qariyb.',
            ],
            'community' => [
                'name' => 'Community',
                'slug' => 'community',
                'description' => 'Stories, case studies, and insights from Muslim event organizers and communities worldwide.',
            ],
            'culture' => [
                'name' => 'Culture',
                'slug' => 'culture',
                'description' => 'How we work, what we believe, and the values that guide our team.',
            ],
            'announcements' => [
                'name' => 'Announcements',
                'slug' => 'announcements',
                'description' => 'Major updates, launches, and milestones from Qariyb.',
            ],
        ];

        $categories = [];
        foreach ($categoriesData as $key => $data) {
            $categories[$key] = Category::create($data);
        }

        return $categories;
    }

    /**
     * @return array<string, Tag>
     */
    private function seedTags(): array
    {
        $tagNames = [
            'laravel', 'performance', 'scaling', 'qr-codes', 'search',
            'elasticsearch', 'accessibility', 'wcag', 'design', 'ux',
            'open-source', 'events', 'case-study', 'analytics', 'mobile',
            'api', 'security', 'infrastructure', 'devops', 'frontend',
            'backend', 'database', 'testing', 'ci-cd', 'kubernetes',
            'real-time', 'websockets', 'edge-computing', 'ai', 'payments',
        ];

        $tags = [];
        foreach ($tagNames as $name) {
            $tags[$name] = Tag::create([
                'name' => Str::title(str_replace('-', ' ', $name)),
                'slug' => $name,
            ]);
        }

        return $tags;
    }

    /**
     * @param  array<string, Author>  $authors
     * @param  array<string, Category>  $categories
     * @param  array<string, Tag>  $tags
     * @return array<int, Post>
     */
    private function seedPosts(array $authors, array $categories, array $tags): array
    {
        $postsData = $this->getPostsData();
        $posts = [];

        foreach ($postsData as $data) {
            $author = $authors[$data['author_key']];
            $categoryKey = $data['category_key'];
            $tagKeys = $data['tag_keys'];

            $html = $data['content_html'];

            $post = Post::create([
                'title' => $data['title'],
                'slug' => $data['slug'],
                'excerpt' => $data['excerpt'],
                'content' => $html,
                'content_html' => $html,
                'published_at' => $data['published_at'],
                'is_featured' => $data['is_featured'] ?? false,
                'status' => $data['status'] ?? PostStatus::Published,
                'author_id' => $author->id,
                'post_type' => 'post',
            ]);

            $post->categories()->attach($categories[$categoryKey]->id);

            $tagIds = collect($tagKeys)->map(fn (string $key) => $tags[$key]->id)->all();
            $post->tags()->attach($tagIds);

            $this->createSeoMeta($post);

            $posts[] = $post;
        }

        return $posts;
    }

    private function createSeoMeta(Post $post): void
    {
        SeoMeta::create([
            'metable_type' => Post::class,
            'metable_id' => $post->id,
            'meta_title' => Str::limit($post->title, 60),
            'meta_description' => $post->excerpt,
        ]);
    }

    private function seedSubscribers(): void
    {
        $subscribers = [
            ['email' => 'ahmad.hassan@example.com', 'name' => 'Ahmad Hassan'],
            ['email' => 'sara.malik@example.com', 'name' => 'Sara Malik'],
            ['email' => 'yusuf.ali@example.com', 'name' => 'Yusuf Ali'],
            ['email' => 'mariam.khan@example.com', 'name' => 'Mariam Khan'],
            ['email' => 'ibrahim.sheikh@example.com', 'name' => 'Ibrahim Sheikh'],
        ];

        foreach ($subscribers as $data) {
            Subscriber::create([
                'email' => $data['email'],
                'name' => $data['name'],
                'status' => 'active',
                'subscribed_at' => now()->subDays(rand(1, 90)),
                'email_verified_at' => now()->subDays(rand(1, 90)),
            ]);
        }
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function getPostsData(): array
    {
        return [
            // ── Post 1: Featured ──
            [
                'title' => 'How We Built Real-Time Ticket Scanning for 10,000+ Attendee Events',
                'slug' => 'real-time-ticket-scanning',
                'excerpt' => 'A deep dive into the architecture behind Qariyb\'s contactless check-in system — from QR generation to sub-second validation at scale.',
                'content_html' => $this->post01ContentHtml(),
                'category_key' => 'engineering',
                'author_key' => 'abubakar',
                'tag_keys' => ['qr-codes', 'scaling', 'real-time', 'infrastructure'],
                'is_featured' => true,
                'status' => PostStatus::Published,
                'published_at' => '2026-01-28 09:00:00',
            ],
            // ── Post 2 ──
            [
                'title' => 'Migrating Our Event Search to Elasticsearch: Lessons Learned',
                'slug' => 'migrating-to-elasticsearch',
                'excerpt' => 'How we moved from basic database queries to a full-text search engine, improving event discovery speed by 40x while keeping infrastructure costs manageable.',
                'content_html' => $this->post02ContentHtml(),
                'category_key' => 'engineering',
                'author_key' => 'abubakar',
                'tag_keys' => ['elasticsearch', 'search', 'performance', 'database'],
                'published_at' => '2026-01-22 10:00:00',
            ],
            // ── Post 3 ──
            [
                'title' => 'Introducing Multi-Day Event Scheduling',
                'slug' => 'multi-day-event-scheduling',
                'excerpt' => 'Conferences and retreats don\'t happen in a single day. Our new multi-day scheduling feature lets organizers create complex agendas with tracks, sessions, and breaks.',
                'content_html' => $this->post03ContentHtml(),
                'category_key' => 'product',
                'author_key' => 'abubakar',
                'tag_keys' => ['events', 'ux', 'design'],
                'published_at' => '2026-01-18 08:30:00',
            ],
            // ── Post 4 ──
            [
                'title' => 'How Deen Developers Built a 2,000-Person Meetup Using Qariyb',
                'slug' => 'deen-developers-case-study',
                'excerpt' => 'A behind-the-scenes look at how one of the largest Muslim tech communities scaled their events from living room gatherings to conference-hall meetups.',
                'content_html' => $this->post04ContentHtml(),
                'category_key' => 'community',
                'author_key' => 'zainab',
                'tag_keys' => ['case-study', 'events', 'scaling'],
                'published_at' => '2026-01-14 11:00:00',
            ],
            // ── Post 5 ──
            [
                'title' => 'Building Accessible Forms: Our Approach to WCAG 2.2 Compliance',
                'slug' => 'accessible-forms-wcag',
                'excerpt' => 'Accessibility isn\'t optional. Here\'s how we rebuilt our event registration forms to be fully accessible, and what we learned about screen readers.',
                'content_html' => $this->post05ContentHtml(),
                'category_key' => 'engineering',
                'author_key' => 'fatima',
                'tag_keys' => ['accessibility', 'wcag', 'frontend', 'design'],
                'published_at' => '2026-01-10 09:00:00',
            ],
            // ── Post 6 ──
            [
                'title' => 'Why We Work in Public: Our Open Culture Philosophy',
                'slug' => 'open-culture-philosophy',
                'excerpt' => 'Transparency shapes better products. We share how working in public — from roadmaps to retros — has built trust with our organizers and community.',
                'content_html' => $this->post06ContentHtml(),
                'category_key' => 'culture',
                'author_key' => 'abubakar',
                'tag_keys' => ['open-source', 'events'],
                'published_at' => '2026-01-06 10:00:00',
            ],
            // ── Post 7 ──
            [
                'title' => 'Qariyb Expands to London, Toronto, and Kuala Lumpur',
                'slug' => 'qariyb-expands-globally',
                'excerpt' => 'We\'re taking Qariyb global. Starting this month, event organizers in three new cities can list, promote, and manage their events on our platform.',
                'content_html' => $this->post07ContentHtml(),
                'category_key' => 'announcements',
                'author_key' => 'abubakar',
                'tag_keys' => ['events'],
                'published_at' => '2026-01-02 08:00:00',
            ],
            // ── Post 8 ──
            [
                'title' => 'Edge Functions for Event Geolocation: A Performance Story',
                'slug' => 'edge-functions-geolocation',
                'excerpt' => 'We moved our location-based event discovery to edge functions and reduced latency from 800ms to 45ms. Here\'s the full architecture and tradeoffs.',
                'content_html' => $this->post08ContentHtml(),
                'category_key' => 'engineering',
                'author_key' => 'omar',
                'tag_keys' => ['edge-computing', 'performance', 'infrastructure', 'backend'],
                'published_at' => '2025-12-28 09:30:00',
            ],
            // ── Post 9 ──
            [
                'title' => 'Designing the Organizer Dashboard: From Chaos to Clarity',
                'slug' => 'organizer-dashboard-redesign',
                'excerpt' => 'Event organizers juggle a lot. We redesigned the dashboard around three principles: at-a-glance insights, fast actions, and zero configuration overhead.',
                'content_html' => $this->post09ContentHtml(),
                'category_key' => 'product',
                'author_key' => 'nadia',
                'tag_keys' => ['design', 'ux', 'frontend', 'analytics'],
                'published_at' => '2025-12-22 10:00:00',
            ],
            // ── Post 10 ──
            [
                'title' => 'The State of Muslim Tech Events in 2026',
                'slug' => 'state-of-muslim-tech-events-2026',
                'excerpt' => 'We analysed 5,000+ events hosted on Qariyb over the past year. Here\'s what we found about attendance trends, popular formats, and what\'s coming next.',
                'content_html' => $this->post10ContentHtml(),
                'category_key' => 'community',
                'author_key' => 'abubakar',
                'tag_keys' => ['analytics', 'events', 'case-study'],
                'published_at' => '2025-12-18 09:00:00',
            ],
            // ── Post 11 ──
            [
                'title' => 'Implementing Stripe Connect for Multi-Party Event Payments',
                'slug' => 'stripe-connect-event-payments',
                'excerpt' => 'When organizers sell tickets, money flows through multiple parties. Here\'s how we built a compliant payment pipeline with Stripe Connect that handles splits, refunds, and payouts across 12 countries.',
                'content_html' => $this->post11ContentHtml(),
                'category_key' => 'engineering',
                'author_key' => 'omar',
                'tag_keys' => ['payments', 'api', 'security', 'backend'],
                'published_at' => '2025-12-14 09:00:00',
            ],
            // ── Post 12 ──
            [
                'title' => 'How We Reduced Our CI Pipeline From 18 Minutes to 4',
                'slug' => 'faster-ci-pipeline',
                'excerpt' => 'Our test suite was growing and so were our build times. We parallelised, cached, and pruned our way to a 77% reduction in CI time without sacrificing coverage.',
                'content_html' => $this->post12ContentHtml(),
                'category_key' => 'engineering',
                'author_key' => 'fatima',
                'tag_keys' => ['ci-cd', 'testing', 'devops', 'performance'],
                'published_at' => '2025-12-10 10:30:00',
            ],
            // ── Post 13 ──
            [
                'title' => 'Launching the Qariyb Mobile App: React Native Lessons',
                'slug' => 'qariyb-mobile-app-launch',
                'excerpt' => 'After six months of development, the Qariyb mobile app is live on iOS and Android. We share the technical decisions, the trade-offs of React Native, and what surprised us most.',
                'content_html' => $this->post13ContentHtml(),
                'category_key' => 'product',
                'author_key' => 'fatima',
                'tag_keys' => ['mobile', 'frontend', 'design', 'ux'],
                'published_at' => '2025-12-06 08:00:00',
            ],
            // ── Post 14 ──
            [
                'title' => 'Our Ramadan Playbook: Scaling for Peak Season Traffic',
                'slug' => 'ramadan-scaling-playbook',
                'excerpt' => 'Ramadan is our busiest month — event creation spikes 340% and ticket sales surge overnight. Here\'s how we prepare infrastructure, support, and product for the annual rush.',
                'content_html' => $this->post14ContentHtml(),
                'category_key' => 'engineering',
                'author_key' => 'omar',
                'tag_keys' => ['scaling', 'infrastructure', 'kubernetes', 'devops'],
                'published_at' => '2025-12-02 09:00:00',
            ],
            // ── Post 15 ──
            [
                'title' => 'Building a Design System From Scratch: Noor UI',
                'slug' => 'noor-ui-design-system',
                'excerpt' => 'We built Noor UI, our internal design system, to ensure consistency across web, mobile, and the organizer dashboard. Here are the principles, components, and lessons from 18 months of iteration.',
                'content_html' => $this->post15ContentHtml(),
                'category_key' => 'product',
                'author_key' => 'nadia',
                'tag_keys' => ['design', 'frontend', 'ux', 'open-source'],
                'published_at' => '2025-11-28 10:00:00',
            ],
            // ── Post 16 ──
            [
                'title' => 'How ICNA Managed 15,000 Convention Attendees With Qariyb',
                'slug' => 'icna-convention-case-study',
                'excerpt' => 'ICNA\'s annual convention is one of the largest Islamic events in North America. We walk through how their team used Qariyb for registration, check-in, and real-time crowd management.',
                'content_html' => $this->post16ContentHtml(),
                'category_key' => 'community',
                'author_key' => 'zainab',
                'tag_keys' => ['case-study', 'events', 'scaling', 'qr-codes'],
                'published_at' => '2025-11-22 11:00:00',
            ],
            // ── Post 17 ──
            [
                'title' => 'WebSocket Architecture for Live Event Dashboards',
                'slug' => 'websocket-live-dashboards',
                'excerpt' => 'Organizers want to see attendee arrivals, ticket sales, and session occupancy in real time. We built a WebSocket layer with Laravel Reverb that pushes 50,000 events per minute.',
                'content_html' => $this->post17ContentHtml(),
                'category_key' => 'engineering',
                'author_key' => 'omar',
                'tag_keys' => ['websockets', 'real-time', 'laravel', 'backend'],
                'published_at' => '2025-11-18 09:00:00',
            ],
            // ── Post 18 ──
            [
                'title' => 'Growing Qariyb\'s Community Ambassador Programme',
                'slug' => 'community-ambassador-programme',
                'excerpt' => 'We launched our Ambassador Programme six months ago and it\'s already active in 14 cities. Here\'s how we built it, what worked, and what we\'d do differently.',
                'content_html' => $this->post18ContentHtml(),
                'category_key' => 'community',
                'author_key' => 'zainab',
                'tag_keys' => ['events', 'case-study'],
                'published_at' => '2025-11-14 10:30:00',
            ],
            // ── Post 19 ──
            [
                'title' => 'The Engineer\'s Guide to Running On-Call at a Small Startup',
                'slug' => 'on-call-small-startup',
                'excerpt' => 'With a team of five engineers, everyone carries a pager. We share our on-call rotation, escalation policies, and the runbooks that keep Qariyb running at 99.95% uptime.',
                'content_html' => $this->post19ContentHtml(),
                'category_key' => 'culture',
                'author_key' => 'omar',
                'tag_keys' => ['devops', 'infrastructure'],
                'published_at' => '2025-11-10 08:00:00',
            ],
            // ── Post 20 ──
            [
                'title' => 'Qariyb API v2: What\'s New for Developers',
                'slug' => 'qariyb-api-v2',
                'excerpt' => 'API v2 brings batch operations, webhook signatures, cursor-based pagination, and full OpenAPI 3.1 documentation. Here\'s what changed and how to migrate.',
                'content_html' => $this->post20ContentHtml(),
                'category_key' => 'announcements',
                'author_key' => 'abubakar',
                'tag_keys' => ['api', 'backend', 'open-source'],
                'published_at' => '2025-11-06 09:00:00',
            ],
            // ── Post 21 ──
            [
                'title' => 'AI-Powered Event Recommendations: Building Our Discovery Engine',
                'slug' => 'ai-event-recommendations',
                'excerpt' => 'We trained a recommendation model on anonymous attendance patterns to help users discover events they\'ll love. Here\'s the ML pipeline and the privacy-first approach we took.',
                'content_html' => $this->post21ContentHtml(),
                'category_key' => 'engineering',
                'author_key' => 'omar',
                'tag_keys' => ['ai', 'backend', 'performance', 'database'],
                'published_at' => '2025-11-02 10:00:00',
            ],
            // ── Post 22 (Draft) ──
            [
                'title' => 'Rethinking Event Ticketing: Dynamic Pricing Experiments',
                'slug' => 'dynamic-pricing-experiments',
                'excerpt' => 'Should community events use dynamic pricing? We ran experiments with 50 organizers to find out. The results challenged our assumptions.',
                'content_html' => $this->post22ContentHtml(),
                'category_key' => 'product',
                'author_key' => 'nadia',
                'tag_keys' => ['analytics', 'ux', 'payments'],
                'status' => PostStatus::Draft,
                'published_at' => null,
            ],
        ];
    }

    // ────────────────────────────────────────────────────────
    //  Post Content Methods (HTML for TipTap)
    // ────────────────────────────────────────────────────────

    private function post01ContentHtml(): string
    {
        return <<<'HTML'
<p>When we launched Qariyb, our check-in system was simple: show a QR code, scan it with a phone, mark the attendee as arrived. It worked great for 50-person halaqas and 200-person fundraisers. Then an organizer tried to check in 8,000 people at a three-day Islamic convention — and everything broke.</p>
<p>This is the story of how we rebuilt our entire ticketing and scanning infrastructure to handle events at scale, while keeping the experience fast enough that volunteers at the gate never notice there's an entire distributed system behind each beep.</p>
<h2 id="problem">The Problem</h2>
<p>At a large event, check-in isn't a steady trickle — it's a <strong>thundering herd</strong>. Friday prayers mean 2,000 people arriving in a 10-minute window. Convention doors open and 500 people surge through 6 gates simultaneously. Our original architecture made a round-trip API call for every single scan, which meant:</p>
<ul>
<li>Average scan-to-confirmation time of 2.3 seconds</li>
<li>Timeout failures when the API was under load</li>
<li>No offline capability — if WiFi dropped at the venue, gates froze</li>
<li>No duplicate-scan protection across multiple devices</li>
</ul>
<p>Organizers told us they were losing 15–20 minutes of event time just getting people through the door. That's unacceptable when people have taken time out of their day to attend.</p>
<h2 id="architecture">Architecture Overview</h2>
<p>We redesigned the system around three principles: <strong>scan locally, sync globally, resolve conflicts gracefully</strong>.</p>
<p>Each scanning device runs a lightweight local database that holds the full attendee manifest for that event. When a QR code is scanned, validation happens on-device in under 50 milliseconds. The result is then synced to our central API asynchronously.</p>
<h2 id="qr-generation">QR Code Generation</h2>
<p>Every ticket QR code is a signed JWT containing the attendee ID, event ID, ticket type, and a timestamp. We use Ed25519 signatures because they're fast to verify and the keys are small enough to bundle with the scanning app.</p>
<p>The QR payload is intentionally compact — under 200 bytes — so it scans reliably even on cracked phone screens or printed on low-quality paper.</p>
<h2 id="validation">Real-Time Validation</h2>
<p>Validation happens in three stages, each progressively more authoritative:</p>
<p><strong>Stage 1: On-device (50ms).</strong> The scanning device checks the ticket signature and looks up the attendee in its local manifest.</p>
<p><strong>Stage 2: Edge sync (200ms).</strong> The check-in event is pushed to our edge network via WebSocket. Other devices at the same event receive the update, preventing duplicate scans at different gates.</p>
<p><strong>Stage 3: Server reconciliation (async).</strong> The central API processes the check-in, updates the database of record, and triggers downstream actions.</p>
<h2 id="concurrency">Handling Concurrency</h2>
<p>The hardest problem wasn't speed — it was <strong>consistency across devices</strong>. What happens when Gate A and Gate B scan the same ticket within the same second?</p>
<p>We use a last-writer-wins strategy with vector clocks. Each device maintains a logical clock that increments with every scan. When the edge layer detects a conflict, it keeps the earliest timestamp and broadcasts a correction to all devices.</p>
<p>In practice, we see duplicate-scan conflicts on about 0.3% of total scans. The correction propagates in under 500ms.</p>
<h2 id="offline">Offline Resilience</h2>
<p>Venues are unpredictable. Our scanning app is designed to work fully offline — it downloads the complete attendee manifest during setup and can process every scan without any network connectivity.</p>
<p>When connectivity returns, the device replays its queue of check-in events to the server. We use idempotency keys to ensure no duplicate writes.</p>
<h2 id="results">Results &amp; Metrics</h2>
<p>After deploying the new system across 200+ events over three months:</p>
<ul>
<li><strong>Scan-to-confirmation:</strong> 2.3s → 47ms (98% improvement)</li>
<li><strong>Gate throughput:</strong> ~180 → ~900 scans/hour per device</li>
<li><strong>Offline resilience:</strong> 100% — zero scan failures during connectivity drops</li>
<li><strong>Duplicate detection accuracy:</strong> 99.97% across multi-gate setups</li>
<li><strong>Largest event tested:</strong> 12,400 attendees, 8 gates, 4-day convention</li>
</ul>
<h2 id="whats-next">What's Next</h2>
<p>We're working on predictive gate recommendations, NFC ticket support, and real-time arrival heatmaps for organizers.</p>
HTML;
    }

    private function post02ContentHtml(): string
    {
        return <<<'HTML'
<p>When Qariyb started, finding events was a simple <code>WHERE title LIKE '%keyword%'</code> query. It worked. Then we grew to 20,000 events and users started searching for "islamic finance workshop near me" — and the cracks showed.</p>
<h2 id="why-search">Why We Needed Search</h2>
<p>Database LIKE queries are brutal at scale. They can't rank relevance, they ignore synonyms, and they don't understand proximity. A search for "quran competition" wouldn't find "Qur'an Recitation Contest" even though they're the same thing.</p>
<h2 id="choosing-elasticsearch">Choosing Elasticsearch</h2>
<p>We evaluated Meilisearch, Typesense, and Elasticsearch. Meilisearch was tempting for its simplicity, but we needed custom analysers for Arabic transliteration and geo-distance scoring. Elasticsearch gave us the flexibility we needed.</p>
<h2 id="migration-strategy">The Migration Strategy</h2>
<p>We ran both systems in parallel for six weeks. Every search query hit both the old database and Elasticsearch, but only the database results were shown to users. We logged the Elasticsearch results separately and compared relevance.</p>
<h2 id="custom-analysers">Custom Analysers</h2>
<p>The hardest part was Arabic text handling. We built a custom analyser chain: ICU tokenizer → Arabic normalisation → transliteration mapping → edge n-gram filter. This means searching "jumma" finds events tagged "جمعة".</p>
<h2 id="results">Results</h2>
<ul>
<li>Search latency dropped from 1,200ms to 28ms (40x faster)</li>
<li>Click-through rate on search results improved by 62%</li>
<li>Zero-result searches dropped from 23% to 4%</li>
<li>Infrastructure cost: $180/month for a 3-node cluster</li>
</ul>
<h2 id="lessons">Lessons Learned</h2>
<p>Start with a good analyser chain — retrofitting is painful. Run systems in parallel before switching. And always have a fallback to database search if Elasticsearch goes down.</p>
HTML;
    }

    private function post03ContentHtml(): string
    {
        return <<<'HTML'
<p>Conferences and retreats don't happen in a single day. For months, organizers asked us for the ability to create events that span multiple days with separate tracks, session times, and speaker slots.</p>
<h2 id="challenge">The Challenge</h2>
<p>Multi-day events are fundamentally different from single events. You need nested schedules, per-day ticketing options, recurring sessions, break periods, and the ability for attendees to build personalised agendas.</p>
<h2 id="how-it-works">How It Works</h2>
<p>Organizers can now create a parent event with child "days", each containing tracks and sessions. Sessions have their own speakers, locations, and capacity limits. Attendees browse the full schedule and bookmark sessions they want to attend.</p>
<h2 id="technical-approach">Technical Approach</h2>
<p>We modelled this as a tree: Event → Days → Tracks → Sessions. Each level inherits settings from its parent but can override them. The trickiest part was timezone handling — a convention in Istanbul shouldn't show London times to the organizer in Dubai.</p>
<h2 id="feedback">What Organizers Are Saying</h2>
<p>Early feedback has been overwhelmingly positive. One organizer told us they cut their planning spreadsheets from 14 tabs to zero. The drag-and-drop schedule builder lets them rearrange sessions in seconds.</p>
HTML;
    }

    private function post04ContentHtml(): string
    {
        return <<<'HTML'
<p>Deen Developers started as a WhatsApp group of 15 Muslim software engineers in East London. Three years later, they're running monthly meetups with 400+ RSVPs and an annual conference that sold out at 2,000 attendees.</p>
<h2 id="early-days">The Early Days</h2>
<p>When founder Hamza Patel reached out to us, Deen Developers was managing registrations through Google Forms and check-in through printed spreadsheets. "We'd spend the first hour of every event just ticking names off paper," he told us.</p>
<h2 id="moving-to-qariyb">Moving to Qariyb</h2>
<p>The transition was immediate. Within their first event on Qariyb, they had QR check-in, automatic waitlists, and real-time attendance tracking. But the real game-changer was our community features.</p>
<h2 id="scaling">Scaling to 2,000</h2>
<p>Their annual conference required multi-track scheduling, speaker management, sponsor visibility, and tiered ticketing. We worked closely with their team to configure the platform for the complexity.</p>
<h2 id="results">Key Results</h2>
<ul>
<li>Registration time per attendee: 4 minutes → 45 seconds</li>
<li>Check-in queue wait: 12 minutes → 90 seconds</li>
<li>Post-event survey response rate: 8% → 34% (via in-app prompts)</li>
<li>Repeat attendee rate: 67% (tracked across events)</li>
</ul>
<h2 id="whats-next">What's Next for Deen Developers</h2>
<p>They're expanding to Manchester and Birmingham, and they're using Qariyb's multi-city tools to manage chapters from a single dashboard.</p>
HTML;
    }

    private function post05ContentHtml(): string
    {
        return '<p>Accessibility isn\'t optional — it\'s a responsibility. When we audited our event registration forms against WCAG 2.2, we found 47 issues ranging from missing ARIA labels to insufficient colour contrast. Here\'s how we fixed every one of them and what we learned about building truly inclusive forms.</p>'."\n".'<h2 id="audit">The Audit</h2>'."\n".'<p>We used axe-core, manual keyboard navigation, and real screen reader testing with NVDA and VoiceOver. The results were humbling — our forms looked great visually but were nearly unusable for screen reader users.</p>'."\n".'<h2 id="key-fixes">Key Fixes</h2>'."\n".'<p>We restructured form groups with proper fieldset/legend patterns, added live error announcements via aria-live regions, ensured all interactive elements had visible focus indicators, and rebuilt our date picker to be fully keyboard-navigable.</p>'."\n".'<h2 id="colour-contrast">Colour Contrast</h2>'."\n".'<p>Our brand green failed WCAG AA on white backgrounds. Rather than change the brand, we adjusted the specific shade used for text and interactive elements to achieve a 4.7:1 contrast ratio.</p>'."\n".'<h2 id="testing">Testing With Real Users</h2>'."\n".'<p>We invited three screen reader users to test the forms. Their feedback was invaluable — they caught issues that automated tools missed entirely, like confusing tab order and redundant announcements.</p>'."\n".'<h2 id="result">The Result</h2>'."\n".'<p>All 47 issues resolved. Zero axe-core violations. And most importantly, we heard from an organizer who is visually impaired that she could finally create events independently.</p>';
    }

    private function post06ContentHtml(): string
    {
        return '<p>Transparency shapes better products. From the beginning, we\'ve shared our roadmap publicly, run retrospectives in the open, and published our revenue numbers. Here\'s why we do it and what it\'s taught us.</p>'."\n".'<h2 id="why">Why Transparency Matters</h2>'."\n".'<p>Trust is the foundation of any platform that handles people\'s money and personal data. By being open about our decisions, our mistakes, and our direction, we give organizers a reason to invest their time in Qariyb.</p>'."\n".'<h2 id="what-we-share">What We Share</h2>'."\n".'<p>Our public roadmap shows what we\'re building, what\'s next, and what we\'ve decided not to build (and why). Our monthly retros are published as blog posts. Our uptime status page shows real incidents — not just the ones we\'re proud of.</p>'."\n".'<h2 id="benefits">The Unexpected Benefits</h2>'."\n".'<p>Working in public attracts contributors. Three of our best feature ideas came from organizers who read our roadmap and said "have you considered..." It also keeps us honest — if you promise something publicly, you feel accountable to deliver it.</p>'."\n".'<h2 id="hard-parts">The Hard Parts</h2>'."\n".'<p>Not everything can be public. Security vulnerabilities, user data, and pre-launch partnerships need privacy. We\'ve learned to draw the line at "would sharing this create risk for our users?" If yes, it stays internal.</p>';
    }

    private function post07ContentHtml(): string
    {
        return '<p>We\'re taking Qariyb global. Starting this month, event organizers in London, Toronto, and Kuala Lumpur can list, promote, and manage their events on our platform.</p>'."\n".'<h2 id="why-these-cities">Why These Cities</h2>'."\n".'<p>These three cities have thriving Muslim communities with active event scenes — from tech meetups to charity fundraisers to educational workshops. We\'ve been getting requests from organizers in all three for over a year.</p>'."\n".'<h2 id="available">What\'s Available</h2>'."\n".'<p>Full platform access including event creation, ticketing, QR check-in, analytics, and our new multi-day scheduling feature. Payment processing is available in local currencies (GBP, CAD, MYR) through Stripe Connect.</p>'."\n".'<h2 id="local-support">Local Support</h2>'."\n".'<p>We\'re hiring community managers in each city to provide local support and build relationships with organizers. If you\'re interested, check our careers page.</p>'."\n".'<h2 id="whats-next">What\'s Next</h2>'."\n".'<p>Dubai, Istanbul, and Jakarta are on our radar for Q2 2026. We\'re prioritising cities where we already have waitlisted organizers.</p>';
    }

    private function post08ContentHtml(): string
    {
        return '<p>Location-based event discovery is our most-used feature. Users open the app and see events near them — but "near them" was taking 800ms to compute. We moved the logic to edge functions and cut that to 45ms.</p>'."\n".'<h2 id="bottleneck">The Bottleneck</h2>'."\n".'<p>Our geolocation queries ran against a PostgreSQL database in us-east-1. A user in Kuala Lumpur experienced 600ms of network latency before the query even started. Add the Haversine distance calculation across 20,000 events, and responses were slow.</p>'."\n".'<h2 id="edge-architecture">Edge Architecture</h2>'."\n".'<p>We pre-compute a geospatial index of active events and replicate it to 14 edge locations worldwide using Cloudflare Workers KV. When a user requests nearby events, the edge function performs the distance calculation locally.</p>'."\n".'<h2 id="tradeoffs">The Tradeoffs</h2>'."\n".'<p>Edge data is eventually consistent — a new event might take up to 60 seconds to appear in nearby searches. We decided this was acceptable because events are created hours or days before they\'re discovered. The latency improvement far outweighed the consistency delay.</p>'."\n".'<h2 id="results">Results</h2>'."\n".'<p>Response time dropped from 800ms to 45ms globally. The experience feels instant regardless of where users are located.</p>';
    }

    private function post09ContentHtml(): string
    {
        return '<p>Event organizers juggle a lot. Ticket sales, attendee communications, volunteer coordination, venue logistics — and they need all of it at their fingertips. Our original dashboard tried to show everything at once and ended up showing nothing well.</p>'."\n".'<h2 id="research">Research Phase</h2>'."\n".'<p>We interviewed 24 organizers across five countries. The recurring theme was "I just want to know if things are on track." They didn\'t need 15 charts — they needed three numbers and a way to take action fast.</p>'."\n".'<h2 id="principles">Three Design Principles</h2>'."\n".'<ol><li><strong>At-a-glance insights:</strong> The most important metrics (tickets sold, revenue, check-in rate) are visible without scrolling.</li><li><strong>Fast actions:</strong> Common tasks (send announcement, export attendee list, duplicate event) are one click away.</li><li><strong>Zero configuration:</strong> The dashboard adapts to the event type — a free community meetup doesn\'t need revenue widgets.</li></ol>'."\n".'<h2 id="architecture">Component Architecture</h2>'."\n".'<p>We built the dashboard as composable widgets. Each widget is a self-contained React component that fetches its own data and handles its own loading state. Organizers can rearrange and hide widgets to customise their view.</p>'."\n".'<h2 id="outcome">The Outcome</h2>'."\n".'<p>Time-to-first-action (how quickly an organizer performs a task after opening the dashboard) dropped from 34 seconds to 8 seconds. Net Promoter Score for the dashboard went from 32 to 71.</p>';
    }

    private function post10ContentHtml(): string
    {
        return '<p>We analysed 5,000+ events hosted on Qariyb over the past year. Here\'s what we found about attendance trends, popular formats, and what\'s coming next for Muslim tech events.</p>'."\n".'<h2 id="volume">Event Volume Growth</h2>'."\n".'<p>Total events grew 127% year-over-year. The fastest-growing categories were tech meetups (+180%), mental health workshops (+210%), and hybrid religious-social gatherings (+95%).</p>'."\n".'<h2 id="formats">Format Trends</h2>'."\n".'<p>In-person events still dominate (72%), but hybrid events grew from 8% to 19% of total events. Fully virtual events declined from 20% to 9%, suggesting the community strongly prefers gathering physically.</p>'."\n".'<h2 id="attendance">Attendance Patterns</h2>'."\n".'<p>Average event size is 142 attendees, up from 98 last year. The median is lower at 65, indicating that while most events are intimate, a growing number of large-scale events are pulling the average up. Ramadan remains the peak month with 340% more events than the monthly average.</p>'."\n".'<h2 id="geography">Geographic Distribution</h2>'."\n".'<p>North America leads with 45% of events, followed by the UK (22%), Southeast Asia (18%), and the Middle East (15%). The fastest-growing region is Southeast Asia, driven by Malaysia and Indonesia.</p>'."\n".'<h2 id="whats-next">What\'s Next</h2>'."\n".'<p>We expect 2026 to see more professional development events, cross-city event series, and increased demand for Arabic-language support. We\'re building for all of it.</p>';
    }

    private function post11ContentHtml(): string
    {
        return '<p>When organizers sell tickets on Qariyb, money flows through multiple parties: the attendee pays, Stripe processes, Qariyb takes a platform fee, and the organizer receives the rest. Add refunds, multi-currency support, and tax compliance across 12 countries, and you have a non-trivial payment pipeline.</p>'."\n".'<h2 id="why-stripe">Why Stripe Connect</h2>'."\n".'<p>We evaluated building our own payment splitting logic, but the regulatory burden was enormous. Stripe Connect handles KYC, tax reporting, and money transmission licensing. We focus on the product; Stripe handles compliance.</p>'."\n".'<h2 id="architecture">Architecture</h2>'."\n".'<p>Every organizer has a Connected Account on Stripe. When a ticket is purchased, we create a PaymentIntent with automatic transfers. Our platform fee is deducted, and the remainder is transferred to the organizer\'s connected account.</p>'."\n".'<h2 id="refunds">Handling Refunds</h2>'."\n".'<p>Refunds reverse the payment split proportionally. If our fee was 5%, we refund 5% of our portion and 95% comes from the organizer\'s balance. Edge case: if the organizer has already withdrawn funds, Stripe handles negative balances via future payouts.</p>'."\n".'<h2 id="multi-currency">Multi-Currency Challenges</h2>'."\n".'<p>An organizer in Malaysia charges in MYR, but their connected account settles in MYR. An attendee in the UK pays in GBP. Stripe handles the conversion, but we had to build logic to display accurate amounts in both currencies throughout the checkout flow.</p>'."\n".'<h2 id="lessons">Lessons</h2>'."\n".'<p>Payment integrations are deceptively complex. Budget 3x the time you think you need. Test every edge case with real money in test mode. And never, ever store card numbers — let Stripe handle all sensitive data.</p>';
    }

    private function post12ContentHtml(): string
    {
        return '<p>Our test suite grew to 1,800 tests and our CI pipeline ballooned to 18 minutes. Developers started running only local tests and skipping CI feedback. We had to fix it.</p>'."\n".'<h2 id="diagnosing">Diagnosing the Problem</h2>'."\n".'<p>We profiled every step. The breakdown: dependency installation (4min), asset compilation (3min), database migrations (2min), test execution (7min), and miscellaneous (2min). Every stage had optimisation potential.</p>'."\n".'<h2 id="caching">Caching Dependencies</h2>'."\n".'<p>We cached Composer and npm dependencies keyed on lockfile hashes. This alone saved 3.5 minutes on cache-hit runs (which is 95% of runs).</p>'."\n".'<h2 id="parallel">Parallel Test Execution</h2>'."\n".'<p>We split our test suite across 4 parallel runners using Pest\'s parallel flag. Each runner gets a fresh database. Total test execution dropped from 7 minutes to 2 minutes.</p>'."\n".'<h2 id="assets">Asset Build Optimisation</h2>'."\n".'<p>We only build frontend assets when frontend files have changed (detected via git diff). Backend-only PRs skip the asset step entirely, saving 3 minutes.</p>'."\n".'<h2 id="result">The Result</h2>'."\n".'<p>Average pipeline time: 18 minutes → 4 minutes. Cache-hit backend-only PRs finish in under 3 minutes. Developer satisfaction with CI went from "I ignore it" to "I wait for it before merging."</p>';
    }

    private function post13ContentHtml(): string
    {
        return '<p>After six months of development, the Qariyb mobile app is live on iOS and Android. We chose React Native and it was the right call — but not without tradeoffs.</p>'."\n".'<h2 id="why-rn">Why React Native</h2>'."\n".'<p>Our team is JavaScript-heavy, and we needed to ship on both platforms simultaneously. Flutter was a contender, but React Native\'s ecosystem and our existing React knowledge tipped the scales.</p>'."\n".'<h2 id="code-sharing">Sharing Code With Web</h2>'."\n".'<p>We share about 40% of our business logic between web and mobile through a shared TypeScript package. API clients, validation schemas, and data transformations live in this shared layer. UI components are platform-specific.</p>'."\n".'<h2 id="performance">Performance Challenges</h2>'."\n".'<p>The event discovery feed was janky on older Android devices. We solved it with FlashList (replacing FlatList), windowed rendering, and aggressive image caching. Scroll performance went from 42fps to a consistent 60fps.</p>'."\n".'<h2 id="notifications">Push Notifications</h2>'."\n".'<p>We built a notification system that reminds attendees about upcoming events, sends check-in prompts when they arrive at the venue (using geofencing), and delivers post-event surveys. Open rates are 3x higher than email.</p>'."\n".'<h2 id="surprise">What Surprised Us</h2>'."\n".'<p>The biggest surprise was how much users value offline access. People browse events on the train, in areas with spotty coverage. We added offline caching for bookmarked events and it became one of our highest-rated features.</p>';
    }

    private function post14ContentHtml(): string
    {
        return '<p>Ramadan is our Super Bowl. Event creation spikes 340%, ticket sales surge overnight, and our support inbox triples. Here\'s our playbook for preparing infrastructure, product, and team for the annual rush.</p>'."\n".'<h2 id="traffic">Traffic Patterns</h2>'."\n".'<p>Ramadan events cluster around Iftar (sunset), Tarawih (night prayers), and weekends. This creates predictable but intense traffic spikes — 8x normal load between 5pm and 10pm local time, but the "local time" shifts across 24 time zones.</p>'."\n".'<h2 id="infrastructure">Infrastructure Prep</h2>'."\n".'<p>We pre-scale our Kubernetes cluster two weeks before Ramadan starts. Horizontal pod autoscaling handles the moment-to-moment fluctuations, but base capacity needs to be higher to avoid cold-start latency during sudden spikes.</p>'."\n".'<h2 id="database">Database Readiness</h2>'."\n".'<p>We add read replicas and pre-warm query caches with anticipated access patterns. Last year, a missing index on the events table caused a 30-second query during peak — this year, we ran EXPLAIN on every hot query path.</p>'."\n".'<h2 id="product">Product Preparation</h2>'."\n".'<p>We freeze non-critical deployments during Ramadan. Only bug fixes and security patches go out. Feature launches happen before or after — never during. We also pre-build Ramadan-specific templates (Iftar dinner, Tarawih schedule, Eid celebration) so organizers can get started in minutes.</p>'."\n".'<h2 id="team">Team Coordination</h2>'."\n".'<p>Support shifts are extended and on-call rotations are tightened. We run a daily standup focused solely on Ramadan metrics: event creation rate, payment success rate, support ticket volume, and error rates.</p>';
    }

    private function post15ContentHtml(): string
    {
        return '<p>We built Noor UI, our internal design system, to ensure consistency across web, mobile, and the organizer dashboard. After 18 months of iteration, here are the principles, components, and lessons.</p>'."\n".'<h2 id="why-build">Why Build Our Own</h2>'."\n".'<p>We started with Tailwind UI and Headless UI. They\'re excellent, but we needed components that understood our domain — event cards, ticket selectors, seat maps, schedule builders. Off-the-shelf components got us 60% of the way; we needed to own the rest.</p>'."\n".'<h2 id="principles">Design Principles</h2>'."\n".'<p>Noor UI is built on three principles: <strong>accessible by default</strong> (every component meets WCAG AA), <strong>RTL-ready</strong> (our users speak Arabic, Urdu, and Malay), and <strong>theme-aware</strong> (full dark mode support without per-component overrides).</p>'."\n".'<h2 id="components">Component Library</h2>'."\n".'<p>The library includes 67 components across four categories: primitives (buttons, inputs, badges), layout (grids, cards, modals), domain (event card, ticket picker, schedule block), and data display (charts, tables, stat cards).</p>'."\n".'<h2 id="docs">Documentation</h2>'."\n".'<p>Every component has a Storybook page with usage examples, prop documentation, and accessibility notes. New engineers can browse the library and build screens without asking "which component do I use for this?"</p>'."\n".'<h2 id="open-source">Considering Open Source</h2>'."\n".'<p>We\'re considering open-sourcing Noor UI. The domain-specific components (event cards, schedule builders) are probably too niche, but our accessible, RTL-ready primitives might be useful to other teams building for multilingual Muslim audiences.</p>';
    }

    private function post16ContentHtml(): string
    {
        return '<p>ICNA\'s annual convention is one of the largest Islamic events in North America, drawing over 15,000 attendees across three days. When they chose Qariyb for 2025, it was our biggest test yet.</p>'."\n".'<h2 id="requirements">The Requirements</h2>'."\n".'<p>ICNA needed: 8 registration tracks with different pricing, 120+ sessions across 6 concurrent tracks, VIP and speaker badge management, real-time occupancy tracking for fire marshal compliance, and multi-gate QR check-in.</p>'."\n".'<h2 id="registration">Registration Setup</h2>'."\n".'<p>We configured tiered registration: Early Bird, Standard, On-Site, Student, and Family packages. Each tier had different access levels and included different session bundles. The family package allowed up to 6 attendees under a single registration.</p>'."\n".'<h2 id="checkin">Check-In Day</h2>'."\n".'<p>Opening day saw 6,200 check-ins in the first 90 minutes across 12 gates. Our scanning system handled the load without a single timeout. The average check-in time was 3.2 seconds per attendee, including badge printing.</p>'."\n".'<h2 id="occupancy">Occupancy Tracking</h2>'."\n".'<p>Each session room had a dedicated scanner that tracked entries and exits. The fire marshal required real-time occupancy counts, and our dashboard provided them with a 5-second refresh rate.</p>'."\n".'<h2 id="results">Results</h2>'."\n".'<p>ICNA reported a 45% reduction in registration-related support tickets compared to the previous year. Their team of 40 volunteers managed check-in for 15,000 people with zero paper and zero spreadsheets.</p>';
    }

    private function post17ContentHtml(): string
    {
        return '<p>Organizers want to see their events come alive in real time — attendee arrivals ticking up, ticket sales as they happen, session occupancy filling. We built a WebSocket layer with Laravel Reverb that pushes 50,000 events per minute.</p>'."\n".'<h2 id="why-reverb">Why Laravel Reverb</h2>'."\n".'<p>We considered Pusher and Soketi, but Laravel Reverb is first-party, self-hosted, and deeply integrated with Laravel\'s broadcasting system. No external service dependency and no per-message pricing.</p>'."\n".'<h2 id="architecture">Architecture</h2>'."\n".'<p>Every significant action (ticket purchase, check-in, session join) dispatches a Laravel event that\'s broadcast to the organizer\'s private channel. The frontend subscribes via Echo and updates the dashboard widgets in real time.</p>'."\n".'<h2 id="scaling">Scaling Challenges</h2>'."\n".'<p>During a large event, a single organizer channel might receive 100 events per second. We batch updates on the server side — instead of sending individual check-in events, we send aggregated counts every 2 seconds. This reduced WebSocket message volume by 85%.</p>'."\n".'<h2 id="connections">Connection Management</h2>'."\n".'<p>We handle connection drops gracefully with exponential backoff reconnection. When a connection is re-established, the client requests a state snapshot to catch up on missed events rather than replaying the entire history.</p>'."\n".'<h2 id="dx">The Developer Experience</h2>'."\n".'<p>For our engineers, adding a new real-time feature is three steps: dispatch a broadcast event, define the channel authorization, and subscribe in the React component. The entire WebSocket infrastructure is invisible.</p>';
    }

    private function post18ContentHtml(): string
    {
        return '<p>We launched our Ambassador Programme six months ago to build a grassroots network of community connectors. It\'s now active in 14 cities across 6 countries.</p>'."\n".'<h2 id="concept">The Concept</h2>'."\n".'<p>Ambassadors are local community members — not Qariyb employees — who help organizers in their city get started on the platform. They run onboarding sessions, provide first-line support, and give us feedback on what their community needs.</p>'."\n".'<h2 id="selection">Selection Process</h2>'."\n".'<p>We received 340 applications and selected 28 ambassadors. We looked for people who were already organising events or deeply embedded in their local Muslim community. Technical skills were a bonus but not a requirement.</p>'."\n".'<h2 id="worked">What Worked</h2>'."\n".'<p>Ambassadors drove 3x more organizer sign-ups than our paid marketing in the same cities. The personal touch matters — a WhatsApp message from a trusted community member converts better than any ad. Ambassadors also surfaced feature requests we never would have heard through our support channels.</p>'."\n".'<h2 id="change">What We\'d Change</h2>'."\n".'<p>We underestimated the support ambassadors themselves need. They\'re volunteers, and we initially treated the programme like a set-it-and-forget-it initiative. We\'ve since added monthly video calls, a private Slack channel, and quarterly care packages.</p>'."\n".'<h2 id="ahead">Looking Ahead</h2>'."\n".'<p>We\'re opening applications for a second cohort focused on Southeast Asia and the Middle East. Our goal is 50 ambassadors across 30 cities by end of 2026.</p>';
    }

    private function post19ContentHtml(): string
    {
        return '<p>With a team of five engineers, everyone carries a pager. We share our on-call rotation, escalation policies, and the runbooks that keep Qariyb running at 99.95% uptime.</p>'."\n".'<h2 id="rotation">The Rotation</h2>'."\n".'<p>We run a weekly rotation. Each engineer is primary on-call for one week every five weeks. The previous week\'s on-call is secondary (backup). This gives everyone a predictable schedule and enough recovery time between shifts.</p>'."\n".'<h2 id="escalation">Escalation Policy</h2>'."\n".'<p>PagerDuty alert → 5 minutes to acknowledge → 15 minutes to assess severity. P1 (site down) escalates to the whole team immediately. P2 (degraded service) stays with on-call. P3 (non-urgent) goes to the backlog for next business day.</p>'."\n".'<h2 id="runbooks">Runbooks</h2>'."\n".'<p>Every alert has a linked runbook. We learned this the hard way — at 3am, you don\'t want to debug from first principles. Our runbooks follow a template: symptom, likely cause, diagnostic steps, fix steps, and who to escalate to if the fix doesn\'t work.</p>'."\n".'<h2 id="sustainability">Sustainability</h2>'."\n".'<p>On-call burnout is real. We compensate on-call with time off in lieu — every on-call week earns a half-day off. We also track interrupt frequency and invest in reducing alerts. Our goal is zero pages per week (we\'re currently averaging 1.2).</p>'."\n".'<h2 id="results">Results</h2>'."\n".'<p>Over the past 12 months: 99.95% uptime, mean time to acknowledge of 2.3 minutes, mean time to resolve of 18 minutes for P1 incidents, and zero incidents where the on-call couldn\'t resolve without escalation.</p>';
    }

    private function post20ContentHtml(): string
    {
        return '<p>Qariyb API v2 is here. It brings batch operations, webhook signatures, cursor-based pagination, and full OpenAPI 3.1 documentation. Here\'s what changed and how to migrate.</p>'."\n".'<h2 id="whats-new">What\'s New</h2>'."\n".'<p><strong>Batch Operations:</strong> Create or update up to 100 events in a single request. Ideal for organisers who import events from external calendars.</p>'."\n".'<p><strong>Webhook Signatures:</strong> Every webhook payload is now signed with HMAC-SHA256. Verify the signature to ensure the payload came from Qariyb and hasn\'t been tampered with.</p>'."\n".'<p><strong>Cursor Pagination:</strong> We replaced offset-based pagination with cursor-based pagination. This eliminates the performance cliff on large result sets and provides stable pagination when new items are added during traversal.</p>'."\n".'<p><strong>OpenAPI 3.1 Spec:</strong> The full API is documented in an OpenAPI spec file that you can import into Postman, generate client SDKs, or use with any OpenAPI-compatible tool.</p>'."\n".'<h2 id="migration">Migration Guide</h2>'."\n".'<p>API v1 will continue to work until June 2026. We recommend migrating to v2 before then. The main breaking changes are: pagination response format, webhook payload structure, and authentication header format (Bearer token instead of API key query parameter).</p>'."\n".'<h2 id="sdks">SDKs</h2>'."\n".'<p>We\'re releasing official SDKs for PHP, JavaScript/TypeScript, and Python. The PHP SDK is available now via Composer. JS and Python SDKs are coming in February.</p>';
    }

    private function post21ContentHtml(): string
    {
        return '<p>We trained a recommendation model on anonymous attendance patterns to help users discover events they\'ll love. Here\'s the ML pipeline and the privacy-first approach we took.</p>'."\n".'<h2 id="problem">The Problem</h2>'."\n".'<p>Users attending Islamic finance workshops were seeing recommendations for children\'s Quran classes. Our basic "same category" recommendations weren\'t cutting it. We needed to understand user intent, not just content labels.</p>'."\n".'<h2 id="privacy">Privacy-First Design</h2>'."\n".'<p>We never train on personal data. Instead, we use anonymised attendance vectors — a user is represented as a pattern of event-type interactions, not as a named individual. All training data is aggregated and differential privacy noise is added before model training.</p>'."\n".'<h2 id="model">The Model</h2>'."\n".'<p>We use a collaborative filtering approach with matrix factorisation. Users and events are embedded in a 64-dimensional space. Events close to a user\'s embedding are recommended. The model is retrained weekly on new attendance data.</p>'."\n".'<h2 id="cold-start">Cold Start</h2>'."\n".'<p>New users with no history get popularity-based recommendations filtered by location and expressed interests (collected during onboarding). After attending 3+ events, the model kicks in and recommendations become personalised.</p>'."\n".'<h2 id="results">Results</h2>'."\n".'<p>Click-through rate on recommendations improved from 4.2% (category-based) to 11.8% (ML-based). Event discovery time (how long users spend searching before finding an event to attend) dropped by 40%.</p>';
    }

    private function post22ContentHtml(): string
    {
        return '<p>Should community events use dynamic pricing? We ran experiments with 50 organizers to find out. The results challenged our assumptions about what works for Muslim community events.</p>'."\n".'<h2 id="hypothesis">The Hypothesis</h2>'."\n".'<p>Dynamic pricing — adjusting ticket prices based on demand, time to event, and remaining capacity — is standard in airlines and concerts. We hypothesised it could help organizers maximise both revenue and attendance.</p>'."\n".'<h2 id="experiment">The Experiment</h2>'."\n".'<p>We gave 50 organizers access to three pricing strategies: fixed pricing (control), early-bird tiers (time-based), and demand-based pricing (capacity-based). Each organizer ran at least two events with different strategies.</p>'."\n".'<h2 id="results">Surprising Results</h2>'."\n".'<p>Early-bird tiers increased revenue by 18% with no negative impact on attendance. But demand-based pricing decreased attendance by 12% — attendees perceived rising prices as unfair, especially for community and religious events where accessibility is valued.</p>'."\n".'<h2 id="nuance">The Nuance</h2>'."\n".'<p>The negative reaction to demand-based pricing was strongest for religious and community events but didn\'t exist for professional development events. Context matters: charging more as a workshop fills up feels like scarcity pricing, but charging more as a halaqa fills up feels exclusionary.</p>'."\n".'<h2 id="recommendation">Our Recommendation</h2>'."\n".'<p>We\'re launching early-bird tiers as a default feature for all organizers. Demand-based pricing will be available as an opt-in for professional events only, with clear guidance on when it\'s appropriate.</p>';
    }
}
