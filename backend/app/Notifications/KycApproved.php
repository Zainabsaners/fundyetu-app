<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class KycApproved extends Notification
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
            ->subject('KYC Verified - Support Sphere')
            ->view('emails.kyc-approved', [
                'user' => $notifiable,
                'url' => route('dashboard'),
            ]);
    }

    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'KYC Approved',
            'message' => 'Your KYC has been verified. You can now submit campaigns for fundraising!',
            'icon' => 'check',
            'color' => 'green',
        ];
    }
}
