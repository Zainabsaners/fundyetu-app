<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class UserDeactivated extends Notification
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
            ->subject('Your Support Sphere Account Has Been Deactivated')
            ->view('emails.user-deactivated', [
                'user' => $notifiable,
                'reason' => $this->reason,
            ]);
    }

    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'Account Deactivated',
            'message' => 'Your account has been deactivated.' . ($this->reason ? ' Reason: ' . $this->reason : ''),
            'icon' => 'x',
            'color' => 'red',
        ];
    }
}
