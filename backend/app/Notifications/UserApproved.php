<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class UserApproved extends Notification
{
    use Queueable;

    public function __construct(public User $user) {}

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Your Support Sphere Account Has Been Activated')
            ->view('emails.user-approved', [
                'user' => $notifiable,
                'url' => route('dashboard'),
            ]);
    }

    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'Account Approved',
            'message' => 'Your account has been approved. You can now start fundraising!',
            'icon' => 'check',
            'color' => 'green',
        ];
    }
}
