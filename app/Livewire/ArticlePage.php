<?php

namespace App\Livewire;

use App\Data\ArticleData;
use App\Models\Post;
use Illuminate\Support\Collection;
use Livewire\Component;

class ArticlePage extends Component
{
    public Post $article;

    /** @var array<int, array{id: string, text: string}> */
    public array $toc = [];

    public Collection $relatedPosts;

    public function mount(string $slug): void
    {
        $article = ArticleData::findBySlug($slug);

        if (! $article) {
            abort(404);
        }

        $this->article = $article;
        $this->relatedPosts = ArticleData::relatedTo($slug);
        $this->toc = $this->extractToc($article->content_html ?? '');
    }

    /**
     * @return array<int, array{id: string, text: string}>
     */
    private function extractToc(string $html): array
    {
        preg_match_all('/<h2\s+id="([^"]+)"[^>]*>([^<]+)<\/h2>/', $html, $matches, PREG_SET_ORDER);

        return array_map(fn (array $match) => [
            'id' => $match[1],
            'text' => html_entity_decode($match[2]),
        ], $matches);
    }

    public function render()
    {
        return view('livewire.article-page')
            ->title($this->article->title . ' — Qariyb Blog');
    }
}
