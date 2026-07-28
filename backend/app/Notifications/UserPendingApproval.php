<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class UserPendingApproval extends Notification
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
            ->subject('New User Pending Approval - Support Sphere')
            ->view('emails.user-pending-approval', [
                'user' => $this->user,
                'url' => route('admin.users.details', $this->user),
            ]);
    }

    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'New User Pending Approval',
            'message' => "{$this->user->name} ({$this->user->email}) has completed verification and is awaiting admin approval.",
            'icon' => 'user',
            'color' => 'yellow',
            'user_id' => $this->user->id,
        ];
    }
}
