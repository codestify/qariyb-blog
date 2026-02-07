<?php

use App\Livewire\PostFilter;
use Database\Seeders\NovusSeeder;
use Livewire\Livewire;

beforeEach(function () {
    $this->seed(NovusSeeder::class);
});

// ── Existing behaviour ───────────────────────────────────────

test('post filter shows all posts by default', function () {
    Livewire::test(PostFilter::class)
        ->assertSee('All Posts')
        ->assertSee('Migrating Our Event Search to Elasticsearch')
        ->assertSee('Introducing Multi-Day Event Scheduling');
});

test('post filter shows featured post when viewing all', function () {
    Livewire::test(PostFilter::class)
        ->assertSee('Featured');
});

test('post filter filters by category', function () {
    Livewire::test(PostFilter::class)
        ->call('filterByCategory', 'product')
        ->assertSet('activeCategory', 'product')
        ->assertSee('Introducing Multi-Day Event Scheduling')
        ->assertDontSee('Migrating Our Event Search to Elasticsearch');
});

test('post filter hides featured post when filtering by category', function () {
    Livewire::test(PostFilter::class)
        ->call('filterByCategory', 'engineering')
        ->assertDontSee('Featured');
});

test('post filter returns to all posts', function () {
    Livewire::test(PostFilter::class)
        ->call('filterByCategory', 'product')
        ->call('filterByCategory', 'all')
        ->assertSet('activeCategory', 'all')
        ->assertSee('All Posts');
});

// ── Search ───────────────────────────────────────────────────

test('search filters posts by title', function () {
    Livewire::test(PostFilter::class)
        ->set('search', 'Elasticsearch')
        ->assertSee('Migrating Our Event Search to Elasticsearch')
        ->assertDontSee('Introducing Multi-Day Event Scheduling')
        ->assertSee('Search Results');
});

test('search filters posts by excerpt', function () {
    Livewire::test(PostFilter::class)
        ->set('search', 'full-text search engine')
        ->assertSee('Migrating Our Event Search to Elasticsearch');
});

test('search hides the featured post section', function () {
    Livewire::test(PostFilter::class)
        ->set('search', 'Elasticsearch')
        ->assertDontSee('Featured');
});

test('search combined with category filter works', function () {
    Livewire::test(PostFilter::class)
        ->set('search', 'dashboard')
        ->call('filterByCategory', 'product')
        ->assertSee('Designing the Organizer Dashboard')
        ->assertDontSee('WebSocket Architecture for Live Event Dashboards');
});

test('search resets to page 1', function () {
    Livewire::test(PostFilter::class)
        ->call('nextPage')
        ->assertViewHas('posts', fn ($posts) => $posts->currentPage() === 2)
        ->set('search', 'Elasticsearch')
        ->assertViewHas('posts', fn ($posts) => $posts->currentPage() === 1);
});

test('empty search restores all posts and featured', function () {
    Livewire::test(PostFilter::class)
        ->set('search', 'Elasticsearch')
        ->assertDontSee('Featured')
        ->set('search', '')
        ->assertSee('Featured')
        ->assertSee('All Posts');
});

test('no results shows empty state', function () {
    Livewire::test(PostFilter::class)
        ->set('search', 'xyznonexistent')
        ->assertSee("No articles found for 'xyznonexistent'")
        ->assertSee('Clear filters');
});

test('clear filters resets search, category, and page', function () {
    Livewire::test(PostFilter::class)
        ->set('search', 'Elasticsearch')
        ->call('filterByCategory', 'engineering')
        ->call('clearFilters')
        ->assertSet('search', '')
        ->assertSet('activeCategory', 'all')
        ->assertViewHas('posts', fn ($posts) => $posts->currentPage() === 1)
        ->assertSee('All Posts');
});

// ── Pagination ───────────────────────────────────────────────

test('displays 9 posts per page', function () {
    Livewire::test(PostFilter::class)
        ->assertViewHas('posts', fn ($posts) => $posts->count() === 9 && $posts->perPage() === 9);
});

test('navigate to next page', function () {
    Livewire::test(PostFilter::class)
        ->call('nextPage')
        ->assertViewHas('posts', fn ($posts) => $posts->currentPage() === 2 && $posts->count() === 9);
});

test('navigate to previous page', function () {
    Livewire::test(PostFilter::class)
        ->call('nextPage')
        ->call('previousPage')
        ->assertViewHas('posts', fn ($posts) => $posts->currentPage() === 1);
});

test('featured post only shows on page 1', function () {
    Livewire::test(PostFilter::class)
        ->assertSee('Featured')
        ->call('nextPage')
        ->assertDontSee('Featured');
});

test('category change resets to page 1', function () {
    Livewire::test(PostFilter::class)
        ->call('nextPage')
        ->assertViewHas('posts', fn ($posts) => $posts->currentPage() === 2)
        ->call('filterByCategory', 'engineering')
        ->assertViewHas('posts', fn ($posts) => $posts->currentPage() === 1);
});

test('total count reflects filtered results', function () {
    Livewire::test(PostFilter::class)
        ->assertViewHas('totalCount', 20)
        ->call('filterByCategory', 'product')
        ->assertViewHas('totalCount', 4);
});

test('search query persists in url via query params', function () {
    Livewire::withQueryParams(['q' => 'Elasticsearch'])
        ->test(PostFilter::class)
        ->assertSet('search', 'Elasticsearch')
        ->assertSee('Migrating Our Event Search to Elasticsearch');
});
