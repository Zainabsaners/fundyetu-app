<?php

namespace App\Notifications;

use App\Models\SupportTicket;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TicketReplied extends Notification
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
            ->subject("Your Ticket #{$this->ticket->id} Has Been Replied - Support Sphere")
            ->view('emails.ticket-replied', [
                'user' => $notifiable,
                'ticket' => $this->ticket,
                'url' => route('tickets.show', $this->ticket),
            ]);
    }

    public function toArray(object $notifiable): array
    {
        return [
            'message' => "Your support ticket \"{$this->ticket->subject}\" has been replied to.",
            'ticket_id' => $this->ticket->id,
        ];
    }
}
