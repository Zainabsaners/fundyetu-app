<?php

namespace App\Services\Payment;

use App\Models\PaymentGatewayLog;
use Illuminate\Support\Facades\Log;

class MpesaGateway implements PaymentGatewayInterface
{
    protected string $consumerKey;
    protected string $consumerSecret;
    protected string $passkey;
    protected string $shortcode;
    protected string $environment;
    protected string $callbackUrl;

    public function __construct()
    {
        $this->consumerKey = config('services.mpesa.consumer_key');
        $this->consumerSecret = config('services.mpesa.consumer_secret');
        $this->passkey = config('services.mpesa.passkey');
        $this->shortcode = config('services.mpesa.shortcode');
        $this->environment = config('services.mpesa.environment', 'sandbox');
        $this->callbackUrl = config('services.mpesa.callback_url');
    }

    protected function baseUrl(): string
{
    return 'https://api.safaricom.co.ke';
}

    protected function getAccessToken(): string
    {
        $url = $this->baseUrl() . '/oauth/v1/generate?grant_type=client_credentials';

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json; charset=utf-8']);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_USERPWD, "$this->consumerKey:$this->consumerSecret");

        $result = curl_exec($ch);
        $error = curl_error($ch);
        $statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($error) {
            Log::error('M-Pesa auth cURL error', ['error' => $error]);
            throw new \Exception('Connection error during M-Pesa authentication: ' . $error);
        }

        Log::info('M-Pesa auth request', ['url' => $url]);
        Log::info('M-Pesa auth response', ['status' => $statusCode, 'response' => $result]);

        $data = json_decode($result, true);
        $token = trim($data['access_token'] ?? '');

        if (!$token) {
            Log::error('M-Pesa access token not found', ['response' => $data]);
            throw new \Exception('Failed to get M-Pesa access token. Response: ' . ($data['errorMessage'] ?? json_encode($data)));
        }

        return $token;
    }

    public function processPayment(float $amount, array $metadata): array
    {
        $phone = $metadata['phone'];
        $accountRef = $metadata['reference'] ?? 'Support Sphere';
        $transactionDesc = mb_substr($metadata['description'] ?? 'Donation payment', 0, 12);

        $timestamp = date('YmdHis');
        $password = base64_encode($this->shortcode . $this->passkey . $timestamp);

        $payload = [
            'BusinessShortCode' => $this->shortcode,
            'Password' => $password,
            'Timestamp' => $timestamp,
            'TransactionType' => 'CustomerPayBillOnline',
            'Amount' => (int) round($amount),
            'PartyA' => $phone,
            'PartyB' => $this->shortcode,
            'PhoneNumber' => $phone,
            'CallBackURL' => $this->callbackUrl,
            'AccountReference' => mb_substr($accountRef, 0, 12),
            'TransactionDesc' => $transactionDesc,
        ];

        $token = $this->getAccessToken();
        $stkUrl = $this->baseUrl() . '/mpesa/stkpush/v1/processrequest';

        Log::info('M-Pesa STK push details', ['url' => $stkUrl, 'payload' => $payload, 'token_len' => strlen($token), 'token_hex' => bin2hex($token)]);

        $postData = json_encode($payload, JSON_UNESCAPED_SLASHES);
        Log::info('M-Pesa STK push raw POST', ['post' => $postData]);

        $ch = curl_init($stkUrl);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $token,
        ]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);

        $response = curl_exec($ch);
        $error = curl_error($ch);
        $errno = curl_errno($ch);
        $statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($error) {
            Log::error('M-Pesa STK push cURL error', ['errno' => $errno, 'error' => $error]);
            throw new \Exception('Connection error during M-Pesa STK push: ' . $error);
        }

        Log::info('M-Pesa STK push response', ['status' => $statusCode, 'response' => $response]);

        $responseData = json_decode($response, true) ?? [];

        PaymentGatewayLog::create([
            'gateway' => 'mpesa',
            'endpoint' => 'stkpush',
            'request_payload' => json_encode($payload),
            'response_payload' => json_encode($responseData),
            'transaction_id' => $responseData['MerchantRequestID'] ?? null,
            'status' => ($responseData['ResponseCode'] ?? '1') === '0' ? 'success' : 'failed',
        ]);

        return $responseData;
    }

    protected function normalizePhone(string $phone): string
    {
        $phone = preg_replace('/[^0-9]/', '', $phone);

        if (strlen($phone) === 9) {
            $phone = '254' . $phone;
        } elseif (strlen($phone) === 10 && str_starts_with($phone, '0')) {
            $phone = '254' . substr($phone, 1);
        } elseif (strlen($phone) === 12 && str_starts_with($phone, '254')) {
            $phone = $phone;
        } elseif (strlen($phone) === 13 && str_starts_with($phone, '254')) {
            $phone = substr($phone, 0, 12);
        }

        return $phone;
    }

    public function verifyPayment(string $transactionRef): array
    {
        $token = $this->getAccessToken();

        $timestamp = date('YmdHis');
        $password = base64_encode($this->shortcode . $this->passkey . $timestamp);

        $payload = [
            'BusinessShortCode' => $this->shortcode,
            'Password' => $password,
            'Timestamp' => $timestamp,
            'CheckoutRequestID' => $transactionRef,
        ];

        $queryUrl = $this->baseUrl() . '/mpesa/stkpushquery/v1/query';

        $ch = curl_init($queryUrl);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $token,
        ]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));

        $response = curl_exec($ch);
        curl_close($ch);

        return json_decode($response, true) ?? [];
    }

    public function processRefund(string $transactionRef, float $amount): array
    {
        throw new \Exception('M-Pesa refund not implemented');
    }

    public function disbursement(string $phone, float $amount, string $remarks = 'Withdrawal'): array
    {
        $token = $this->getAccessToken();

        $phone = $this->normalizePhone($phone);

        $payload = [
            'InitiatorName' => config('services.mpesa.initiator_name'),
            'SecurityCredential' => config('services.mpesa.security_credential'),
            'CommandID' => 'BusinessPayment',
            'Amount' => (int) round($amount),
            'PartyA' => $this->shortcode,
            'PartyB' => $phone,
            'Remarks' => $remarks,
            'QueueTimeOutURL' => config('services.mpesa.timeout_url'),
            'ResultURL' => config('services.mpesa.result_url'),
        ];

        $b2cUrl = $this->baseUrl() . '/mpesa/b2c/v1/paymentrequest';

        $ch = curl_init($b2cUrl);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $token,
        ]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));

        $response = curl_exec($ch);
        $error = curl_error($ch);
        $errno = curl_errno($ch);
        curl_close($ch);

        if ($error) {
            Log::error('M-Pesa B2C cURL error', ['errno' => $errno, 'error' => $error]);
            throw new \Exception('Connection error during M-Pesa B2C disbursement: ' . $error);
        }

        $responseData = json_decode($response, true) ?? [];

        PaymentGatewayLog::create([
            'gateway' => 'mpesa',
            'endpoint' => 'b2c',
            'request_payload' => json_encode($payload),
            'response_payload' => json_encode($responseData),
            'transaction_id' => $responseData['ConversationID'] ?? null,
            'status' => ($responseData['ResponseCode'] ?? '1') === '0' ? 'success' : 'failed',
        ]);

        return $responseData;
    }
}
