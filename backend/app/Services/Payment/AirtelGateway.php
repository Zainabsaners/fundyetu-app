<?php

namespace App\Services\Payment;

use App\Models\PaymentGatewayLog;
use Illuminate\Support\Facades\Http;

class AirtelGateway implements PaymentGatewayInterface
{
    protected string $clientId;
    protected string $clientSecret;
    protected string $environment;

    public function __construct()
    {
        $this->clientId = config('services.airtel.client_id');
        $this->clientSecret = config('services.airtel.client_secret');
        $this->environment = config('services.airtel.environment', 'sandbox');
    }

    protected function baseUrl(): string
    {
        return $this->environment === 'live'
            ? 'https://openapi.airtel.africa'
            : 'https://openapi.airtel.africa';
    }

    protected function getAccessToken(): string
    {
        $response = Http::post($this->baseUrl() . '/auth/oauth2/token', [
            'client_id' => $this->clientId,
            'client_secret' => $this->clientSecret,
            'grant_type' => 'client_credentials',
        ]);

        return $response->json()['access_token'] ?? throw new \Exception('Failed to get Airtel token');
    }

    public function processPayment(float $amount, array $metadata): array
    {
        $token = $this->getAccessToken();

        $payload = [
            'reference' => $metadata['reference'] ?? uniqid('FY-'),
            'subscriber' => [
                'country' => 'KEN',
                'currency' => 'KES',
                'msisdn' => $metadata['phone'],
            ],
            'transaction' => [
                'amount' => (string) $amount,
                'country' => 'KEN',
                'currency' => 'KES',
                'id' => uniqid('TXN-'),
            ],
        ];

        $response = Http::withToken($token)
            ->post($this->baseUrl() . '/merchant/v1/payments/', $payload);

        $responseData = $response->json();

        PaymentGatewayLog::create([
            'gateway' => 'airtel',
            'endpoint' => 'payments',
            'request_payload' => json_encode($payload),
            'response_payload' => json_encode($responseData),
            'transaction_id' => $responseData['data']['transactionId'] ?? null,
            'status' => isset($responseData['data']['transactionId']) ? 'success' : 'failed',
        ]);

        return $responseData;
    }

    public function verifyPayment(string $transactionRef): array
    {
        $token = $this->getAccessToken();

        $response = Http::withToken($token)
            ->get($this->baseUrl() . '/standard/v1/payments/' . $transactionRef);

        return $response->json();
    }

    public function processRefund(string $transactionRef, float $amount): array
    {
        throw new \Exception('Airtel refund not implemented');
    }
}
