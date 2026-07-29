<?php

use App\Enums\CampaignStatus;
use App\Http\Controllers\Admin\CampaignController as AdminCampaignController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\DonationController as AdminDonationController;
use App\Http\Controllers\Admin\DonorController as AdminDonorController;
use App\Http\Controllers\Admin\WithdrawalController as AdminWithdrawalController;
use App\Http\Controllers\CampaignController;
use App\Models\Testimonial;
use App\Http\Controllers\DonationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StatementController;
use App\Http\Controllers\SupportTicketController;
use App\Http\Controllers\WithdrawalController;
use App\Models\Campaign;
use App\Models\Donation;
use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    $campaigns = Campaign::where('status', CampaignStatus::Active)
        ->with(['user', 'category', 'media'])
        ->latest()
        ->take(6)
        ->get();

    $totalCampaigns = Campaign::where('status', CampaignStatus::Active)->count();
    $totalRaised = Campaign::where('status', CampaignStatus::Active)->sum('raised_amount');
    $totalDonors = Donation::where('status', 'completed')->distinct('user_id')->count('user_id');

    $testimonials = Testimonial::active()->get();

    return view('landing', compact('campaigns', 'totalCampaigns', 'totalRaised', 'totalDonors', 'testimonials'));
})->name('home');

Route::get('/pending-approval', function () {
    if (!auth()->check()) {
        return redirect()->route('login');
    }
    if (auth()->user()->is_approved) {
        return auth()->user()->hasAnyRole(['admin', 'super_admin'])
            ? redirect()->route('admin.dashboard')
            : redirect()->route('dashboard');
    }
    return view('auth.pending-approval');
})->name('pending.approval');

Route::post('/pending-approval/update', function (\Illuminate\Http\Request $request) {
    if (!auth()->check()) {
        return redirect()->route('login');
    }

    $user = auth()->user();

    $validated = $request->validate([
        'email' => ['required', 'string', 'lowercase', 'email', 'max:255', \Illuminate\Validation\Rule::unique('users', 'email')->ignore($user->id)],
        'phone' => ['required', 'string', 'max:20'],
    ]);

    $emailChanged = $validated['email'] !== $user->email;
    $phoneChanged = $validated['phone'] !== $user->phone;

    if ($emailChanged) {
        $user->email = $validated['email'];
        $user->email_verified_at = null;
    }
    if ($phoneChanged) {
        $user->phone = $validated['phone'];
        $user->phone_verified_at = null;
        $request->session()->forget(\App\Http\Controllers\Auth\PhoneVerificationController::OTP_SESSION_KEY);
    }

    $user->save();

    return redirect()->route('pending.approval')->with('toast', 'Update was successful.');
})->name('pending.contact.update');

Route::view('/privacy-policy', 'privacy')->name('privacy');

Route::get('/campaigns', [CampaignController::class, 'index'])->name('campaigns.index');

Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])
    ->middleware(['auth', 'verified', 'approved'])->name('dashboard');

Route::resource('campaigns', CampaignController::class)
    ->except(['index', 'show'])
    ->middleware(['auth', 'approved']);

Route::controller(CampaignController::class)->group(function () {
    Route::get('/my-campaigns', 'myCampaigns')
        ->name('campaigns.my')
        ->middleware(['auth', 'approved']);
    Route::get('/campaigns/{campaign}', 'show')->name('campaigns.show');
    Route::post('/campaigns/{campaign}/publish', 'publish')
        ->name('campaigns.publish')
        ->middleware(['auth', 'approved']);
    Route::post('/campaigns/{campaign}/comment', 'comment')
        ->name('campaigns.comment')
        ->middleware(['auth', 'approved']);
});

Route::get('/campaigns/{campaign}/donate', [DonationController::class, 'create'])
    ->name('donations.create');
Route::post('/campaigns/{campaign}/donate', [DonationController::class, 'store'])
    ->name('donations.store');
Route::get('/donations/{donation}/status', [DonationController::class, 'status'])
    ->name('donations.status');
Route::get('/donations/{donation}/confirmation', [DonationController::class, 'confirmation'])
    ->name('donations.confirmation');
Route::get('/donations/{donation}/thank-you', [DonationController::class, 'thankYou'])
    ->name('donations.thank-you');
Route::get('/donations/{donation}/callback', [DonationController::class, 'callback'])
    ->name('donations.callback');

