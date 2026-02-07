<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Shah\Novus\Models\Category as NovusCategory;

class Category extends NovusCategory
{
    public function posts(): MorphToMany
    {
        return $this->morphedByMany(Post::class, 'categorizable', 'novus_categorizables');
    }
}
