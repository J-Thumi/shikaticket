<?php

namespace App\Mail;

use App\Models\Event;
use App\Models\Marketer;
use App\Models\MarketerInvitation;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class MarketerApprovedMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(
        public User $user,
        public Marketer $marketer,
        public Event $event,
        public MarketerInvitation $invitation,
        public ?string $organizerNote = null,
    ) {
        $this->afterCommit();
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Your ShikaTicket Marketer Application Has Been Approved',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.marketers.approved',
            with: [
                'dashboardUrl' => route('marketer.dashboard'),
                'referralUrl' => $this->marketer->getReferralUrl($this->event),
            ],
        );
    }
}
