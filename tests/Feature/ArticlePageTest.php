<?php

use App\Livewire\ArticlePage;
use Database\Seeders\NovusSeeder;
use Livewire\Livewire;

beforeEach(function () {
    $this->seed(NovusSeeder::class);
});

test('article page renders for valid slug', function () {
    $this->get('/articles/real-time-ticket-scanning')->assertSuccessful();
});

test('article page displays article content', function () {
    Livewire::test(ArticlePage::class, ['slug' => 'real-time-ticket-scanning'])
        ->assertSee('How We Built Real-Time Ticket Scanning')
        ->assertSee('Abubakar Sheriff');
});

test('article page shows table of contents', function () {
    Livewire::test(ArticlePage::class, ['slug' => 'real-time-ticket-scanning'])
        ->assertSee('The Problem')
        ->assertSee('Architecture Overview');
});

test('article page shows related posts', function () {
    Livewire::test(ArticlePage::class, ['slug' => 'real-time-ticket-scanning'])
        ->assertSee('Related articles');
});

test('article page returns 404 for unknown slug', function () {
    $this->get('/articles/nonexistent-article')->assertNotFound();
});
