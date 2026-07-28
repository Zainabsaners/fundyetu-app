<?php

namespace App\Http\Controllers;

use App\Enums\CampaignStatus;
use App\Enums\DonationStatus;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        if ($user->hasAnyRole(['admin', 'super_admin'])) {
            return redirect()->route('admin.dashboard');
        }

        if (!$user->hasVerifiedPhone()) {
            return redirect()->route('phone.verification.notice');
        }

        $stats = [
            'my_campaigns' => $user->campaigns()->count(),
            'active_campaigns' => $user->campaigns()->where('status', CampaignStatus::Active)->count(),
            'total_raised' => $user->campaigns()->sum('raised_amount'),
            'my_donations' => $user->donations()->where('status', DonationStatus::Completed)->count(),
            'pending_verification' => $user->campaigns()->where('status', CampaignStatus::PendingVerification)->count(),
        ];

        $draft_campaigns = $user->campaigns()
            ->where('status', CampaignStatus::Draft)
            ->latest()
            ->get();

        $my_campaigns = $user->campaigns()
            ->with(['category', 'media'])
            ->latest()
            ->take(5)
            ->get();

        $top_campaigns = $user->campaigns()
            ->orderBy('raised_amount', 'desc')
            ->take(5)
            ->get();

        $campaignIds = $user->campaigns()->pluck('id');
        $recent_donations = \App\Models\Donation::whereIn('campaign_id', $campaignIds)
            ->where('status', DonationStatus::Completed)
            ->with('campaign')
            ->latest()
            ->take(5)
            ->get();

        $monthly_raised = $user->campaigns()
            ->join('donations', 'campaigns.id', '=', 'donations.campaign_id')
            ->where('donations.status', DonationStatus::Completed)
            ->where('donations.created_at', '>=', now()->subMonths(6))
            ->select(DB::raw("DATE_FORMAT(donations.created_at, '%Y-%m') as month"), DB::raw('SUM(donations.amount) as total'))
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        return view('dashboard', compact(
            'stats',
            'draft_campaigns',
            'my_campaigns',
            'top_campaigns',
            'recent_donations',
            'monthly_raised'
        ));
    }
}
