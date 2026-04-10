<?php

namespace App\Mail;

use App\Models\Invitation;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class MemberInvitationMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Invitation $invitation) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'You\'ve been invited to the HALI Access Partner Portal',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.invitation',
            with: [
                'acceptUrl'    => route('invitation.show', $this->invitation->token),
                'loginUrl'     => route('login'),
                'expiresAt'    => $this->invitation->expires_at->format('F j, Y'),
                'organization' => $this->invitation->organization,
                'role'         => $this->invitation->role,
                'email'        => $this->invitation->email,
            ]
        );
    }
}
