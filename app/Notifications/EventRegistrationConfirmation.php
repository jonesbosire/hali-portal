<?php

namespace App\Notifications;

use App\Models\Event;
use App\Models\EventRegistration;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class EventRegistrationConfirmation extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Event $event,
        public EventRegistration $registration,
    ) {}

    public function via(object $notifiable): array
    {
        $channels = ['database', 'mail'];
        // Add SMS if the user has a phone number and AT is configured
        if ($notifiable->phone && config('services.africastalking.api_key')) {
            $channels[] = 'sms';
        }
        return $channels;
    }

    // ── SMS ───────────────────────────────────────────────────────────────────

    public function toSms(object $notifiable): string
    {
        $date = $this->event->start_datetime->format('D j M Y');
        $time = $this->event->start_datetime->format('g:i A') . ' ' . $this->event->timezone;
        return "HALI Access: You're registered for \"{$this->event->title}\" on {$date} at {$time}. Visit the portal for details.";
    }

    // ── In-app (database) ─────────────────────────────────────────────────────

    public function toDatabase(object $notifiable): array
    {
        return [
            'title'       => "You're registered for {$this->event->title}",
            'message'     => 'Your spot is confirmed. ' . $this->event->start_datetime->format('l, F j, Y \a\t g:i A') . ' · ' . $this->event->timezone,
            'icon'        => 'event',
            'type'        => 'success',
            'url'         => route('events.show', $this->event),
            'action_text' => 'View event',
        ];
    }

    // ── Email ─────────────────────────────────────────────────────────────────

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject("Registration confirmed: {$this->event->title}")
            ->view('emails.event-registration', [
                'user'         => $notifiable,
                'event'        => $this->event,
                'registration' => $this->registration,
                'eventUrl'     => route('events.show', $this->event),
            ]);
    }
}