Route::get('/default.png', function () {
    $path = public_path('assets/images/default.png');
    if (!file_exists($path)) {
        abort(404);
    }
    return response()->file($path, [
        'Access-Control-Allow-Origin' => '*',
    ]);
});


Route::middleware(['auth', 'approved'])->group(function () {
    Route::post('/notifications/read', function () {
        auth()->user()->unreadNotifications->markAsRead();
        return response()->json(['success' => true]);
    })->name('notifications.read');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::get('/profile/verify-email-otp', [ProfileController::class, 'showVerifyEmailOtp'])->name('profile.verify-email-otp');
    Route::post('/profile/verify-email-otp', [ProfileController::class, 'verifyEmailOtp'])->name('profile.verify-email-otp.post');
    Route::get('/profile/verify-password-otp', [App\Http\Controllers\Auth\PasswordController::class, 'showVerifyOtp'])->name('profile.verify-password-otp');
    Route::post('/profile/verify-password-otp', [App\Http\Controllers\Auth\PasswordController::class, 'verifyOtp'])->name('profile.verify-password-otp.post');
    Route::patch('/profile/kyc', [ProfileController::class, 'updateKyc'])->name('profile.kyc');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/campaigns/{campaign}/statement', [StatementController::class, 'index'])
        ->name('campaigns.statement');

    Route::get('/withdraw-funds', [WithdrawalController::class, 'overview'])
        ->name('withdraw.index');
    Route::get('/campaigns/{campaign}/withdrawals', [WithdrawalController::class, 'index'])
        ->name('campaigns.withdrawals');
    Route::post('/campaigns/{campaign}/withdrawals', [WithdrawalController::class, 'store'])
        ->name('campaigns.withdrawals.store');
    Route::get('/campaigns/{campaign}/withdrawals/verify-otp', [WithdrawalController::class, 'showVerifyOtp'])
        ->name('campaigns.withdrawals.verify-otp');
    Route::post('/campaigns/{campaign}/withdrawals/verify-otp', [WithdrawalController::class, 'verifyOtp'])
        ->name('campaigns.withdrawals.verify-otp.post');
    Route::post('/campaigns/{campaign}/withdrawals/resend-otp', [WithdrawalController::class, 'resendOtp'])
        ->name('campaigns.withdrawals.resend-otp');
    Route::get('/campaigns/{campaign}/withdrawals/treasurer-otp', [WithdrawalController::class, 'showTreasurerOtp'])
        ->name('campaigns.withdrawals.treasurer-otp');
    Route::post('/campaigns/{campaign}/withdrawals/treasurer-otp', [WithdrawalController::class, 'verifyTreasurerOtp'])
        ->name('campaigns.withdrawals.treasurer-otp.post');
    Route::post('/withdrawals/{withdrawal}/approve', [WithdrawalController::class, 'approve'])
        ->name('withdrawals.approve');
    Route::get('/campaigns/{campaign}/withdrawals/{withdrawal}/invoice', [WithdrawalController::class, 'invoice'])
        ->name('campaigns.withdrawals.invoice');
    

    Route::get('/tickets', [SupportTicketController::class, 'index'])->name('tickets.index');
    Route::get('/tickets/{ticket}', [SupportTicketController::class, 'show'])->name('tickets.show');
    Route::post('/tickets', [SupportTicketController::class, 'store'])->name('tickets.store');

    Route::get('/feedback', [App\Http\Controllers\FeedbackController::class, 'index'])->name('feedbacks.index');
    Route::post('/feedback', [App\Http\Controllers\FeedbackController::class, 'store'])->name('feedbacks.store');
});

Route::get('/', function () {
    return response()->json(['message' => 'FundYetu API is running']);
});


