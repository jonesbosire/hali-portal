<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AccountStatusChanged extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public string $newStatus) {}

    public function via(object $notifiable): array
    {
        $channels = ['database', 'mail'];
        if ($notifiable->phone && config('services.africastalking.api_key')) {
            $channels[] = 'sms';
        }
        return $channels;
    }

    // ── SMS ───────────────────────────────────────────────────────────────────

    public function toSms(object $notifiable): string
    {
        return match ($this->newStatus) {
            'active'    => "HALI Access: Your account has been activated. You now have full access to the portal.",
            'suspended' => "HALI Access: Your account has been suspended. Contact portal@haliaccess.org for assistance.",
            'pending'   => "HALI Access: Your account is under review. The Secretariat will contact you shortly.",
            default     => "HALI Access: Your account status has changed to {$this->newStatus}.",
        };
    }

    // ── In-app (database) ─────────────────────────────────────────────────────

    public function toDatabase(object $notifiable): array
    {
        return match ($this->newStatus) {
            'active' => [
                'title'       => 'Your account has been activated',
                'message'     => 'You now have full access to the HALI Access Partner Portal.',
                'icon'        => 'check',
                'type'        => 'success',
                'url'         => route('dashboard'),
                'action_text' => 'Go to dashboard',
            ],
            'suspended' => [
                'title'       => 'Your account has been suspended',
                'message'     => 'Your access to the portal has been suspended. Contact the Secretariat for assistance.',
                'icon'        => 'warning',
                'type'        => 'warning',
                'url'         => null,
                'action_text' => null,
            ],
            'pending' => [
                'title'       => 'Your account is under review',
                'message'     => 'Your account has been placed under review by the Secretariat.',
                'icon'        => 'info',
                'type'        => 'info',
                'url'         => null,
                'action_text' => null,
            ],
            default => [
                'title'   => 'Your account status has changed',
                'message' => "Your account status is now: {$this->newStatus}.",
                'icon'    => 'info',
                'type'    => 'info',
                'url'     => null,
            ],
        };
    }

    // ── Email ─────────────────────────────────────────────────────────────────

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject($this->emailSubject())
            ->view('emails.account-status', [
                'user'        => $notifiable,
                'newStatus'   => $this->newStatus,
                'dashboardUrl' => route('dashboard'),
            ]);
    }

    private function emailSubject(): string
    {
        return match ($this->newStatus) {
            'active'    => 'Your HALI Access account has been activated',
            'suspended' => 'Important: Your HALI Access account has been suspended',
            'pending'   => 'Your HALI Access account is under review',
            default     => 'Your HALI Access account status has changed',
        };
    }
}
