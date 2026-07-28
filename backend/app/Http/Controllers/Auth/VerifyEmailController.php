<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Notifications\UserPendingApproval;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\RedirectResponse;

class VerifyEmailController extends Controller
{
    /**
     * Mark the authenticated user's email address as verified.
     */
    public function __invoke(EmailVerificationRequest $request): RedirectResponse
    {
        if ($request->user()->hasVerifiedEmail()) {
        return redirect()->intended(
            $request->user()->hasVerifiedPhone()
                ? route('dashboard', absolute: false).'?verified=1'
                : route('phone.verification.notice', absolute: false)
        );
        }

        if ($request->user()->markEmailAsVerified()) {
            event(new Verified($request->user()));
        }

        if ($request->user()->hasVerifiedPhone()) {
            $admins = User::role(['admin', 'super_admin'])->get();
            foreach ($admins as $admin) {
                $admin->notify(new UserPendingApproval($request->user()));
            }
        }

        return redirect()->intended(
            $request->user()->hasVerifiedPhone()
                ? route('dashboard', absolute: false).'?verified=1'
                : route('phone.verification.notice', absolute: false)
        );
    }
}
