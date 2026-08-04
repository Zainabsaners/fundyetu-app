<?php

namespace App\Http\Controllers;

use App\Enums\CampaignStatus;
use App\Models\Campaign;
use App\Models\Donation;
use App\Models\Setting;
use App\Services\FeeCalculator;
use App\Services\Payment\FlutterwaveGateway;
use App\Services\Payment\MpesaGateway;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class DonationController extends Controller
{
    public function create(Campaign $campaign)
    {
        $enabledMethods = [
            'mpesa' => Setting::get('mpesa_enabled', '1') === '1',
            'airtel' => Setting::get('airtel_enabled', '1') === '1',
            'card' => Setting::get('card_enabled', '0') === '1',
        ];

        return view('campaigns.donate', compact('campaign', 'enabledMethods'));
    }

    public function store(Request $request, Campaign $campaign, FeeCalculator $feeCalculator)
    {
        $enabledMethods = collect(['mpesa', 'airtel', 'card'])
            ->filter(fn ($m) => Setting::get($m . '_enabled', $m === 'mpesa' || $m === 'airtel' ? '1' : '0') === '1')
            ->values()->implode(',');

        $validated = $request->validate([
            'amount' => 'required|numeric|min:10',
            'payment_method' => 'required|in:' . $enabledMethods,
            'donor_name' => 'nullable|string|max:255',
            'donor_email' => 'nullable|email',
            'donor_phone' => 'required_if:payment_method,mpesa,airtel|string',
            'message' => 'nullable|string|max:500',
        ]);

        if (! $campaign->isActive()) {
            return $request->expectsJson()
                ? response()->json(['error' => 'This campaign is not accepting donations.'], 422)
                : back()->with('toast', 'This campaign is not accepting donations.');
        }

        $phone = $validated['donor_phone'] ?? '';
        $phone = ltrim($phone, '+');
        if (str_starts_with($phone, '0')) {
            $phone = '254' . substr($phone, 1);
        }
        $validated['donor_phone'] = $phone;

        $donation = $campaign->donations()->create([
            'user_id' => auth()->id(),
            'donor_name' => $validated['donor_name'] ?? auth()->user()?->name,
            'donor_email' => $validated['donor_email'] ?? auth()->user()?->email,
            'donor_phone' => $validated['donor_phone'],
            'amount' => $validated['amount'],
            'payment_method' => $validated['payment_method'],
            'message' => $validated['message'] ?? null,
            'status' => 'pending',
        ]);

        $gateway = match ($validated['payment_method']) {
            'mpesa' => app(MpesaGateway::class),
            'airtel' => app(\App\Services\Payment\AirtelGateway::class),
            'card' => app(FlutterwaveGateway::class),
        };

        try {
            $result = $gateway->processPayment($validated['amount'], [
                'phone' => $validated['donor_phone'],
                'reference' => 'DON-' . $donation->id,
                'campaign_id' => $campaign->id,
                'email' => $validated['donor_email'] ?? null,
                'name' => $validated['donor_name'],
                'title' => $campaign->title,
                'redirect_url' => route('donations.callback', $donation),
                'description' => "Donation to {$campaign->title}",
            ]);

            if (isset($result['CheckoutRequestID'])) {
                $donation->update(['payment_ref' => $result['CheckoutRequestID']]);
            }

            if (($result['ResponseCode'] ?? '1') !== '0') {
                Log::error('M-Pesa STK push failed', [
                    'donation_id' => $donation->id,
                    'response' => $result,
                ]);
                $errorMsg = $result['ResponseDescription'] ?? ('M-Pesa error (Code: ' . ($result['ResponseCode'] ?? 'unknown') . ')');
                return $request->expectsJson()
                    ? response()->json(['error' => $errorMsg], 422)
                    : back()->withInput()->with('toast', $errorMsg);
            }

            return $request->expectsJson()
                ? response()->json(['donation_id' => $donation->id, 'checkout_request_id' => $result['CheckoutRequestID'] ?? null])
                : redirect()->route('donations.confirmation', $donation);
        } catch (\Exception $e) {
            Log::error('M-Pesa payment failed', [
                'donation_id' => $donation->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return $request->expectsJson()
                ? response()->json(['error' => 'Payment error: ' . $e->getMessage()], 500)
                : back()->withInput()->with('toast', 'Payment error: ' . $e->getMessage());
        }
    }

    public function status(Donation $donation)
    {
        return response()->json([
            'status' => $donation->status,
            'payment_ref' => $donation->payment_ref,
        ]);
    }

    public function confirmation(Donation $donation)
    {
        return view('campaigns.confirmation', compact('donation'));
    }

    public function thankYou(Donation $donation)
    {
        $donation->load('campaign');

        $campaigns = Campaign::where('status', CampaignStatus::Active)
            ->where('id', '!=', $donation->campaign_id)
            ->with(['user', 'category', 'media'])
            ->inRandomOrder()
            ->take(6)
            ->get();

        return view('campaigns.thank-you', compact('donation', 'campaigns'));
    }

    public function callback(\App\Models\Donation $donation)
    {
        $donation->load('campaign');

        return view('campaigns.callback', compact('donation'));
    }
}
