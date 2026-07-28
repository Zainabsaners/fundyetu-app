<?php

namespace App\Services;

use App\Models\SmsLog;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SMSService
{
    protected string $username;
    protected string $apiKey;
    protected string $from;

    public function __construct()
    {
        $this->username = config('services.africastalking.username');
        $this->apiKey = config('services.africastalking.api_key');
        $this->from = config('services.africastalking.from', 'Support Sphere');
    }

    public function send(string $to, string $message, ?int $campaignId = null, ?int $userId = null, bool $isBillable = false): ?SmsLog
    {
        $response = Http::withHeaders([
            'apiKey' => $this->apiKey,
            'Content-Type' => 'application/x-www-form-urlencoded',
            'Accept' => 'application/json',
        ])->post('https://api.africastalking.com/version1/messaging', [
            'username' => $this->username,
            'to' => $to,
            'message' => $message,
            'from' => $this->from,
        ]);

        $responseData = $response->json();
        $status = ($responseData['SMSMessageData']['Recipients'][0]['status'] ?? 'Failed') === 'Success' ? 'sent' : 'failed';

        $log = SmsLog::create([
            'user_id' => $userId,
            'campaign_id' => $campaignId,
            'recipient' => $to,
            'message' => $message,
            'cost' => $responseData['SMSMessageData']['Recipients'][0]['cost'] ?? 0,
            'provider_ref' => $responseData['SMSMessageData']['Recipients'][0]['messageId'] ?? null,
            'status' => $status,
            'is_billable' => $isBillable,
        ]);

        return $status === 'sent' ? $log : null;
    }

    public function sendByUser(\App\Models\User $user, string $to, string $message, ?int $campaignId = null): ?SmsLog
    {
        $hasCredits = $user->sms_credits > 0;

        if ($hasCredits) {
            $user->decrement('sms_credits');
        }

        return $this->send(
            to: $to,
            message: $message,
            campaignId: $campaignId,
            userId: $user->id,
            isBillable: !$hasCredits,
        );
    }

    public function sendBulk(array $recipients, string $message, ?int $campaignId = null): array
    {
        $results = [];

        foreach ($recipients as $recipient) {
            $results[$recipient] = $this->send($recipient, $message, $campaignId);
        }

        return $results;
    }
}
