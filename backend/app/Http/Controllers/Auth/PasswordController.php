<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\TextSMSService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rules\Password;

class PasswordController extends Controller
{
    const OTP_KEY = 'password_change_otp';

    public function update(Request $request, TextSMSService $sms): RedirectResponse
    {
        $validated = $request->validateWithBag('updatePassword', [
            'current_password' => ['required', 'current_password'],
            'password' => ['required', Password::defaults(), 'confirmed'],
        ]);

        $user = $request->user();

        $otp = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        $request->session()->put(self::OTP_KEY, $otp);
        $request->session()->put(self::OTP_KEY . '_expires', now()->addMinutes(10)->timestamp);
        $request->session()->put(self::OTP_KEY . '_data', ['password' => Hash::make($validated['password'])]);

        Log::info('Password change OTP', ['user_id' => $user->id, 'otp' => $otp]);

        $sms->sendByUser($user, $user->phone, "Your Support Sphere password change verification code is: {$otp}. It expires in 10 minutes.");

        return redirect()->route('profile.verify-password-otp')->with('status', 'otp-sent');
    }

    public function showVerifyOtp(Request $request)
    {
        if (!$request->session()->has(self::OTP_KEY)) {
            return redirect()->route('profile.edit')->withErrors(['otp' => 'No pending password change.']);
        }
        return view('profile.verify-password-otp');
    }

    public function verifyOtp(Request $request)
    {
        $request->validate(['code' => ['required', 'string', 'size:6']]);

        $user = $request->user();
        $cachedOtp = $request->session()->get(self::OTP_KEY);
        $expires = $request->session()->get(self::OTP_KEY . '_expires', 0);
        $data = $request->session()->get(self::OTP_KEY . '_data');

        if (!$cachedOtp || $cachedOtp !== $request->code || now()->timestamp > $expires) {
            return back()->withErrors(['code' => 'Invalid or expired verification code.']);
        }

        $request->session()->forget(self::OTP_KEY);
        $request->session()->forget(self::OTP_KEY . '_expires');
        $request->session()->forget(self::OTP_KEY . '_data');

        $user->update(['password' => $data['password']]);

        Log::info('Password changed via OTP', ['user_id' => $user->id]);

        return redirect()->route('profile.edit')->with('status', 'password-updated')->with('toast', 'Password updated successfully.');
    }
}
