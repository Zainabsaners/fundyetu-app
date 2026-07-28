<?php

namespace App\Http\Controllers;

use App\Enums\CampaignStatus;
use App\Models\Campaign;
use App\Models\Setting;
use App\Models\Withdrawal;
use App\Services\TextSMSService;
use App\Services\WithdrawalService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WithdrawalController extends Controller
{
    const OTP_SESSION_KEY = 'withdrawal_otp_';

    public function overview()
    {
        $user = auth()->user();

        $campaignIds = $user->campaigns()->pluck('id');

        $campaigns = $user->campaigns()
            ->whereIn('status', [CampaignStatus::Active, CampaignStatus::Completed, CampaignStatus::Closed])
            ->withCount(['withdrawals', 'donations as completed_donations_count' => function ($q) {
                $q->where('status', 'completed');
            }])
            ->withSum(['withdrawals as approved_withdrawals_sum' => function ($q) {
                $q->whereIn('status', ['disbursed', 'admin_approved']);
            }], 'amount')
            ->latest()
            ->get()
            ->reject(fn ($c) => ($c->balance ?? 0) <= 0);

        $allWithdrawals = Withdrawal::whereIn('campaign_id', $campaignIds)
            ->with('campaign')
            ->latest()
            ->paginate(20);

        return view('campaigns.withdraw-funds', compact('campaigns', 'allWithdrawals'));
    }

    public function index(Campaign $campaign)
    {
        $withdrawals = $campaign->withdrawals()->latest()->get();
        $settings = Setting::pluck('value', 'key')->all();
        $hasPendingWithdrawal = $campaign->withdrawals()->whereIn('status', ['pending', 'treasurer_approved', 'admin_approved'])->exists();

        return view('campaigns.withdrawals', compact('campaign', 'withdrawals', 'settings', 'hasPendingWithdrawal'));
    }

    public function store(Request $request, Campaign $campaign, TextSMSService $sms)
    {
        abort_unless((int) $campaign->user_id === (int) auth()->id(), 403);

        if ($campaign->withdrawals()->whereIn('status', ['pending', 'treasurer_approved', 'admin_approved'])->exists()) {
            return back()->withErrors(['pending' => 'You already have a pending withdrawal request for this campaign. Please wait for it to be processed before requesting another.']);
        }

        $validated = $request->validate([
            'amount' => 'required|numeric|min:10|max:' . $campaign->balance,
            'destination_type' => 'required|in:mpesa,bank',
            'destination_ref' => 'required|string',
        ]);

        $otp = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        $key = self::OTP_SESSION_KEY . $campaign->id;
        $request->session()->put($key, $otp);
        $request->session()->put($key . '_expires', now()->addMinutes(10)->timestamp);
        $request->session()->put($key . '_data', $validated);

        Log::info('Withdrawal OTP generated', ['campaign_id' => $campaign->id, 'otp' => $otp]);

        $sms->sendByUser($campaign->user, $campaign->user->phone, "Your Support Sphere withdrawal OTP is: {$otp}. Enter it to confirm withdrawal of KES " . number_format($validated['amount'], 0) . ". Expires in 10 minutes.");

        return redirect()->route('campaigns.withdrawals.verify-otp', $campaign);
    }

    public function showVerifyOtp(Campaign $campaign, Request $request)
    {
        abort_unless((int) $campaign->user_id === (int) auth()->id(), 403);

        $key = self::OTP_SESSION_KEY . $campaign->id;
        if (!$request->session()->has($key)) {
            return redirect()->route('campaigns.withdrawals', $campaign)
                ->withErrors(['otp' => 'No pending withdrawal request. Please start again.']);
        }

        return view('campaigns.verify-withdrawal-otp', compact('campaign'));
    }

    public function verifyOtp(Request $request, Campaign $campaign, TextSMSService $sms, WithdrawalService $withdrawalService)
    {
        abort_unless((int) $campaign->user_id === (int) auth()->id(), 403);

        $request->validate([
            'code' => ['required', 'string', 'size:6'],
        ]);

        $key = self::OTP_SESSION_KEY . $campaign->id;
        $cachedOtp = $request->session()->get($key);
        $expires = $request->session()->get($key . '_expires', 0);
        $data = $request->session()->get($key . '_data');

        if (!$cachedOtp || $cachedOtp !== $request->code) {
            return back()->withErrors(['code' => 'Invalid or expired verification code.']);
        }

        if (now()->timestamp > $expires) {
            $request->session()->forget($key);
            $request->session()->forget($key . '_expires');
            $request->session()->forget($key . '_data');
            return back()->withErrors(['code' => 'Verification code has expired. Please start again.']);
        }

        $request->session()->forget($key);
        $request->session()->forget($key . '_expires');

        $treasurer = $campaign->is_treasurer_controlled && $campaign->treasurers()->exists()
            ? $campaign->treasurers()->first()->user
            : null;

        if ($treasurer) {
            $treasurerOtp = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);
            $tKey = $key . '_treasurer';
            $request->session()->put($tKey, $treasurerOtp);
            $request->session()->put($tKey . '_expires', now()->addMinutes(10)->timestamp);
            $request->session()->put($tKey . '_data', $data);

            Log::info('Withdrawal treasurer OTP generated', ['campaign_id' => $campaign->id, 'otp' => $treasurerOtp]);

            $sms->sendByUser($campaign->user, $treasurer->phone, "Support Sphere withdrawal requires your approval. OTP: {$treasurerOtp}. Amount: KES " . number_format($data['amount'], 0) . ". Expires in 10 minutes.");

            return redirect()->route('campaigns.withdrawals.treasurer-otp', $campaign);
        }

        $request->session()->forget($key . '_data');

        $withdrawal = $withdrawalService->initiate(
            $campaign,
            $data['amount'],
            $data['destination_type'],
            $data['destination_ref']
        );

        Log::info('Withdrawal confirmed via OTP', ['withdrawal_id' => $withdrawal->id, 'campaign_id' => $campaign->id]);

        return redirect()->route('campaigns.withdrawals', $campaign)
            ->with('success', "Withdrawal #{$withdrawal->id} initiated. Awaiting admin approval.");
    }

    public function showTreasurerOtp(Campaign $campaign, Request $request)
    {
        abort_unless((int) $campaign->user_id === (int) auth()->id(), 403);

        $key = self::OTP_SESSION_KEY . $campaign->id . '_treasurer';
        if (!$request->session()->has($key)) {
            return redirect()->route('campaigns.withdrawals', $campaign)
                ->withErrors(['otp' => 'No pending treasurer approval. Please start again.']);
        }

        $treasurer = $campaign->treasurers()->first()->user;

        return view('campaigns.verify-withdrawal-otp', [
            'campaign' => $campaign,
            'treasurer' => $treasurer,
        ]);
    }

    public function verifyTreasurerOtp(Request $request, Campaign $campaign, WithdrawalService $withdrawalService)
    {
        abort_unless((int) $campaign->user_id === (int) auth()->id(), 403);

        $request->validate([
            'code' => ['required', 'string', 'size:6'],
        ]);

        $key = self::OTP_SESSION_KEY . $campaign->id . '_treasurer';
        $cachedOtp = $request->session()->get($key);
        $expires = $request->session()->get($key . '_expires', 0);
        $data = $request->session()->get($key . '_data');

        if (!$cachedOtp || $cachedOtp !== $request->code) {
            return back()->withErrors(['code' => 'Invalid or expired verification code.']);
        }

        if (now()->timestamp > $expires) {
            $request->session()->forget($key);
            $request->session()->forget($key . '_expires');
            $request->session()->forget($key . '_data');
            return back()->withErrors(['code' => 'Verification code has expired. Please start again.']);
        }

        $request->session()->forget($key);
        $request->session()->forget($key . '_expires');
        $request->session()->forget($key . '_data');

        $withdrawal = $withdrawalService->initiate(
            $campaign,
            $data['amount'],
            $data['destination_type'],
            $data['destination_ref'],
            'treasurer_approved'
        );

        Log::info('Withdrawal confirmed via treasurer OTP', ['withdrawal_id' => $withdrawal->id, 'campaign_id' => $campaign->id]);

        return redirect()->route('campaigns.withdrawals', $campaign)
            ->with('success', "Withdrawal #{$withdrawal->id} approved by both parties. Awaiting admin disbursement.");
    }

    public function invoice(Campaign $campaign, Withdrawal $withdrawal)
    {
        abort_unless((int) $withdrawal->campaign_id === (int) $campaign->id, 404);
        abort_unless($withdrawal->status->value === 'disbursed', 404);

        $withdrawal->load(['campaign.user', 'approvals.treasurer']);

        return view('admin.withdrawals.invoice', compact('withdrawal'));
    }

    public function resendOtp(Campaign $campaign, Request $request, TextSMSService $sms)
    {
        abort_unless((int) $campaign->user_id === (int) auth()->id(), 403);

        $treasurerKey = self::OTP_SESSION_KEY . $campaign->id . '_treasurer';
        $ownerKey = self::OTP_SESSION_KEY . $campaign->id;

        if ($request->session()->has($treasurerKey . '_data')) {
            $otp = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);
            $request->session()->put($treasurerKey, $otp);
            $request->session()->put($treasurerKey . '_expires', now()->addMinutes(10)->timestamp);

            Log::info('Withdrawal treasurer OTP resent', ['campaign_id' => $campaign->id, 'otp' => $otp]);

            $treasurer = $campaign->treasurers()->first()->user;
            $sms->sendByUser($campaign->user, $treasurer->phone, "Your new Support Sphere withdrawal approval OTP is: {$otp}. Expires in 10 minutes.");

            return back()->with('status', 'otp-resent');
        }

        if (!$request->session()->has($ownerKey . '_data')) {
            return redirect()->route('campaigns.withdrawals', $campaign)
                ->withErrors(['otp' => 'No pending withdrawal request. Please start again.']);
        }

        $otp = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        $request->session()->put($ownerKey, $otp);
        $request->session()->put($ownerKey . '_expires', now()->addMinutes(10)->timestamp);

        Log::info('Withdrawal OTP resent', ['campaign_id' => $campaign->id, 'otp' => $otp]);

        $sms->sendByUser($campaign->user, $campaign->user->phone, "Your new Support Sphere withdrawal OTP is: {$otp}. Expires in 10 minutes.");

        return back()->with('status', 'otp-resent');
    }

    public function approve(Request $request, Withdrawal $withdrawal, WithdrawalService $withdrawalService)
    {
        $campaign = $withdrawal->campaign;
        $isTreasurer = $campaign->treasurers()
            ->where('user_id', auth()->id())
            ->where('can_approve_withdrawal', true)
            ->exists();
        if (!$isTreasurer) {
            abort(403, 'This action is unauthorized.');
        }

        $withdrawalService->approve($withdrawal, auth()->id(), $request->notes);

        return back()->with('success', 'Withdrawal approved!');
    }
}