Route::prefix('admin')->name('admin.')->middleware(['auth', 'role:admin|super_admin'])->group(function () {
    Route::post('/notifications/read', function () {
        auth()->user()->unreadNotifications->markAsRead();
        return response()->json(['success' => true]);
    })->name('notifications.read');

    Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::get('/reports', [AdminDashboardController::class, 'reports'])->name('reports');

    Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
    Route::get('/users/{user}/json', [AdminUserController::class, 'show'])->name('users.show');
    Route::get('/users/{user}', [AdminUserController::class, 'details'])->name('users.details');
    Route::get('/users/{user}/edit', [AdminUserController::class, 'edit'])->name('users.edit');
    Route::post('/users/{user}/update', [AdminUserController::class, 'update'])->name('users.update');
    Route::post('/users/{user}/delete', [AdminUserController::class, 'destroy'])->name('users.delete');
    Route::post('/users/{user}/approve', [AdminUserController::class, 'approve'])->name('users.approve');
    Route::post('/users/{user}/reject', [AdminUserController::class, 'reject'])->name('users.reject');
    Route::post('/users/{user}/activate', [AdminUserController::class, 'activate'])->name('users.activate');
    Route::post('/users/{user}/deactivate', [AdminUserController::class, 'deactivate'])->name('users.deactivate');

    Route::get('/campaigns', [AdminCampaignController::class, 'index'])->name('campaigns.index');
    Route::get('/campaigns/{campaign}', [AdminCampaignController::class, 'show'])->name('campaigns.show');
    Route::post('/campaigns/{campaign}/verify', [AdminCampaignController::class, 'verify'])->name('campaigns.verify');
    Route::post('/campaigns/{campaign}/reject', [AdminCampaignController::class, 'reject'])->name('campaigns.reject');
    Route::post('/campaigns/{campaign}/close', [AdminCampaignController::class, 'close'])->name('campaigns.close');
    Route::post('/campaigns/{campaign}/extend', [AdminCampaignController::class, 'extend'])->name('campaigns.extend');
    Route::get('/campaigns/{campaign}/edit', [AdminCampaignController::class, 'edit'])->name('campaigns.edit');
    Route::post('/campaigns/{campaign}/update', [AdminCampaignController::class, 'update'])->name('campaigns.update');
    Route::post('/campaigns/{campaign}/delete', [AdminCampaignController::class, 'destroy'])->name('campaigns.delete');
    Route::post('/campaigns/{campaign}/status', [AdminCampaignController::class, 'updateStatus'])->name('campaigns.status');

    Route::get('/donors', [AdminDonorController::class, 'index'])->name('donors.index');
    Route::get('/donations', [AdminDonationController::class, 'index'])->name('donations.index');
    Route::get('/withdrawals', [AdminWithdrawalController::class, 'index'])->name('withdrawals.index');
    Route::post('/withdrawals/{withdrawal}/send-otp', [AdminWithdrawalController::class, 'sendOtp'])->name('withdrawals.send-otp');
    Route::post('/withdrawals/{withdrawal}/approve', [AdminWithdrawalController::class, 'approve'])->name('withdrawals.approve');
    Route::post('/withdrawals/{withdrawal}/reject', [AdminWithdrawalController::class, 'reject'])->name('withdrawals.reject');
    Route::get('/withdrawals/{withdrawal}/invoice', [AdminWithdrawalController::class, 'invoice'])->name('withdrawals.invoice');

    Route::get('/tickets', [App\Http\Controllers\Admin\SupportTicketController::class, 'index'])->name('tickets.index');
    Route::get('/tickets/{ticket}', [App\Http\Controllers\Admin\SupportTicketController::class, 'show'])->name('tickets.show');
    Route::post('/tickets/{ticket}/reply', [App\Http\Controllers\Admin\SupportTicketController::class, 'reply'])->name('tickets.reply');
    Route::post('/tickets/{ticket}/reopen', [App\Http\Controllers\Admin\SupportTicketController::class, 'reopen'])->name('tickets.reopen');

    Route::get('/feedback', [App\Http\Controllers\Admin\FeedbackController::class, 'index'])->name('feedbacks.index');
    Route::post('/feedback/{testimonial}/approve', [App\Http\Controllers\Admin\FeedbackController::class, 'approve'])->name('feedbacks.approve');
    Route::post('/feedback/{testimonial}/reject', [App\Http\Controllers\Admin\FeedbackController::class, 'reject'])->name('feedbacks.reject');

    Route::get('/settings', [App\Http\Controllers\Admin\SettingController::class, 'index'])->name('settings.index');
    Route::post('/settings', [App\Http\Controllers\Admin\SettingController::class, 'update'])->name('settings.update');
    Route::post('/settings/profile', [App\Http\Controllers\Admin\SettingController::class, 'profileUpdate'])->name('settings.profile');
    Route::post('/settings/send-otp', [App\Http\Controllers\Admin\SettingController::class, 'sendOtp'])->name('settings.send-otp');
    Route::post('/settings/cache/{type}', [App\Http\Controllers\Admin\SettingController::class, 'clearCache'])->name('settings.cache');
});

require __DIR__.'/auth.php';
