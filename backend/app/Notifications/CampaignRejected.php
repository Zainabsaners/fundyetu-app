<?php

namespace App\Notifications;

use App\Models\Campaign;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class CampaignRejected extends Notification
{
    use Queueable;

    public function __construct(
        public Campaign $campaign,
        public string $reason,
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Campaign Update: "' . $this->campaign->title . '" - Support Sphere')
            ->view('emails.campaign-rejected', [
                'user' => $this->campaign->user,
                'campaign' => $this->campaign,
                'reason' => $this->reason,
                'url' => route('campaigns.edit', $this->campaign),
            ]);
    }

    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'Campaign Rejected',
            'message' => '"' . $this->campaign->title . '" was rejected. Reason: ' . $this->reason,
            'icon' => 'x',
            'color' => 'red',
            'campaign_id' => $this->campaign->id,
        ];
    }
}
