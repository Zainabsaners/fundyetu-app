<?php

namespace App\Services\Payment;

use App\Models\PaymentGatewayLog;
use Illuminate\Support\Facades\Http;

class FlutterwaveGateway implements PaymentGatewayInterface
{
    protected string $secretKey;
    protected string $environment;

    public function __construct()
    {
        $this->secretKey = config('services.flutterwave.secret_key');
        $this->environment = config('services.flutterwave.environment', 'sandbox');
    }

    protected function baseUrl(): string
    {
        return $this->environment === 'live'
            ? 'https://api.flutterwave.com/v3'
            : 'https://api.flutterwave.com/v3';
    }

    public function processPayment(float $amount, array $metadata): array
    {
        $payload = [
            'tx_ref' => $metadata['reference'] ?? uniqid('FY-'),
            'amount' => $amount,
            'currency' => $metadata['currency'] ?? 'KES',
            'redirect_url' => $metadata['redirect_url'] ?? config('app.url') . '/donations/callback',
            'customer' => [
                'email' => $metadata['email'] ?? 'donor@example.com',
                'phonenumber' => $metadata['phone'] ?? '',
                'name' => $metadata['name'] ?? 'Donor',
            ],
            'meta' => [
                'campaign_id' => $metadata['campaign_id'] ?? null,
            ],
            'customizations' => [
                'title' => $metadata['title'] ?? 'Support Sphere Donation',
                'description' => $metadata['description'] ?? 'Donation payment',
            ],
        ];

        $response = Http::withToken($this->secretKey)
            ->post($this->baseUrl() . '/payments', $payload);

        $responseData = $response->json();

        PaymentGatewayLog::create([
            'gateway' => 'flutterwave',
            'endpoint' => 'payments',
            'request_payload' => json_encode($payload),
            'response_payload' => json_encode($responseData),
            'transaction_id' => $responseData['data']['id'] ?? null,
            'status' => ($responseData['status'] ?? '') === 'success' ? 'success' : 'failed',
        ]);

        return $responseData;
    }

    public function verifyPayment(string $transactionRef): array
    {
        $response = Http::withToken($this->secretKey)
            ->get($this->baseUrl() . '/transactions/' . $transactionRef . '/verify');

        return $response->json();
    }

    public function processRefund(string $transactionRef, float $amount): array
    {
        $response = Http::withToken($this->secretKey)
            ->post($this->baseUrl() . '/transactions/' . $transactionRef . '/refund', [
                'amount' => $amount,
            ]);

        return $response->json();
    }
}
