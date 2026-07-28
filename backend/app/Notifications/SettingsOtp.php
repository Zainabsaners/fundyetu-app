<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class SettingsOtp extends Notification
{
    use Queueable;

    public function __construct(public string $code) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Settings OTP - Support Sphere')
            ->greeting('Hello ' . $notifiable->name . ',')
            ->line('You requested a one-time password to update system settings.')
            ->line('Your OTP is: **' . $this->code . '**')
            ->line('This code expires in 5 minutes.')
            ->line('If you did not request this, please ignore this email.');
    }
}
