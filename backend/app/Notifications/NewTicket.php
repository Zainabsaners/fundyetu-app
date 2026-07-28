<?php

namespace App\Notifications;

use App\Models\SupportTicket;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewTicket extends Notification
{
    use Queueable;

    public function __construct(
        public SupportTicket $ticket
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject("New Support Ticket #{$this->ticket->id} - Support Sphere")
            ->view('emails.new-ticket', [
                'user' => $this->ticket->user,
                'ticket' => $this->ticket,
                'url' => route('admin.tickets.show', $this->ticket),
            ]);
    }

    public function toArray(object $notifiable): array
    {
        return [
            'message' => "New ticket #{$this->ticket->id}: {$this->ticket->subject}",
            'ticket_id' => $this->ticket->id,
        ];
    }
}
