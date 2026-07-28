<?php

namespace App\Http\Controllers\Admin;

use App\Models\Withdrawal;
use App\Services\Payment\MpesaGateway;
use App\Services\TextSMSService;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Log;

class WithdrawalController extends Controller
{
    const OTP_SESSION_KEY = 'admin_withdrawal_otp_';

    public function __construct()
    {
        $this->middleware(['auth', 'role:admin|super_admin']);
    }

    public function index(Request $request)
    {
        $query = Withdrawal::with(['campaign', 'requester']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $withdrawals = $query->latest()->paginate(20);

        return view('admin.withdrawals.index', compact('withdrawals'));
    }

    public function sendOtp(Withdrawal $withdrawal, Request $request, TextSMSService $sms)
    {
        if ($withdrawal->status->value !== 'pending' && $withdrawal->status->value !== 'treasurer_approved') {
            return back()->withErrors(['error' => 'Withdrawal is not in a pending state.']);
        }

        $admin = auth()->user();
        if (!$admin->phone) {
            return back()->withErrors(['error' => 'You must have a phone number registered to receive OTP.']);
        }

        $otp = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        $key = self::OTP_SESSION_KEY . $withdrawal->id;
        $request->session()->put($key, $otp);
        $request->session()->put($key . '_expires', now()->addMinutes(10)->timestamp);

        Log::info('Admin withdrawal OTP generated', ['withdrawal_id' => $withdrawal->id, 'admin_id' => $admin->id, 'otp' => $otp]);

        $sms->send($admin->phone, "Your Support Sphere withdrawal approval OTP is: {$otp}. Enter it to approve withdrawal #{$withdrawal->id} of KES " . number_format($withdrawal->net_amount, 0) . ". Expires in 10 minutes.");

        return back()->with('status', 'otp-sent')->with('otp_withdrawal_id', $withdrawal->id);
    }

    public function approve(Withdrawal $withdrawal, Request $request, MpesaGateway $mpesa)
    {
        if ($withdrawal->status->value !== 'pending' && $withdrawal->status->value !== 'treasurer_approved') {
            return back()->withErrors(['error' => 'Withdrawal is not in a pending state.']);
        }

        $key = self::OTP_SESSION_KEY . $withdrawal->id;
        $cachedOtp = $request->session()->get($key);
        $expires = $request->session()->get($key . '_expires', 0);

        if (!$cachedOtp || $cachedOtp !== $request->otp) {
            return back()->withErrors(['otp' => 'Invalid verification code.']);
        }

        if (now()->timestamp > $expires) {
            $request->session()->forget($key);
            $request->session()->forget($key . '_expires');
            return back()->withErrors(['otp' => 'Verification code has expired. Request a new one.']);
        }

        $request->session()->forget($key);
        $request->session()->forget($key . '_expires');

        if ($withdrawal->destination_type !== 'mpesa') {
            return back()->withErrors(['error' => 'M-Pesa disbursement is only available for M-Pesa withdrawals.']);
        }

        $phone = $withdrawal->destination_ref;
        $amount = $withdrawal->net_amount;

        if ($amount < 10) {
            return back()->withErrors(['error' => 'Net amount is below M-Pesa minimum (KES 10). Withdrawal fee may exceed requested amount.']);
        }

        try {
            $response = $mpesa->disbursement($phone, $amount);

            Log::info('M-Pesa B2C disbursement response', [
                'withdrawal_id' => $withdrawal->id,
                'response' => $response,
            ]);

            $responseCode = $response['ResponseCode'] ?? '1';

            $evidence = json_encode([
                'conversation_id' => $response['ConversationID'] ?? null,
                'originator_conversation_id' => $response['OriginatorConversationID'] ?? null,
                'response_code' => $responseCode,
                'response_description' => $response['ResponseDescription'] ?? null,
                'disbursed_at' => now()->toDateTimeString(),
            ]);

            $withdrawal->update(['evidence' => $evidence]);

            if ($responseCode === '0') {
                return back()->with('success', 'Disbursement sent. Awaiting M-Pesa confirmation.');
            }

            $desc = $response['ResponseDescription'] ?? ($response['errorMessage'] ?? 'Unknown error');
            return back()->withErrors(['error' => "M-Pesa B2C failed: {$desc}"]);
        } catch (\Throwable $e) {
            Log::error('M-Pesa B2C disbursement error', [
                'withdrawal_id' => $withdrawal->id,
                'error' => $e->getMessage(),
            ]);

            $withdrawal->update([
                'evidence' => json_encode([
                    'error' => $e->getMessage(),
                    'attempted_at' => now()->toDateTimeString(),
                ]),
            ]);

            return back()->withErrors(['error' => 'M-Pesa disbursement failed: ' . $e->getMessage()]);
        }
    }

    public function reject(Withdrawal $withdrawal, Request $request)
    {
        $request->validate(['rejection_reason' => 'required|string|max:1000']);

        $withdrawal->update([
            'status' => 'rejected',
            'rejection_reason' => $request->rejection_reason,
            'rejected_at' => now(),
        ]);

        return back()->with('success', "Withdrawal #{$withdrawal->id} rejected.");
    }

    public function invoice(Withdrawal $withdrawal)
    {
        $withdrawal->load(['campaign.user', 'approvals.treasurer']);

        return view('admin.withdrawals.invoice', compact('withdrawal'));
    }
}
