<?php

namespace App\Http\Controllers\Payment;

use App\Models\Donation;
use App\Models\Transaction;
use App\Services\FeeCalculator;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class FlutterwaveController extends Controller
{
    public function webhook(Request $request, FeeCalculator $feeCalculator)
    {
        $payload = $request->all();
        $event = $payload['event'] ?? '';

        if ($event !== 'charge.completed') {
            return response()->json(['status' => 'ignored']);
        }

        $data = $payload['data'] ?? [];
        $donation = Donation::find($data['meta']['campaign_id'] ?? null);

        if (! $donation) {
            return response()->json(['status' => 'not_found'], 404);
        }

        $campaign = $donation->campaign;
        $amount = $data['amount'] ?? 0;
        $fees = $feeCalculator->calculateForDonation((float) $amount, $campaign, 'card');

        $donation->update([
            'amount' => $fees['amount'],
            'fee' => $fees['total_fee'],
            'net_amount' => $fees['net_amount'],
            'payment_ref' => $data['id'] ?? null,
            'status' => 'completed',
        ]);

        $campaign->increment('raised_amount', $fees['net_amount']);
        $campaign->increment('balance', $fees['net_amount']);

        Transaction::create([
            'campaign_id' => $campaign->id,
            'type' => 'donation',
            'amount' => $fees['net_amount'],
            'balance_before' => $campaign->balance - $fees['net_amount'],
            'balance_after' => $campaign->balance,
            'description' => 'Card donation from ' . ($data['customer']['email'] ?? 'unknown'),
            'transactable_id' => $donation->id,
            'transactable_type' => Donation::class,
        ]);

        return response()->json(['status' => 'success']);
    }
}
