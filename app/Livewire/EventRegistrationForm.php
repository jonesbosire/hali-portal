<?php

namespace App\Livewire;

use App\Models\Event;
use App\Models\EventRegistration;
use Livewire\Component;

class EventRegistrationForm extends Component
{
    public Event $event;
    public ?EventRegistration $registration = null;

    public string $dietary_requirements = '';
    public string $registration_notes   = '';

    public bool $registered = false;

    public function mount(Event $event): void
    {
        $this->registration = auth()->user()
            ->eventRegistrations()
            ->where('event_id', $event->id)
            ->first();

        $this->registered = $this->registration && $this->registration->status !== 'canceled';
    }

    public function register(): void
    {
        if (!$this->event->isRegistrationOpen()) {
            $this->js("window.toast('error', 'Registration is not open for this event.')");
            return;
        }
        if ($this->event->isFull()) {
            $this->js("window.toast('error', 'This event is full.')");
            return;
        }
        if ($this->registered) {
            $this->js("window.toast('warning', 'You are already registered for this event.')");
            return;
        }

        $this->validate([
            'dietary_requirements' => ['nullable', 'string', 'max:255'],
            'registration_notes'   => ['nullable', 'string', 'max:1000'],
        ]);

        $this->registration = EventRegistration::create([
            'event_id'             => $this->event->id,
            'user_id'              => auth()->id(),
            'organization_id'      => auth()->user()->primaryOrganization()?->id,
            'status'               => 'registered',
            'dietary_requirements' => $this->dietary_requirements ?: null,
            'registration_notes'   => $this->registration_notes   ?: null,
            'registered_at'        => now(),
        ]);

        $this->registered = true;
        $this->reset('dietary_requirements', 'registration_notes');

        $title   = "You're registered!";
        $message = "See you at {$this->event->title}. Check your email for confirmation.";
        $this->js("window.toast('success', " . json_encode($message) . ", " . json_encode($title) . ")");
    }

    public function cancel(): void
    {
        if (!$this->registration) return;

        $this->registration->update([
            'status'      => 'canceled',
            'canceled_at' => now(),
        ]);

        $this->registered = false;
        $this->event->refresh();

        $this->js("window.toast('info', 'Your registration has been canceled.')");
    }

    public function render()
    {
        return view('livewire.event-registration-form');
    }
}
