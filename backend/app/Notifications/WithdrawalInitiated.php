<?php

namespace App\Notifications;

use App\Models\Withdrawal;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class WithdrawalInitiated extends Notification
{
    use Queueable;

    public function __construct(public Withdrawal $withdrawal) {}

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $campaign = $this->withdrawal->campaign;

        return (new MailMessage)
            ->subject('Withdrawal Requested: ' . $campaign->title . ' - Support Sphere')
            ->view('emails.withdrawal-initiated', [
                'user' => $notifiable,
                'withdrawal' => $this->withdrawal,
                'campaign' => $campaign,
                'url' => route('campaigns.withdrawals', $campaign),
            ]);
    }

    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'Withdrawal Requested',
            'message' => 'KES ' . number_format($this->withdrawal->amount, 0) . ' withdrawal requested from "' . $this->withdrawal->campaign->title . '".',
            'icon' => 'cash',
            'color' => 'yellow',
            'withdrawal_id' => $this->withdrawal->id,
            'campaign_id' => $this->withdrawal->campaign_id,
        ];
    }
}
