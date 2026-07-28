<?php

namespace App\Services;

use App\Models\SmsLog;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class TextSMSService
{
    protected string $apiKey;
    protected string $partnerId;
    protected string $shortcode;
    protected string $endpoint;

    public function __construct()
    {
        $this->apiKey = config('textsms.api_key');
        $this->partnerId = config('textsms.partner_id');
        $this->shortcode = config('textsms.shortcode', 'Support Sphere');
        $this->endpoint = config('textsms.endpoint', 'https://sms.textsms.co.ke/api/services/sendsms/');
    }

    public function send(string $to, string $message, ?int $campaignId = null, ?int $userId = null, bool $isBillable = false): ?SmsLog
    {
        $mobile = preg_replace('/^0/', '254', $to);

        $fields = [
            'apikey'    => $this->apiKey,
            'partnerID' => $this->partnerId,
            'message'   => $message,
            'shortcode' => $this->shortcode,
            'mobile'    => $mobile,
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->endpoint);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        $result = curl_exec($ch);
        curl_close($ch);

        $response = json_decode($result, true);
        Log::info('TextSMS API response', ['response' => $response]);

        $code = $response['responses'][0]['response-code'] ?? 0;
        $status = $code === 200 ? 'sent' : 'failed';

        $log = SmsLog::create([
            'user_id' => $userId,
            'campaign_id' => $campaignId,
            'recipient' => $to,
            'message' => $message,
            'cost' => (float) (Setting::get('sms_cost_per_credit', 5)),
            'provider_ref' => $response['responses'][0]['messageid'] ?? null,
            'status' => $status,
            'is_billable' => $isBillable,
        ]);

        return $status === 'sent' ? $log : null;
    }

    public function sendByUser(User $user, string $to, string $message, ?int $campaignId = null): ?SmsLog
    {
        $log = $this->send(
            to: $to,
            message: $message,
            campaignId: $campaignId,
            userId: $user->id,
            isBillable: $user->sms_credits <= 0,
        );

        if ($log) {
            $user->decrement('sms_credits');
        }

        return $log;
    }
}
