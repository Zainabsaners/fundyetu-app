<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\User;
use App\Notifications\KycResubmitted;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class ProfileController extends Controller
{
    const EMAIL_OTP_KEY = 'profile_email_otp';
    const PASSWORD_OTP_KEY = 'profile_password_otp';

    public function edit(Request $request): View
    {
        $user = $request->user();

        $notifications = $user->notifications()->latest()->take(10)->get();
        $campaignsCount = $user->campaigns()->count();
        $donationsCount = $user->donations()->where('status', 'completed')->count();
        $activeCampaigns = $user->campaigns()->where('status', 'active')->count();

        return view('profile.edit', compact(
            'user', 'notifications', 'campaignsCount', 'donationsCount', 'activeCampaigns'
        ));
    }

    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();
        $validated = $request->validated();

        if ($validated['email'] !== $user->email) {
            $otp = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);
            $request->session()->put(self::EMAIL_OTP_KEY, $otp);
            $request->session()->put(self::EMAIL_OTP_KEY . '_expires', now()->addMinutes(10)->timestamp);
            $request->session()->put(self::EMAIL_OTP_KEY . '_data', ['email' => $validated['email'], 'name' => $validated['name']]);

            Log::info('Profile email change OTP', ['user_id' => $user->id, 'otp' => $otp, 'new_email' => $validated['email']]);

            Mail::raw("Your Support Sphere email change verification code is: {$otp}. It expires in 10 minutes.", function ($message) use ($user) {
                $message->to($user->email)
                    ->subject('Verify Email Change');
            });

            return redirect()->route('profile.verify-email-otp')->with('status', 'otp-sent');
        }

        $user->fill($validated);
        $user->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated')->with('toast', 'Profile updated successfully.');
    }

    public function showVerifyEmailOtp(Request $request)
    {
        if (!$request->session()->has(self::EMAIL_OTP_KEY)) {
            return redirect()->route('profile.edit')->withErrors(['otp' => 'No pending email change.']);
        }
        return view('profile.verify-email-otp');
    }

    public function verifyEmailOtp(Request $request)
    {
        $request->validate(['code' => ['required', 'string', 'size:6']]);

        $user = $request->user();
        $cachedOtp = $request->session()->get(self::EMAIL_OTP_KEY);
        $expires = $request->session()->get(self::EMAIL_OTP_KEY . '_expires', 0);
        $data = $request->session()->get(self::EMAIL_OTP_KEY . '_data');

        if (!$cachedOtp || $cachedOtp !== $request->code || now()->timestamp > $expires) {
            return back()->withErrors(['code' => 'Invalid or expired verification code.']);
        }

        $request->session()->forget(self::EMAIL_OTP_KEY);
        $request->session()->forget(self::EMAIL_OTP_KEY . '_expires');
        $request->session()->forget(self::EMAIL_OTP_KEY . '_data');

        $user->fill(['name' => $data['name'], 'email' => $data['email']]);
        $user->email_verified_at = null;
        $user->save();

        Log::info('Email changed via OTP', ['user_id' => $user->id, 'new_email' => $data['email']]);

        return redirect()->route('profile.edit')->with('status', 'profile-updated')->with('toast', 'Profile updated successfully.');
    }

    public function updateKyc(\App\Http\Requests\KycUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();
        $data = $request->validated();

        $user->id_number = $data['id_number'];
        $user->birth_year = $data['birth_year'];
        $user->address = $data['address'];
        $user->withdrawal_method = $data['withdrawal_method'];
        $user->mpesa_phone = $data['withdrawal_method'] === 'mpesa' ? $data['mpesa_phone'] : null;
        $user->bank_name = $data['withdrawal_method'] === 'bank' ? $data['bank_name'] : null;
        $user->bank_account_number = $data['withdrawal_method'] === 'bank' ? $data['bank_account_number'] : null;
        $user->bank_account_name = $data['withdrawal_method'] === 'bank' ? $data['bank_account_name'] : null;

        if ($request->hasFile('id_front')) {
            $user->id_front_path = $request->file('id_front')->store('kyc', 'public');
        }
        if ($request->hasFile('id_back')) {
            $user->id_back_path = $request->file('id_back')->store('kyc', 'public');
        }
        if ($request->hasFile('address_proof')) {
            $user->address_proof_path = $request->file('address_proof')->store('kyc', 'public');
        }
        if ($request->hasFile('profile_photo')) {
            $user->profile_photo_path = $request->file('profile_photo')->store('kyc', 'public');
        }

        if ($user->kyc_status !== 'verified') {
            $wasRejected = $user->kyc_status === 'rejected';
            $user->kyc_status = 'pending';
        }

        $user->save();

        if ($wasRejected) {
            $admins = User::role(['admin', 'super_admin'])->get();
            foreach ($admins as $admin) {
                $admin->notify(new KycResubmitted($user));
            }
        }

        return Redirect::route('profile.edit')->with('kyc-status', 'updated')->with('toast', 'KYC information saved successfully.');
    }

    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
