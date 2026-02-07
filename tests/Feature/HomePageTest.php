<?php

use App\Livewire\HomePage;
use Database\Seeders\NovusSeeder;
use Livewire\Livewire;

beforeEach(function () {
    $this->seed(NovusSeeder::class);
});

test('home page renders successfully', function () {
    $this->get('/')->assertSuccessful();
});

test('home page contains hero section', function () {
    Livewire::test(HomePage::class)
        ->assertSee('Insights & Stories', escape: false);
});

test('home page displays featured post', function () {
    Livewire::test(HomePage::class)
        ->assertSee('How We Built Real-Time Ticket Scanning');
});

test('home page displays post filter', function () {
    Livewire::test(HomePage::class)
        ->assertSeeLivewire('post-filter');
});

test('home page displays newsletter form', function () {
    Livewire::test(HomePage::class)
        ->assertSeeLivewire('newsletter-form');
});
