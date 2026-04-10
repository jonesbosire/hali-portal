<?php

namespace App\Mail;

use App\Models\MemberBulletin;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class MemberBulletinMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public MemberBulletin $bulletin,
        public User $recipient
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '[HALI Access] ' . $this->bulletin->title,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.bulletin',
            with: [
                'bulletin' => $this->bulletin,
                'recipient' => $this->recipient,
                'portalUrl' => route('dashboard'),
            ]
        );
    }
}
