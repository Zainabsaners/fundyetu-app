<?php

namespace App\Http\Controllers;

use App\Models\Campaign;

class StatementController extends Controller
{
    public function index(Campaign $campaign)
    {
        $donations = $campaign->donations()
            ->where('status', 'completed')
            ->latest()
            ->paginate(20);

        $withdrawals = $campaign->withdrawals()
            ->whereIn('status', ['disbursed', 'admin_approved'])
            ->latest()
            ->get();

        $approvedWithdrawals = $campaign->withdrawals()
            ->whereIn('status', ['disbursed', 'admin_approved'])
            ->sum('amount');

        $balance = $campaign->balance;

        return view('campaigns.statement', compact('campaign', 'donations', 'withdrawals', 'approvedWithdrawals', 'balance'));
    }
}
