<?php

namespace App\Livewire;

use App\Data\ArticleData;
use App\Models\Post;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class PostFilter extends Component
{
    use WithPagination;

    #[Url(as: 'q')]
    public string $search = '';

    #[Url(as: 'category')]
    public string $activeCategory = 'all';

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function filterByCategory(string $category): void
    {
        $this->activeCategory = $category;
        $this->resetPage();
    }

    public function clearFilters(): void
    {
        $this->search = '';
        $this->activeCategory = 'all';
        $this->resetPage();
    }

    public function render()
    {
        $query = Post::query()
            ->published()
            ->with(['author', 'categories'])
            ->where('is_featured', false);

        if ($this->activeCategory !== 'all') {
            $query->whereHas('categories', fn ($q) => $q->where('slug', $this->activeCategory));
        }

        if ($this->search !== '') {
            $query->where(fn ($q) => $q
                ->where('title', 'like', "%{$this->search}%")
                ->orWhere('excerpt', 'like', "%{$this->search}%")
            );
        }

        $posts = $query->latest('published_at')->paginate(9);

        $showFeatured = $this->activeCategory === 'all'
            && $this->search === ''
            && $posts->onFirstPage();

        return view('livewire.post-filter', [
            'posts' => $posts,
            'featured' => $showFeatured ? ArticleData::featured() : null,
            'categories' => ArticleData::categories(),
            'totalCount' => $posts->total(),
        ]);
    }
}
