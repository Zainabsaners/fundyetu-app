<x-dashboard-layout title="Dashboard">
    <x-slot name="header">Dashboard</x-slot>

    {{-- Welcome --}}
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-900">Welcome back, {{ Auth::user()->name }}!</h2>
        <p class="text-sm text-gray-500 mt-1">Here's an overview of your fundraising activity.</p>
    </div>

    @php $kycStatus = Auth::user()->kyc_status; $hasKycData = Auth::user()->id_number || Auth::user()->id_front_path; $kycVerified = $kycStatus === 'verified'; @endphp

    {{-- KYC Alert (not verified) --}}
    @if($kycStatus === 'unverified')
        <div class="mb-6 bg-red-50 border border-red-200 p-4 sm:p-5">
            <div class="flex items-start gap-3">
                <svg class="w-6 h-6 text-red-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                </svg>
                <div class="flex-1 min-w-0">
                    <p class="font-semibold text-red-800 text-sm sm:text-base">KYC verification required to continue</p>
                    <p class="text-red-600 text-sm mt-1">Please complete your KYC (Know Your Customer) information before you can start fundraising. This helps us verify your identity.</p>
                    <a href="{{ route('profile.edit', ['tab' => 'kyc']) }}" class="mt-3 inline-flex items-center gap-1.5 text-sm font-medium text-white bg-red-600 hover:bg-red-700 px-4 py-2 rounded transition">
                        Complete KYC Now
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </a>
                </div>
            </div>
        </div>
    @elseif($kycStatus === 'pending')
        <div class="mb-6 bg-yellow-50 border border-yellow-200 p-4 sm:p-5">
            <div class="flex items-start gap-3">
                <svg class="w-6 h-6 text-yellow-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <div class="flex-1 min-w-0">
                    <p class="font-semibold text-yellow-800 text-sm sm:text-base">KYC documents under review</p>
                    <p class="text-yellow-700 text-sm mt-1">Your KYC information has been submitted and is awaiting admin review. You'll be able to start fundraising once verified.</p>
                </div>
            </div>
        </div>
    @elseif($kycStatus === 'rejected')
        <div class="mb-6 bg-red-50 border border-red-200 p-4 sm:p-5">
            <div class="flex items-start gap-3">
                <svg class="w-6 h-6 text-red-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                </svg>
                <div class="flex-1 min-w-0">
                    <p class="font-semibold text-red-800 text-sm sm:text-base">KYC verification rejected</p>
                    <p class="text-red-600 text-sm mt-1">Your submitted KYC information was not approved. Please update your details and resubmit for review.</p>
                    <a href="{{ route('profile.edit', ['tab' => 'kyc']) }}" class="mt-3 inline-flex items-center gap-1.5 text-sm font-medium text-white bg-red-600 hover:bg-red-700 px-4 py-2 rounded-lg transition">
                        Update KYC
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </a>
                </div>
            </div>
        </div>
    @endif

    {{-- Draft Campaigns Alert --}}
    @if($draft_campaigns->count() > 0 && $kycVerified)
        <a href="{{ route('campaigns.my') }}" class="block mb-6 bg-amber-50 border border-amber-200 p-4 sm:p-5 hover:bg-amber-100 transition cursor-pointer">
            <div class="flex items-start gap-3">
                <svg class="w-6 h-6 text-amber-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
                <div class="flex-1 min-w-0">
                    <p class="font-semibold text-amber-800 text-sm sm:text-base">
                        You have {{ $draft_campaigns->count() }} campaign{{ $draft_campaigns->count() > 1 ? 's' : '' }} in draft
                    </p>
                    <p class="text-amber-700 text-sm mt-1">Complete the details and submit for admin review to get your fundraiser live.</p>
                    <div class="mt-3 flex flex-wrap gap-2">
                        @foreach($draft_campaigns as $draft)
                            <span onclick="event.stopPropagation(); window.location='{{ route('campaigns.edit', $draft) }}'" class="inline-flex items-center gap-1.5 text-sm font-medium text-white bg-amber-600 hover:bg-amber-700 px-3.5 py-1.5 rounded-lg transition cursor-pointer">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                                {{ $draft->title }}
                            </span>
                        @endforeach
                    </div>
                </div>
            </div>
        </a>
    @endif

    {{-- Stats Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 lg:gap-6 mb-6">
        <div class="bg-white border border-gray-200 p-5">
            <div class="flex items-center justify-between mb-3">
                <span class="text-sm font-medium text-gray-500">My Campaigns</span>
                <svg class="w-8 h-8 text-[#CE5F26]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                </svg>
            </div>
            <p class="text-2xl font-bold text-gray-900">{{ $stats['my_campaigns'] }}</p>
            <p class="text-xs text-gray-400 mt-1">{{ $stats['active_campaigns'] }} active</p>
        </div>

        <div class="bg-white border border-gray-200 p-5">
            <div class="flex items-center justify-between mb-3">
                <span class="text-sm font-medium text-gray-500">Total Raised</span>
                <svg class="w-8 h-8 text-[#CE5F26]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <p class="text-2xl font-bold text-gray-900">KES {{ number_format($stats['total_raised'], 0) }}</p>
            <p class="text-xs text-gray-400 mt-1">Across all campaigns</p>
        </div>

        <div class="bg-white border border-gray-200 p-5">
            <div class="flex items-center justify-between mb-3">
                <span class="text-sm font-medium text-gray-500">My Donations</span>
                <svg class="w-8 h-8 text-[#CE5F26]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                </svg>
            </div>
            <p class="text-2xl font-bold text-gray-900">{{ $stats['my_donations'] }}</p>
            <p class="text-xs text-gray-400 mt-1">Donations made</p>
        </div>

        <div class="bg-white border border-gray-200 p-5">
            <div class="flex items-center justify-between mb-3">
                <span class="text-sm font-medium text-gray-500">Pending Verification</span>
                <svg class="w-8 h-8 text-[#CE5F26]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <p class="text-2xl font-bold text-gray-900">{{ $stats['pending_verification'] }}</p>
            <p class="text-xs text-gray-400 mt-1">Awaiting review</p>
        </div>
    </div>

    {{-- Charts Row --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 lg:gap-6 mb-6">
        {{-- Monthly Donations Chart --}}
        <div class="bg-white border border-gray-200 p-5">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-semibold text-gray-800">Monthly Donations Received</h3>
                <span class="text-xs text-gray-400">Last 6 months</span>
            </div>
            @if($monthly_raised->count() > 0)
                <div class="h-52">
                    <canvas id="monthlyChart"></canvas>
                </div>
            @else
                <div class="flex flex-col items-center justify-center py-12 text-gray-400">
                    <svg class="w-12 h-12 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                    <p class="text-sm font-medium">No donation data yet</p>
                    <p class="text-xs mt-1">Donations will appear here once received</p>
                </div>
            @endif
        </div>

        {{-- Top Campaigns --}}
        <div class="bg-white border border-gray-200 p-5">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-semibold text-gray-800">Top Campaigns</h3>
                <span class="text-xs text-gray-400">By amount raised</span>
            </div>
            @if($top_campaigns->count() > 0)
                <div class="h-52">
                    <canvas id="topCampaignsChart"></canvas>
                </div>
            @else
                <div class="flex flex-col items-center justify-center py-12 text-gray-400">
                    <svg class="w-12 h-12 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                    </svg>
                    <p class="text-sm font-medium">No campaigns yet</p>
                </div>
            @endif
        </div>

        {{-- Campaign Status Breakdown --}}
        <div class="bg-white border border-gray-200 p-5">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-semibold text-gray-800">Campaign Status</h3>
                <span class="text-xs text-gray-400">{{ $stats['my_campaigns'] }} total</span>
            </div>
            @if($stats['my_campaigns'] > 0)
                <div class="h-52">
                    <canvas id="statusChart"></canvas>
                </div>
            @else
                <div class="flex flex-col items-center justify-center py-12 text-gray-400">
                    <svg class="w-12 h-12 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                    </svg>
                    <p class="text-sm font-medium">No campaigns yet</p>
                    <a href="{{ route('campaigns.create') }}" class="text-xs text-[#CE5F26] hover:text-[#B04E1E] font-medium mt-1">Start your first fundraiser</a>
                </div>
            @endif
        </div>
    </div>

    {{-- Recent Activity --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 lg:gap-6">
        {{-- My Campaigns --}}
        <div class="bg-white border border-gray-200 overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
                <h3 class="font-semibold text-gray-800">My Campaigns</h3>
                <a href="{{ route('campaigns.create') }}" class="text-xs font-medium text-[#CE5F26] hover:text-[#B04E1E] transition">+ New</a>
            </div>
            <div class="divide-y divide-gray-100">
                @forelse($my_campaigns as $campaign)
                    <div class="px-5 py-3.5 flex items-center gap-3">
                        <div class="w-10 h-10 rounded-lg bg-gray-100 overflow-hidden shrink-0">
                            @if($campaign->getFirstMediaUrl('cover_image'))
                                <img src="{{ $campaign->getFirstMediaUrl('cover_image') }}" alt="" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-xs font-bold text-gray-400">
                                    {{ substr($campaign->title, 0, 2) }}
                                </div>
                            @endif
                        </div>
                        <div class="min-w-0 flex-1">
                            <a href="{{ route('campaigns.show', $campaign) }}" class="text-sm font-medium text-gray-800 hover:text-[#CE5F26] transition truncate block">{{ $campaign->title }}</a>
                            <p class="text-xs text-gray-400">KES {{ number_format($campaign->raised_amount, 0) }} raised</p>
                        </div>
                        <span class="text-xs font-medium px-2 py-0.5 rounded-full shrink-0
                            @if($campaign->status->value === 'active') bg-green-50 text-green-700
                            @elseif($campaign->status->value === 'pending_verification') bg-yellow-50 text-yellow-700
                            @elseif($campaign->status->value === 'draft') bg-gray-100 text-gray-600
                            @else bg-gray-100 text-gray-500 @endif">
                            {{ str_replace('_', ' ', ucfirst($campaign->status->value)) }}
                        </span>
                    </div>
                @empty
                    <div class="px-5 py-10 text-center text-sm text-gray-400">
                        <p class="font-medium">No campaigns yet</p>
                        <a href="{{ route('campaigns.create') }}" class="text-[#CE5F26] hover:text-[#B04E1E] font-medium mt-1 inline-block">Start a fundraiser</a>
                    </div>
                @endforelse
            </div>
        </div>

        {{-- Recent Donations --}}
        <div class="bg-white border border-gray-200 overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
                <h3 class="font-semibold text-gray-800">Recent Donations</h3>
                <span class="text-xs text-gray-400">Latest activity</span>
            </div>
            <div class="divide-y divide-gray-100">
                @forelse($recent_donations as $donation)
                    <div class="px-5 py-3.5 flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full bg-[#CE5F26]/10 flex items-center justify-center text-xs font-bold text-[#CE5F26] shrink-0">
                            {{ substr($donation->donor_name ?? 'A', 0, 1) }}
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="text-sm font-medium text-gray-800 truncate">{{ $donation->donor_name ?? 'Anonymous' }}</p>
                            <p class="text-xs text-gray-400">{{ $donation->campaign->title ?? 'Campaign' }} &middot; {{ $donation->created_at->diffForHumans() }}</p>
                        </div>
                        <div class="text-right shrink-0">
                            <p class="text-sm font-bold text-gray-900">KES {{ number_format($donation->amount, 0) }}</p>
                            <p class="text-xs text-gray-400">{{ $donation->payment_method }}</p>
                        </div>
                    </div>
                @empty
                    <div class="px-5 py-10 text-center text-sm text-gray-400">
                        <p class="font-medium">No donations yet</p>
                        <p class="text-xs mt-1">Share your campaign to start receiving donations</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    @php
        $months = $monthly_raised->pluck('month')->map(fn($m) => \Carbon\Carbon::parse($m . '-01')->format('M Y'));
        $totals = $monthly_raised->pluck('total')->map(fn($v) => (float) $v);

        $userStatuses = Auth::user()->campaigns()
            ->select('status', \Illuminate\Support\Facades\DB::raw('count(*) as count'))
            ->groupBy('status')
            ->pluck('count', 'status');
        $statusLabels = $userStatuses->keys()->map(fn($s) => str_replace('_', ' ', ucfirst($s)));
        $statusCounts = $userStatuses->values();

        $topCampaignLabels = $top_campaigns->pluck('title');
        $topCampaignData = $top_campaigns->pluck('raised_amount')->map(fn($v) => (float) $v);
    @endphp

    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            @if($monthly_raised->count() > 0)
                const monthlyCtx = document.getElementById('monthlyChart').getContext('2d');
                new Chart(monthlyCtx, {
                    type: 'bar',
                    data: {
                        labels: @json($months),
                        datasets: [{
                            label: 'Amount (KES)',
                            data: @json($totals),
                            backgroundColor: 'rgba(122, 163, 106, 0.7)',
                            borderColor: '#7AA36A',
                            borderWidth: 1,
                            borderRadius: 6
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { legend: { display: false } },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: { callback: function(v) { return 'KES ' + v.toLocaleString(); } },
                                grid: { color: 'rgba(0,0,0,0.05)' }
                            },
                            x: { grid: { display: false } }
                        }
                    }
                });
            @endif

            @if($top_campaigns->count() > 0)
                const topCtx = document.getElementById('topCampaignsChart').getContext('2d');
                new Chart(topCtx, {
                    type: 'bar',
                    data: {
                        labels: @json($topCampaignLabels),
                        datasets: [{
                            label: 'Raised (KES)',
                            data: @json($topCampaignData),
                            backgroundColor: ['#1B2A4A', '#CE5F26', '#7AA36A', '#7B2D2D', '#B48C4A'],
                            borderRadius: 6,
                            barThickness: 20
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { legend: { display: false } },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: { callback: function(v) { return 'KES ' + v.toLocaleString(); } },
                                grid: { color: 'rgba(0,0,0,0.05)' }
                            },
                            x: {
                                grid: { display: false },
                                ticks: { font: { size: 9 } }
                            }
                        }
                    }
                });
            @endif

            @if($stats['my_campaigns'] > 0)
                const statusCtx = document.getElementById('statusChart').getContext('2d');
                new Chart(statusCtx, {
                    type: 'doughnut',
                    data: {
                        labels: @json($statusLabels),
                        datasets: [{
                            data: @json($statusCounts),
                            backgroundColor: ['#7AA36A', '#CE5F26', '#1B2A4A', '#7B2D2D', '#9CA3AF'],
                            borderWidth: 0
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'bottom',
                                labels: { padding: 16, usePointStyle: true, pointStyleWidth: 8, font: { size: 11 } }
                            }
                        },
                        cutout: '65%'
                    }
                });
            @endif
        });
    </script>
</x-dashboard-layout>
