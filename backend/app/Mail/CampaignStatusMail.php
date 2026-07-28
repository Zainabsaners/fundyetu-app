<?php

namespace App\Mail;

use App\Models\Campaign;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CampaignStatusMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Campaign $campaign,
        public string $type,
    ) {}

    public function envelope(): Envelope
    {
        $subject = $this->type === 'approved'
            ? 'Your campaign has been approved!'
            : 'Your campaign has been reviewed';

        return new Envelope(
            subject: $subject,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.campaign-status',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}