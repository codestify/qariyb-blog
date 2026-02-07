<?php

use App\Data\ArticleData;
use App\Models\Post;
use Database\Seeders\NovusSeeder;
use Shah\Novus\Enums\PostStatus;
use Shah\Novus\Models\Author;
use Shah\Novus\Models\Category;

beforeEach(function () {
    $this->seed(NovusSeeder::class);
});

test('homepage returns 200 with posts from the database', function () {
    $this->get('/')->assertSuccessful();

    $posts = ArticleData::all();
    expect($posts)->toHaveCount(21)
        ->and($posts->first())->toBeInstanceOf(Post::class);
});

test('article page returns 200 for a valid slug', function () {
    $this->get('/articles/real-time-ticket-scanning')->assertSuccessful();
});

test('article page returns 404 for an invalid slug', function () {
    $this->get('/articles/this-slug-does-not-exist')->assertNotFound();
});

test('category filtering returns only posts in that category', function () {
    $engineeringPosts = ArticleData::whereCategory('engineering');

    expect($engineeringPosts)->each(
        fn ($post) => $post->category->toBe('engineering'),
    );
});

test('draft posts are excluded from published queries', function () {
    $all = ArticleData::all();
    $slugs = $all->pluck('slug')->all();

    expect($slugs)->not->toContain('dynamic-pricing-experiments');
});

test('featured post is returned correctly', function () {
    $featured = ArticleData::featured();

    expect($featured)->not->toBeNull()
        ->and($featured->is_featured)->toBeTrue()
        ->and($featured->slug)->toBe('real-time-ticket-scanning');
});

test('post accessors return expected values', function () {
    $post = ArticleData::findBySlug('real-time-ticket-scanning');

    expect($post->body)->toContain('<p>')
        ->and($post->category)->toBe('engineering')
        ->and($post->author_name)->toBe('Abubakar Sheriff')
        ->and($post->author_initials)->toBe('AS')
        ->and($post->author_role)->toBe('Founder & CEO')
        ->and($post->read_time)->toBeGreaterThanOrEqual(1);
});

test('related posts excludes the current article', function () {
    $related = ArticleData::relatedTo('real-time-ticket-scanning');

    expect($related)->toHaveCount(2)
        ->and($related->pluck('slug'))->not->toContain('real-time-ticket-scanning');
});

test('categories are fetched from the database', function () {
    $categories = ArticleData::categories();

    expect($categories)->toContain('engineering', 'product', 'community', 'culture', 'announcements')
        ->and($categories)->toBe(collect($categories)->sort()->values()->all());
});
