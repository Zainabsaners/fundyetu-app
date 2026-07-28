<?php

namespace App\Notifications;

use App\Models\Withdrawal;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class WithdrawalRejected extends Notification
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
            ->subject('Withdrawal Rejected: ' . $campaign->title . ' - Support Sphere')
            ->view('emails.withdrawal-rejected', [
                'user' => $notifiable,
                'withdrawal' => $this->withdrawal,
                'campaign' => $campaign,
                'url' => route('campaigns.withdrawals', $campaign),
            ]);
    }

    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'Withdrawal Rejected',
            'message' => 'KES ' . number_format($this->withdrawal->amount, 0) . ' withdrawal from "' . $this->withdrawal->campaign->title . '" was rejected. Reason: ' . ($this->withdrawal->rejection_reason ?? 'No reason provided.'),
            'icon' => 'x-circle',
            'color' => 'red',
            'withdrawal_id' => $this->withdrawal->id,
            'campaign_id' => $this->withdrawal->campaign_id,
        ];
    }
}
