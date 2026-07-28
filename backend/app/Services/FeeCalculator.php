<?php

namespace App\Services;

use App\Models\Campaign;
use App\Models\Setting;

class FeeCalculator
{
    public function calculateForDonation(float $amount, Campaign $campaign, string $paymentMethod): array
    {
        $platformFeePercent = $campaign->platform_fee_percent;

        $gatewayFeePercent = match ($paymentMethod) {
            'mpesa' => 1.0,
            'airtel' => 1.0,
            'card' => 3.5,
            'paypal' => 4.4,
            default => 2.0,
        };

        $platformFee = round($amount * ($platformFeePercent / 100), 2);
        $gatewayFee = round($amount * ($gatewayFeePercent / 100), 2);
        $totalFee = round($platformFee + $gatewayFee, 2);
        $netAmount = round($amount - $totalFee, 2);

        return [
            'amount' => $amount,
            'platform_fee' => $platformFee,
            'gateway_fee' => $gatewayFee,
            'total_fee' => $totalFee,
            'net_amount' => $netAmount,
        ];
    }

    public function calculateWithdrawalFee(float $amount, float $platformFeePercent = 0, int $smsCredits = 0, float $smsCostPerCredit = 5): array
    {
        $withdrawalFee = (float) (Setting::get('withdrawal_fee', 30));
        $platformFee = round($amount * ($platformFeePercent / 100), 2);
        $smsCharge = $smsCredits < 0 ? round(abs($smsCredits) * $smsCostPerCredit, 2) : 0;
        $totalFee = $withdrawalFee + $platformFee + $smsCharge;

        return [
            'amount' => $amount,
            'fee' => $withdrawalFee,
            'platform_fee' => $platformFee,
            'sms_charge' => $smsCharge,
            'net_amount' => round($amount - $totalFee, 2),
        ];
    }
}
