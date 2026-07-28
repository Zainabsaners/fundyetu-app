<?php

namespace App\Notifications;

use App\Models\Campaign;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class CampaignPendingVerification extends Notification
{
    use Queueable;

    public function __construct(public Campaign $campaign) {}

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('New Campaign Pending Verification - Support Sphere')
            ->view('emails.campaign-pending-verification', [
                'campaign' => $this->campaign,
                'url' => route('admin.campaigns.index', ['status' => 'pending_verification']),
            ]);
    }

    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'New Campaign Pending Verification',
            'message' => "\"{$this->campaign->title}\" has been submitted for verification.",
            'icon' => 'clipboard',
            'color' => 'yellow',
            'campaign_id' => $this->campaign->id,
        ];
    }
}
