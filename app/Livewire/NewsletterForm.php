<?php

namespace App\Livewire;

use App\Models\Subscriber;
use Livewire\Component;

class NewsletterForm extends Component
{
    public string $email = '';

    public bool $isSubscribed = false;

    public function subscribe(): void
    {
        $this->validate([
            'email' => ['required', 'email', 'unique:subscribers,email'],
        ], [
            'email.unique' => 'This email is already subscribed.',
        ]);

        Subscriber::create([
            'email' => $this->email,
            'subscribed_at' => now(),
        ]);

        $this->isSubscribed = true;
        $this->email = '';
    }

    public function render()
    {
        return view('livewire.newsletter-form');
    }
}
