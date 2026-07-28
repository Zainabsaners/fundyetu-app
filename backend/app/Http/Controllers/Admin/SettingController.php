<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\EnvWriter;
use App\Models\ActivityLog;
use App\Models\Setting;
use App\Models\User;
use App\Notifications\SettingsOtp;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;

class SettingController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin|super_admin']);
    }

    public function index()
    {
        $settings = Setting::pluck('value', 'key')->all();

        $activityLogs = ActivityLog::with('user')
            ->latest()
            ->paginate(20);

        $notifications = auth()->user()->notifications()
            ->latest()
            ->paginate(20);

        return view('admin.settings.index', compact(
            'settings', 'activityLogs', 'notifications'
        ));
    }

    public function sendOtp(Request $request)
    {
        $user = auth()->user();
        $code = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        Cache::put('settings_otp_' . $user->id, $code, now()->addMinutes(5));

        $user->notify(new SettingsOtp($code));

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'OTP sent to your email.']);
        }

        return back()->with('success', 'OTP sent to your email.');
    }

    public function update(Request $request)
    {
        $user = auth()->user();
        $cachedOtp = Cache::get('settings_otp_' . $user->id);

        $request->validate(['otp' => 'required|string|size:6']);

        if (!$cachedOtp || $request->otp !== $cachedOtp) {
            return back()->withErrors(['otp' => 'Invalid or expired OTP.'])->withInput();
        }

        Cache::forget('settings_otp_' . $user->id);

        $validated = $request->validate([
            // Platform
            'platform_fee_percent' => 'sometimes|required|numeric|min:0|max:100',
            'withdrawal_fee' => 'sometimes|required|numeric|min:0',
            // SMS
            'sms_cost_per_credit' => 'sometimes|required|numeric|min:0',
            'sms_sender_id' => 'nullable|string|max:50',
            'sms_provider' => 'nullable|string|max:50',
            'sms_api_key' => 'nullable|string',
            'sms_partner_id' => 'nullable|string',
            'sms_api_secret' => 'nullable|string',
            // Payment gateways
            'mpesa_enabled' => 'nullable|in:0,1',
            'airtel_enabled' => 'nullable|in:0,1',
            'card_enabled' => 'nullable|in:0,1',
            'paypal_enabled' => 'nullable|in:0,1',
            'mpesa_consumer_key' => 'nullable|string',
            'mpesa_consumer_secret' => 'nullable|string',
            'mpesa_passkey' => 'nullable|string',
            'mpesa_shortcode' => 'nullable|string',
            'mpesa_environment' => 'nullable|in:sandbox,live',
            // SMTP
            'smtp_host' => 'nullable|string|max:255',
            'smtp_port' => 'nullable|numeric|min:1|max:65535',
            'smtp_username' => 'nullable|string',
            'smtp_password' => 'nullable|string',
            'smtp_encryption' => 'nullable|string|max:10',
            'smtp_from_address' => 'nullable|email',
            'smtp_from_name' => 'nullable|string|max:255',
            'smtp_admin_address' => 'nullable|email',
        ]);

        foreach ($validated as $key => $value) {
            Setting::set($key, $value ?? '');
        }

        // Write SMS settings to .env
        if ($request->hasAny(['sms_api_key', 'sms_partner_id', 'sms_sender_id'])) {
            if ($request->filled('sms_api_key')) {
                EnvWriter::set('TEXTSMS_API_KEY', $request->sms_api_key);
            }
            if ($request->filled('sms_partner_id')) {
                EnvWriter::set('TEXTSMS_PARTNER_ID', $request->sms_partner_id);
            }
            if ($request->filled('sms_sender_id')) {
                EnvWriter::set('TEXTSMS_SHORTCODE', $request->sms_sender_id);
            }
        }

        // Write M-Pesa credentials to .env
        if ($request->hasAny(['mpesa_consumer_key', 'mpesa_consumer_secret', 'mpesa_passkey', 'mpesa_shortcode', 'mpesa_environment'])) {
            if ($request->filled('mpesa_consumer_key')) {
                EnvWriter::set('MPESA_CONSUMER_KEY', $request->mpesa_consumer_key);
            }
            if ($request->filled('mpesa_consumer_secret')) {
                EnvWriter::set('MPESA_CONSUMER_SECRET', $request->mpesa_consumer_secret);
            }
            if ($request->filled('mpesa_passkey')) {
                EnvWriter::set('MPESA_PASSKEY', $request->mpesa_passkey);
            }
            if ($request->filled('mpesa_shortcode')) {
                EnvWriter::set('MPESA_SHORTCODE', $request->mpesa_shortcode);
            }
            if ($request->filled('mpesa_environment')) {
                EnvWriter::set('MPESA_ENVIRONMENT', $request->mpesa_environment);
            }
        }

        // Write SMTP settings to .env
        if ($request->hasAny(['smtp_host', 'smtp_port', 'smtp_username', 'smtp_password', 'smtp_encryption', 'smtp_from_address', 'smtp_from_name'])) {
            if ($request->filled('smtp_host')) {
                EnvWriter::set('MAIL_HOST', $request->smtp_host);
            }
            if ($request->filled('smtp_port')) {
                EnvWriter::set('MAIL_PORT', $request->smtp_port);
            }
            if ($request->filled('smtp_username')) {
                EnvWriter::set('MAIL_USERNAME', $request->smtp_username);
            }
            if ($request->filled('smtp_password')) {
                EnvWriter::set('MAIL_PASSWORD', $request->smtp_password);
            }
            if ($request->filled('smtp_encryption')) {
                EnvWriter::set('MAIL_ENCRYPTION', $request->smtp_encryption);
            }
            if ($request->filled('smtp_from_address')) {
                EnvWriter::set('MAIL_FROM_ADDRESS', $request->smtp_from_address);
            }
            if ($request->filled('smtp_from_name')) {
                EnvWriter::set('MAIL_FROM_NAME', $request->smtp_from_name);
            }
        }

        Artisan::call('config:clear');

        return back()->with('success', 'Settings updated successfully.');
    }

    public function profileUpdate(Request $request)
    {
        $user = auth()->user();

        $cachedOtp = Cache::get('settings_otp_' . $user->id);

        $request->validate(['otp' => 'required|string|size:6']);

        if (!$cachedOtp || $request->otp !== $cachedOtp) {
            return back()->withErrors(['otp' => 'Invalid or expired OTP.'])->withInput();
        }

        Cache::forget('settings_otp_' . $user->id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20|unique:users,phone,' . $user->id,
        ]);

        $user->update($validated);

        if ($request->filled('password')) {
            $request->validate(['password' => 'required|string|min:8|confirmed']);
            $user->update(['password' => Hash::make($request->password)]);
        }

        return back()->with('success', 'Profile updated successfully.');
    }

    public function clearCache(string $type)
    {
        match ($type) {
            'config' => Artisan::call('config:clear'),
            'route' => Artisan::call('route:clear'),
            'view' => Artisan::call('view:clear'),
            'all' => Artisan::call('optimize:clear'),
            default => throw new \InvalidArgumentException('Unknown cache type: ' . $type),
        };

        return back()->with('toast', ucfirst($type) . ' cache cleared successfully.');
    }
}
