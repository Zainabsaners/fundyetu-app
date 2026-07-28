<?php

namespace App\Jobs;

use App\Models\Donation;
use App\Models\PaymentGatewayLog;
use App\Models\Transaction;
use Illuminate\Support\Facades\Log;

class ProcessMpesaCallback
{
    public function __construct(
        public array $callbackData
    ) {}

    public function handle(): void
    {
        Log::info('M-Pesa callback received', ['data' => $this->callbackData]);

        $body = $this->callbackData['Body'] ?? [];
        $stkCallback = $body['stkCallback'] ?? [];
        $resultCode = $stkCallback['ResultCode'] ?? 1;
        $merchantRequestId = $stkCallback['MerchantRequestID'] ?? null;
        $checkoutRequestId = $stkCallback['CheckoutRequestID'] ?? null;

        Log::info('M-Pesa callback parsed', ['resultCode' => $resultCode, 'merchantRequestId' => $merchantRequestId, 'checkoutRequestId' => $checkoutRequestId]);

        if ($resultCode !== 0) {
            Log::warning('M-Pesa callback failed', ['resultCode' => $resultCode, 'resultDesc' => $stkCallback['ResultDesc'] ?? '']);
            PaymentGatewayLog::where('transaction_id', $merchantRequestId)
                ->update(['status' => 'failed']);
            return;
        }

        $metadata = $stkCallback['CallbackMetadata']['Item'] ?? [];
        $amount = 0;
        $phone = '';
        $transactionId = '';

        foreach ($metadata as $item) {
            match ($item['Name']) {
                'Amount' => $amount = $item['Value'] ?? 0,
                'PhoneNumber' => $phone = $item['Value'] ?? '',
                'MpesaReceiptNumber' => $transactionId = $item['Value'] ?? '',
                default => null,
            };
        }

        Log::info('M-Pesa callback metadata', ['amount' => $amount, 'phone' => $phone, 'transactionId' => $transactionId]);

        PaymentGatewayLog::where('transaction_id', $merchantRequestId)
            ->update([
                'status' => 'completed',
                'transaction_id' => $transactionId,
            ]);

        $donation = Donation::where('payment_ref', $checkoutRequestId)->first();

        if (! $donation) {
            Log::warning('M-Pesa callback: donation not found', ['checkoutRequestId' => $checkoutRequestId]);
            return;
        }

        Log::info('M-Pesa callback: donation found', ['donation_id' => $donation->id, 'status' => $donation->status]);

        $campaign = $donation->campaign;

        $donation->update([
            'amount' => (float) $amount,
            'fee' => 0,
            'net_amount' => (float) $amount,
            'payment_ref' => $transactionId,
            'status' => 'completed',
        ]);

        $campaign->increment('raised_amount', (float) $amount);
        $campaign->increment('balance', (float) $amount);

        Transaction::create([
            'campaign_id' => $campaign->id,
            'type' => 'donation',
            'amount' => (float) $amount,
            'balance_before' => $campaign->balance - (float) $amount,
            'balance_after' => $campaign->balance,
            'description' => "M-Pesa donation from {$phone}",
            'transactable_id' => $donation->id,
            'transactable_type' => Donation::class,
        ]);

        (new SendDonationReceipt($donation))->handle();
    }
}
