<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;
use Shah\Novus\Models\Author as NovusAuthor;

class Author extends NovusAuthor
{
    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }

    protected function role(): Attribute
    {
        return Attribute::make(
            get: fn () => Str::of($this->bio)
                ->before('.')
                ->replace([' at Qariyb', ' of Qariyb'], '')
                ->trim()
                ->toString(),
        );
    }
}
