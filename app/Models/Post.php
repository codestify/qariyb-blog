<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Support\Str;
use Shah\Novus\Enums\PostStatus;
use Shah\Novus\Models\Post as NovusPost;

class Post extends NovusPost
{
    public function author(): BelongsTo
    {
        return $this->belongsTo(Author::class, 'author_id');
    }

    public function categories(): MorphToMany
    {
        return $this->morphToMany(Category::class, 'categorizable', 'novus_categorizables')
            ->using(\Illuminate\Database\Eloquent\Relations\MorphPivot::class);
    }

    /**
     * @param  \Illuminate\Database\Eloquent\Builder<self>  $query
     */
    public function scopePublished($query): void
    {
        $query->where('status', PostStatus::Published)
            ->where('published_at', '<=', now());
    }

    /**
     * @param  \Illuminate\Database\Eloquent\Builder<self>  $query
     */
    public function scopeFeatured($query): void
    {
        $query->where('is_featured', true);
    }

    protected function body(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->content_html,
        );
    }

    protected function category(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->categories->first()?->slug,
        );
    }

    protected function authorName(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->author?->name,
        );
    }

    protected function authorInitials(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->author?->initials,
        );
    }

    protected function authorRole(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->author ? Str::of($this->author->bio)
                ->before('.')
                ->replace([' at Qariyb', ' of Qariyb'], '')
                ->trim()
                ->toString() : null,
        );
    }

    protected function readTime(): Attribute
    {
        return Attribute::make(
            get: fn () => (int) max(1, ceil(str_word_count(strip_tags($this->content_html ?? '')) / 200)),
        );
    }

    public function getMorphClass(): string
    {
        return NovusPost::class;
    }
}
