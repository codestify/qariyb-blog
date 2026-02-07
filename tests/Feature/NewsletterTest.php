<?php

use App\Livewire\NewsletterForm;
use App\Models\Subscriber;
use Livewire\Livewire;

test('newsletter form renders', function () {
    Livewire::test(NewsletterForm::class)
        ->assertSee('Stay in the loop')
        ->assertSee('Subscribe');
});

test('newsletter form validates email is required', function () {
    Livewire::test(NewsletterForm::class)
        ->set('email', '')
        ->call('subscribe')
        ->assertHasErrors(['email' => 'required']);
});

test('newsletter form validates email format', function () {
    Livewire::test(NewsletterForm::class)
        ->set('email', 'not-an-email')
        ->call('subscribe')
        ->assertHasErrors(['email' => 'email']);
});

test('newsletter form subscribes a valid email', function () {
    Livewire::test(NewsletterForm::class)
        ->set('email', 'test@example.com')
        ->call('subscribe')
        ->assertHasNoErrors()
        ->assertSet('isSubscribed', true);

    $this->assertDatabaseHas('subscribers', [
        'email' => 'test@example.com',
    ]);
});

test('newsletter form prevents duplicate subscriptions', function () {
    Subscriber::factory()->create(['email' => 'existing@example.com']);

    Livewire::test(NewsletterForm::class)
        ->set('email', 'existing@example.com')
        ->call('subscribe')
        ->assertHasErrors(['email']);
});

test('newsletter form shows success message after subscribing', function () {
    Livewire::test(NewsletterForm::class)
        ->set('email', 'success@example.com')
        ->call('subscribe')
        ->assertSee("You're subscribed!", escape: false);
});
