<?php

use App\Http\Middleware\ComingSoonMiddleware;
use App\Livewire\ArticlePage;
use App\Livewire\HomePage;
use Illuminate\Support\Facades\Route;

Route::middleware(ComingSoonMiddleware::class)->group(function () {
    Route::livewire('/', HomePage::class)->name('home');
    Route::livewire('/articles/{slug}', ArticlePage::class)->name('article.show');
});
