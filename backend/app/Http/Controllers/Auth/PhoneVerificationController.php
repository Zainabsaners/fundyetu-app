<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Notifications\UserPendingApproval;
use App\Services\TextSMSService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class PhoneVerificationController extends Controller
{
    const OTP_SESSION_KEY = 'phone_verification_otp';

    public function show(Request $request, TextSMSService $sms): View|RedirectResponse
    {
        $user = $request->user();

        if ($user->hasVerifiedPhone()) {
            return redirect()->route('pending.approval');
        }

        if (! $request->session()->has(self::OTP_SESSION_KEY)) {
            $otp = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);
            $request->session()->put(self::OTP_SESSION_KEY, $otp);
            $request->session()->put(self::OTP_SESSION_KEY . '_expires', now()->addMinutes(10)->timestamp);

            Log::info('OTP generated', ['user_id' => $user->id, 'otp' => $otp]);

            $sms->sendByUser($user, $user->phone, "Your Support Sphere verification code is: {$otp}. It expires in 10 minutes.");
        }

        return view('auth.verify-phone');
    }

    public function send(Request $request, TextSMSService $sms): RedirectResponse
    {
        $user = $request->user();

        if ($user->hasVerifiedPhone()) {
            return redirect()->route('pending.approval');
        }

        $otp = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        $request->session()->put(self::OTP_SESSION_KEY, $otp);
        $request->session()->put(self::OTP_SESSION_KEY . '_expires', now()->addMinutes(10)->timestamp);

        Log::info('OTP resent', ['user_id' => $user->id, 'otp' => $otp]);

        $sms->sendByUser($user, $user->phone, "Your Support Sphere verification code is: {$otp}. It expires in 10 minutes.");

        return back()->with('status', 'verification-code-sent');
    }

    public function verify(Request $request): RedirectResponse
    {
        $request->validate([
            'code' => ['required', 'string', 'size:6'],
        ]);

        $user = $request->user();

        if ($user->hasVerifiedPhone()) {
            return redirect()->route('pending.approval');
        }

        $cachedOtp = $request->session()->get(self::OTP_SESSION_KEY);
        $expires = $request->session()->get(self::OTP_SESSION_KEY . '_expires', 0);

        Log::info('Phone verify', [
            'user_id' => $user->id,
            'session_otp' => $cachedOtp,
            'input_code' => $request->code,
            'match' => $cachedOtp === $request->code,
            'expired' => now()->timestamp > $expires,
        ]);

        if (! $cachedOtp || $cachedOtp !== $request->code) {
            return back()->withErrors(['code' => 'Invalid or expired verification code.']);
        }

        $request->session()->forget(self::OTP_SESSION_KEY);
        $request->session()->forget(self::OTP_SESSION_KEY . '_expires');

        $user->phone_verified_at = now();
        $user->save();

        Log::info('Phone verified', ['user_id' => $user->id, 'phone_verified_at' => $user->phone_verified_at]);

        if ($user->hasVerifiedEmail()) {
            $admins = User::role(['admin', 'super_admin'])->get();
            foreach ($admins as $admin) {
                $admin->notify(new UserPendingApproval($user));
            }
            Log::info('Admin notified: user pending approval', ['user_id' => $user->id]);
        }

        return redirect()->route('pending.approval')->with('status', 'phone-verified');
    }

    public function resend(Request $request, TextSMSService $sms): RedirectResponse
    {
        return $this->send($request, $sms);
    }
}
