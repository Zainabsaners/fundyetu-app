<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class KycResubmitted extends Notification
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
            ->subject('KYC Documents Re-submitted - Support Sphere')
            ->view('emails.kyc-resubmitted', [
                'user' => $this->user,
                'url' => route('admin.users.details', $this->user),
            ]);
    }

    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'KYC Resubmitted',
            'message' => "{$this->user->name} has re-submitted their KYC documents for review.",
            'icon' => 'clipboard',
            'color' => 'yellow',
            'user_id' => $this->user->id,
        ];
    }
}
