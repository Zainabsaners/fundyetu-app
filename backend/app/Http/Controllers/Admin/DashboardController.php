<?php

namespace App\Http\Controllers\Admin;

use App\Enums\CampaignStatus;
use App\Enums\DonationStatus;
use App\Enums\WithdrawalStatus;
use App\Models\Campaign;
use App\Models\Donation;
use App\Models\User;
use App\Models\Withdrawal;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin|super_admin']);
    }

    public function index()
    {
        $stats = [
            'total_campaigns' => Campaign::count(),
            'active_campaigns' => Campaign::where('status', CampaignStatus::Active)->count(),
            'pending_campaigns' => Campaign::where('status', CampaignStatus::PendingVerification)->count(),
            'total_users' => User::count(),
            'verified_users' => User::where('kyc_status', 'verified')->count(),
            'total_raised' => Campaign::sum('raised_amount'),
            'total_donations' => Donation::where('status', DonationStatus::Completed)->count(),
            'pending_withdrawals' => Withdrawal::where('status', 'treasurer_approved')->count(),
        ];

        $campaigns_by_status = Campaign::select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->pluck('count', 'status');

        $monthly_donations = Donation::where('status', DonationStatus::Completed)
            ->where('created_at', '>=', now()->subMonths(6))
            ->select(DB::raw("DATE_FORMAT(created_at, '%Y-%m') as month"), DB::raw('SUM(amount) as total'), DB::raw('COUNT(*) as count'))
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        $recent_campaigns = Campaign::with(['user', 'category'])
            ->latest()
            ->take(5)
            ->get();

        $top_campaigns = Campaign::where('status', CampaignStatus::Active)
            ->with(['user'])
            ->orderBy('raised_amount', 'desc')
            ->take(5)
            ->get();

        $donor_growth = Donation::where('status', DonationStatus::Completed)
            ->whereNotNull('donor_email')
            ->where('donor_email', '!=', '')
            ->where('created_at', '>=', now()->subMonths(6))
            ->select(DB::raw("DATE_FORMAT(created_at, '%Y-%m') as month"), DB::raw('COUNT(DISTINCT donor_email) as count'))
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        return view('admin.dashboard', compact(
            'stats',
            'campaigns_by_status',
            'monthly_donations',
            'recent_campaigns',
            'top_campaigns',
            'donor_growth'
        ));
    }

    public function reports()
    {
        $disbursed = Withdrawal::where('status', WithdrawalStatus::Disbursed);

        $total_platform_fees = (float) $disbursed->sum('platform_fee');
        $total_sms_costs = (float) $disbursed->sum('sms_charge');
        $net_earnings = $total_platform_fees - $total_sms_costs;
        $total_withdrawals = $disbursed->count();
        $total_disbursed = (float) $disbursed->sum('amount');

        $monthly = Withdrawal::where('status', WithdrawalStatus::Disbursed)
            ->where('disbursed_at', '>=', now()->subMonths(12))
            ->select(
                DB::raw("DATE_FORMAT(disbursed_at, '%Y-%m') as month"),
                DB::raw('SUM(platform_fee) as platform_fees'),
                DB::raw('SUM(sms_charge) as sms_costs'),
                DB::raw('COUNT(*) as count'),
                DB::raw('SUM(amount) as total_amount')
            )
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        $recent = Withdrawal::with('campaign')
            ->where('status', WithdrawalStatus::Disbursed)
            ->latest('disbursed_at')
            ->take(20)
            ->get();

        return view('admin.reports', compact(
            'total_platform_fees',
            'total_sms_costs',
            'net_earnings',
            'total_withdrawals',
            'total_disbursed',
            'monthly',
            'recent'
        ));
    }
}
