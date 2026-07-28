<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AccountDeleted extends Notification
{
    use Queueable;

    public function __construct(
        public User $user,
        public ?string $reason = null,
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Your Support Sphere Account Has Been Deleted')
            ->view('emails.account-deleted', [
                'user' => $notifiable,
                'reason' => $this->reason,
            ]);
    }
}
