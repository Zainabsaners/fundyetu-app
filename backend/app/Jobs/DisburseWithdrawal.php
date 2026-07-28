<?php

namespace App\Jobs;

use App\Models\Withdrawal;
use App\Services\Payment\MpesaGateway;
use App\Services\WithdrawalService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class DisburseWithdrawal implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Withdrawal $withdrawal
    ) {}

    public function handle(MpesaGateway $mpesa, WithdrawalService $withdrawalService): void
    {
        $result = $mpesa->disbursement(
            $this->withdrawal->destination_ref,
            $this->withdrawal->net_amount
        );

        if (($result['ResponseCode'] ?? '1') === '0') {
            $withdrawalService->disburse($this->withdrawal);
        }
    }
}
