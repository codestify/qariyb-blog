<?php

use Database\Seeders\NovusSeeder;

beforeEach(function () {
    $this->seed(NovusSeeder::class);
});

test('home page shows coming soon when enabled', function () {
    config(['app.coming_soon' => true]);

    $this->get('/')
        ->assertSuccessful()
        ->assertSee('Something great is brewing.');
});

test('article page shows coming soon when enabled', function () {
    config(['app.coming_soon' => true]);

    $this->get('/articles/any-slug')
        ->assertSuccessful()
        ->assertSee('Something great is brewing.');
});

test('home page renders normally when coming soon is disabled', function () {
    config(['app.coming_soon' => false]);

    $this->get('/')
        ->assertSuccessful()
        ->assertDontSee('Something great is brewing.');
});

test('novus login is accessible when coming soon is enabled', function () {
    config(['app.coming_soon' => true]);

    $this->get('/novus/login')
        ->assertSuccessful();
});
