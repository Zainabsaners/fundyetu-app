<?php

namespace App\Jobs;

use App\Models\Donation;
use App\Services\TextSMSService;
use Illuminate\Support\Facades\Mail;

class SendDonationReceipt
{

    public function __construct(
        public Donation $donation
    ) {}

    public function handle(): void
    {
        $sms = app(TextSMSService::class);
        $campaign = $this->donation->campaign;
        $amount = number_format($this->donation->amount, 2);

        $donorMessage = "Thank you for your donation of KES {$amount} to \"{$campaign->title}\". Your support is greatly appreciated. - Support Sphere";

        if ($this->donation->donor_phone) {
            $sms->sendByUser($campaign->user, $this->donation->donor_phone, $donorMessage, $campaign->id);
        }

        if ($this->donation->donor_email) {
            try {
                Mail::raw($donorMessage, function ($mail) {
                    $mail->to($this->donation->donor_email)
                        ->subject('Donation Receipt - Support Sphere');
                });
            } catch (\Throwable $e) {
                //
            }
        }

        $msg = "New donation of KES {$amount} received for \"{$campaign->title}\" from " . ($this->donation->donor_name ?? 'Anonymous') . ". - Support Sphere";

        if ($campaign->is_treasurer_controlled && $campaign->treasurers()->exists()) {
            $notifyUser = $campaign->treasurers()->first()->user;
            $notifyPhone = $notifyUser->phone;
            if ($notifyPhone) {
                $sms->sendByUser($notifyUser, $notifyPhone, $msg, $campaign->id);
            }
        } elseif ($campaign->is_treasurer_controlled && $campaign->treasurer_phone) {
            $sms->sendByUser($campaign->user, $campaign->treasurer_phone, $msg, $campaign->id);
        } else {
            $notifyPhone = $campaign->user->phone;
            if ($notifyPhone) {
                $sms->sendByUser($campaign->user, $notifyPhone, $msg, $campaign->id);
            }
        }
    }
}
