<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class UserRejected extends Notification
{
    use Queueable;

    public function __construct(
        public User $user,
        public ?string $reason = null,
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('KYC Verification Update - Support Sphere')
            ->view('emails.user-rejected', [
                'user' => $notifiable,
                'reason' => $this->reason,
            ]);
    }

    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'KYC Rejected',
            'message' => 'Your KYC documents were not approved.' . ($this->reason ? ' Reason: ' . $this->reason : ''),
            'icon' => 'x',
            'color' => 'red',
        ];
    }
}
