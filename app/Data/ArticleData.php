<?php

namespace App\Data;

use App\Models\Category;
use App\Models\Post;
use Illuminate\Support\Collection;

class ArticleData
{
    /**
     * @return Collection<int, Post>
     */
    public static function all(): Collection
    {
        return Post::query()
            ->published()
            ->with(['author', 'categories'])
            ->latest('published_at')
            ->get();
    }

    public static function featured(): ?Post
    {
        return Post::query()
            ->published()
            ->featured()
            ->with(['author', 'categories'])
            ->first();
    }

    public static function findBySlug(string $slug): ?Post
    {
        return Post::query()
            ->published()
            ->with(['author', 'categories'])
            ->where('slug', $slug)
            ->first();
    }

    /**
     * @return array<int, string>
     */
    public static function categories(): array
    {
        return Category::query()
            ->orderBy('name')
            ->pluck('slug')
            ->all();
    }

    /**
     * @return Collection<int, Post>
     */
    public static function whereCategory(string $category): Collection
    {
        return Post::query()
            ->published()
            ->with(['author', 'categories'])
            ->whereHas('categories', fn ($q) => $q->where('slug', $category))
            ->where('is_featured', false)
            ->latest('published_at')
            ->get();
    }

    /**
     * @return Collection<int, Post>
     */
    public static function relatedTo(string $slug, int $limit = 2): Collection
    {
        $article = static::findBySlug($slug);

        if (! $article) {
            return collect();
        }

        $categorySlug = $article->category;

        if (! $categorySlug) {
            return collect();
        }

        return Post::query()
            ->published()
            ->with(['author', 'categories'])
            ->where('slug', '!=', $slug)
            ->whereHas('categories', fn ($q) => $q->where('slug', $categorySlug))
            ->latest('published_at')
            ->limit($limit)
            ->get();
    }
}
