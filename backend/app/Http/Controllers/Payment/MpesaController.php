<?php

namespace App\Http\Controllers\Payment;

use App\Jobs\ProcessMpesaCallback;
use App\Models\Withdrawal;
use App\Services\WithdrawalService;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Log;

class MpesaController extends Controller
{
    public function callback(Request $request)
    {
        (new ProcessMpesaCallback($request->all()))->handle();

        return response()->json(['ResultCode' => 0, 'ResultDesc' => 'Success']);
    }

    public function validate(Request $request)
    {
        return response()->json(['ResultCode' => 0, 'ResultDesc' => 'Success']);
    }

    public function b2cResult(Request $request, WithdrawalService $withdrawalService)
    {
        Log::info('M-Pesa B2C result callback', ['body' => $request->all()]);

        $result = $request->input('Result');
        if (!$result) {
            return response()->json(['ResultCode' => 1, 'ResultDesc' => 'Invalid payload']);
        }

        $conversationId = $result['ConversationID'] ?? null;
        $transactionId = $result['TransactionID'] ?? null;
        $resultCode = $result['ResultCode'] ?? 1;
        $resultDesc = $result['ResultDesc'] ?? '';

        if (!$conversationId) {
            return response()->json(['ResultCode' => 1, 'ResultDesc' => 'Missing ConversationID']);
        }

        $withdrawal = Withdrawal::where('evidence', 'LIKE', "%$conversationId%")->first();
        if (!$withdrawal) {
            Log::warning('B2C result: withdrawal not found', ['conversation_id' => $conversationId]);
            return response()->json(['ResultCode' => 0, 'ResultDesc' => 'Accepted']);
        }

        $params = [];
        if (isset($result['ResultParameters']['ResultParameter'])) {
            foreach ($result['ResultParameters']['ResultParameter'] as $param) {
                $params[$param['Key']] = $param['Value'] ?? null;
            }
        }

        $receipt = $transactionId ?: ($params['TransactionReceipt'] ?? null);
        $completedAt = $params['TransactionCompletedDateTime'] ?? null;

        $existingEvidence = json_decode($withdrawal->evidence, true) ?? [];
        $existingEvidence['transaction_id'] = $receipt;
        $existingEvidence['result_code'] = $resultCode;
        $existingEvidence['result_desc'] = $resultDesc;
        $existingEvidence['completed_at'] = $completedAt;
        $existingEvidence['callback_received_at'] = now()->toDateTimeString();

        $withdrawal->update(['evidence' => json_encode($existingEvidence)]);

        if ((int) $resultCode === 0) {
            $withdrawalService->disburse($withdrawal);

            Log::info('B2C withdrawal disbursed via callback', [
                'withdrawal_id' => $withdrawal->id,
                'transaction_id' => $receipt,
            ]);
        } else {
            Log::warning('B2C withdrawal failed', [
                'withdrawal_id' => $withdrawal->id,
                'result_code' => $resultCode,
                'result_desc' => $resultDesc,
            ]);
        }

        return response()->json(['ResultCode' => 0, 'ResultDesc' => 'Success']);
    }

    public function b2cTimeout(Request $request)
    {
        Log::warning('M-Pesa B2C timeout', ['body' => $request->all()]);

        $conversationId = $request->input('Result.ConversationID');

        if ($conversationId) {
            $withdrawal = Withdrawal::where('evidence', 'LIKE', "%$conversationId%")->first();
            if ($withdrawal) {
                $existingEvidence = json_decode($withdrawal->evidence, true) ?? [];
                $existingEvidence['timed_out'] = true;
                $existingEvidence['timeout_at'] = now()->toDateTimeString();
                $withdrawal->update(['evidence' => json_encode($existingEvidence)]);
            }
        }

        return response()->json(['ResultCode' => 0, 'ResultDesc' => 'Success']);
    }
}
