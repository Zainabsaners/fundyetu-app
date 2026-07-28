<?php

namespace App\Services;

use App\Models\Campaign;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Withdrawal;
use App\Models\WithdrawalApproval;
use App\Notifications\WithdrawalInitiated;
use Illuminate\Support\Facades\DB;

class WithdrawalService
{
    public function __construct(
        protected FeeCalculator $feeCalculator
    ) {}

    public function initiate(Campaign $campaign, float $amount, string $destinationType, string $destinationRef = null, string $status = 'pending'): Withdrawal
    {
        $user = auth()->user();
        $platformFeePercent = (float) (\App\Models\Setting::get('platform_fee_percent', 0));
        $smsCredits = $user->sms_credits ?? 0;
        $smsCostPerCredit = (float) (\App\Models\Setting::get('sms_cost_per_credit', 5));
        $feeBreakdown = $this->feeCalculator->calculateWithdrawalFee($amount, $platformFeePercent, $smsCredits, $smsCostPerCredit);

        return DB::transaction(function () use ($campaign, $amount, $destinationType, $destinationRef, $feeBreakdown, $status) {
            $withdrawal = Withdrawal::create([
                'campaign_id' => $campaign->id,
                'requested_by' => auth()->id(),
                'amount' => $feeBreakdown['amount'],
                'fee' => $feeBreakdown['fee'],
                'platform_fee' => $feeBreakdown['platform_fee'],
                'sms_charge' => $feeBreakdown['sms_charge'],
                'net_amount' => $feeBreakdown['net_amount'],
                'destination_type' => $destinationType,
                'destination_ref' => $destinationRef,
                'status' => $status,
            ]);

            Transaction::create([
                'campaign_id' => $campaign->id,
                'type' => 'withdrawal',
                'amount' => -$amount,
                'balance_before' => $campaign->balance,
                'balance_after' => $campaign->balance,
                'description' => "Withdrawal initiated (#{$withdrawal->id})",
                'transactable_id' => $withdrawal->id,
                'transactable_type' => Withdrawal::class,
            ]);

            $campaign->user->notify(new WithdrawalInitiated($withdrawal));

            $admins = User::role(['admin', 'super_admin'])->get();
            foreach ($admins as $admin) {
                $admin->notify(new WithdrawalInitiated($withdrawal));
            }

            return $withdrawal;
        });
    }

    public function approve(Withdrawal $withdrawal, int $treasurerId, string $notes = null): WithdrawalApproval
    {
        return DB::transaction(function () use ($withdrawal, $treasurerId, $notes) {
            $approval = WithdrawalApproval::create([
                'withdrawal_id' => $withdrawal->id,
                'treasurer_id' => $treasurerId,
                'notes' => $notes,
                'approved_at' => now(),
            ]);

            $requiredApprovals = min(2, $withdrawal->campaign->treasurers()->count());
            $approvalsCount = $withdrawal->approvals()->count();

            if ($approvalsCount >= $requiredApprovals) {
                $withdrawal->update(['status' => 'treasurer_approved']);
            }

            return $approval;
        });
    }

    public function disburse(Withdrawal $withdrawal): void
    {
        DB::transaction(function () use ($withdrawal) {
            $campaign = $withdrawal->campaign;

            $campaign->decrement('balance', $withdrawal->amount);

            $transaction = Transaction::where('transactable_id', $withdrawal->id)
                ->where('transactable_type', Withdrawal::class)
                ->first();

            if ($transaction) {
                $transaction->update([
                    'balance_after' => $campaign->balance,
                ]);
            }

            if ($withdrawal->sms_charge > 0) {
                $smsCostPerCredit = (float) (\App\Models\Setting::get('sms_cost_per_credit', 5));
                $creditsCovered = $smsCostPerCredit > 0 ? (int) round($withdrawal->sms_charge / $smsCostPerCredit) : 0;
                $campaign->user()->update([
                    'sms_credits' => ($campaign->user->sms_credits ?? 0) + $creditsCovered,
                ]);
            }

            $withdrawal->update([
                'status' => 'disbursed',
                'disbursed_at' => now(),
            ]);
        });
    }
}
