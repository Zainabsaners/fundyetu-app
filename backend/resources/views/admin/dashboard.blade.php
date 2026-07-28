<x-admin-layout title="Dashboard">
    <x-slot name="header">Dashboard</x-slot>

    {{-- Stats Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 lg:gap-6 mb-6">
        <div class="bg-white border border-gray-200 p-5">
            <div class="flex items-center justify-between mb-3">
                <span class="text-sm font-medium text-gray-500">Total Raised</span>
                <svg class="w-8 h-8 text-[#CE5F26]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <p class="text-2xl font-bold text-gray-900">KES {{ number_format($stats['total_raised'], 0) }}</p>
            <p class="text-xs text-gray-400 mt-1">Across {{ $stats['total_campaigns'] }} campaigns</p>
        </div>

        <div class="bg-white border border-gray-200 p-5">
            <div class="flex items-center justify-between mb-3">
                <span class="text-sm font-medium text-gray-500">Active Campaigns</span>
                <svg class="w-8 h-8 text-[#CE5F26]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                </svg>
            </div>
            <p class="text-2xl font-bold text-gray-900">{{ $stats['active_campaigns'] }}</p>
            <p class="text-xs text-gray-400 mt-1">{{ $stats['pending_campaigns'] }} pending verification</p>
        </div>

        <div class="bg-white border border-gray-200 p-5">
            <div class="flex items-center justify-between mb-3">
                <span class="text-sm font-medium text-gray-500">Verified Users</span>
                <svg class="w-8 h-8 text-[#CE5F26]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
            </div>
            <p class="text-2xl font-bold text-gray-900">{{ $stats['verified_users'] }} / {{ $stats['total_users'] }}</p>
            <p class="text-xs text-gray-400 mt-1">{{ $stats['total_users'] - $stats['verified_users'] }} unverified</p>
        </div>

        <div class="bg-white border border-gray-200 p-5">
            <div class="flex items-center justify-between mb-3">
                <span class="text-sm font-medium text-gray-500">Pending Withdrawals</span>
                <svg class="w-8 h-8 text-[#CE5F26]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <p class="text-2xl font-bold text-gray-900">{{ $stats['pending_withdrawals'] }}</p>
            <p class="text-xs text-gray-400 mt-1">{{ $stats['total_donations'] }} total donations</p>
        </div>
    </div>

    {{-- Charts Row --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 lg:gap-6 mb-6">
        {{-- Donation Trends --}}
        <div class="bg-white border border-gray-200 p-5">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-semibold text-gray-800">Donation Trends</h3>
                <span class="text-xs text-gray-400">Last 6 months</span>
            </div>
            <div class="h-52">
                <canvas id="donationChart"></canvas>
            </div>
        </div>

        {{-- Top Campaigns by Contribution --}}
        <div class="bg-white border border-gray-200 p-5">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-semibold text-gray-800">Top Campaigns</h3>
                <span class="text-xs text-gray-400">By amount raised</span>
            </div>
            <div class="h-52">
                <canvas id="topCampaignsChart"></canvas>
            </div>
        </div>

        {{-- Campaign Status Breakdown --}}
        <div class="bg-white border border-gray-200 p-5">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-semibold text-gray-800">Campaign Status</h3>
                <span class="text-xs text-gray-400">{{ $stats['total_campaigns'] }} total</span>
            </div>
            <div class="h-52">
                <canvas id="statusChart"></canvas>
            </div>
        </div>
    </div>

    {{-- Tables Row --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 lg:gap-6">
        {{-- Recent Campaigns --}}
        <div class="bg-white border border-gray-200 overflow-hidden flex flex-col">
            <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
                <h3 class="font-semibold text-gray-800">Recent Campaigns</h3>
                <a href="{{ route('admin.campaigns.index') }}" class="text-xs text-[#CE5F26] hover:text-[#B04E1E] font-medium">View All</a>
            </div>
            <div class="divide-y divide-gray-100">
                @forelse($recent_campaigns as $campaign)
                    <a href="{{ route('admin.campaigns.show', $campaign) }}" class="px-5 py-3.5 flex items-center gap-3 hover:bg-gray-50/50 transition">
                        <div class="w-8 h-8 rounded-lg bg-gray-100 flex items-center justify-center text-xs font-bold text-gray-500 shrink-0">
                            {{ substr($campaign->title, 0, 2) }}
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="text-sm font-medium text-gray-800 truncate">{{ $campaign->title }}</p>
                            <p class="text-xs text-gray-400">{{ $campaign->user->name }} &middot; {{ $campaign->created_at->diffForHumans() }}</p>
                        </div>
                        <span class="text-xs font-medium px-2 py-0.5 rounded-full
                            @if($campaign->status->value === 'active') bg-green-50 text-green-700
                            @elseif($campaign->status->value === 'pending_verification') bg-yellow-50 text-yellow-700
                            @else bg-gray-100 text-gray-600 @endif">
                            {{ str_replace('_', ' ', ucfirst($campaign->status->value)) }}
                        </span>
                    </a>
                @empty
                    <div class="px-5 py-8 text-center text-sm text-gray-400">No campaigns yet</div>
                @endforelse
            </div>
        </div>

        {{-- Donor Growth --}}
        <div class="bg-white border border-gray-200 p-5 flex flex-col">
            <div class="flex items-center justify-between mb-4 shrink-0">
                <h3 class="font-semibold text-gray-800">Donor Growth</h3>
                <span class="text-xs text-gray-400">New donors / month</span>
            </div>
            <div class="flex-1 min-h-0">
                <canvas id="donorGrowthChart"></canvas>
            </div>
        </div>

        {{-- Top Campaigns --}}
        <div class="bg-white border border-gray-200 overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
                <h3 class="font-semibold text-gray-800">Top Fundraisers</h3>
                <span class="text-xs text-gray-400">By amount raised</span>
            </div>
            <div class="divide-y divide-gray-100">
                @forelse($top_campaigns as $campaign)
                    <a href="{{ route('admin.campaigns.show', $campaign) }}" class="px-5 py-3.5 flex items-center gap-3 hover:bg-gray-50/50 transition">
                        <div class="w-8 h-8 rounded-lg bg-[#CE5F26]/10 flex items-center justify-center text-xs font-bold text-[#CE5F26] shrink-0">
                            {{ $loop->iteration }}
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="text-sm font-medium text-gray-800 truncate">{{ $campaign->title }}</p>
                            <p class="text-xs text-gray-400">{{ $campaign->user->name }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm font-bold text-gray-900">KES {{ number_format($campaign->raised_amount, 0) }}</p>
                            <p class="text-xs text-gray-400">{{ $campaign->progressPercent() }}% of KES {{ number_format($campaign->target_amount, 0) }}</p>
                        </div>
                    </a>
                @empty
                    <div class="px-5 py-8 text-center text-sm text-gray-400">No active campaigns</div>
                @endforelse
            </div>
        </div>
    </div>

    @php
        $months = $monthly_donations->pluck('month')->map(fn($m) => \Carbon\Carbon::parse($m . '-01')->format('M Y'));
        $totals = $monthly_donations->pluck('total')->map(fn($v) => (float) $v);
        $counts = $monthly_donations->pluck('count');

        $statusLabels = $campaigns_by_status->keys()->map(fn($s) => str_replace('_', ' ', ucfirst($s)));
        $statusCounts = $campaigns_by_status->values();

        $topCampaignLabels = $top_campaigns->pluck('title');
        $topCampaignData = $top_campaigns->pluck('raised_amount')->map(fn($v) => (float) $v);

        $donorGrowthMonths = $donor_growth->pluck('month')->map(fn($m) => \Carbon\Carbon::parse($m . '-01')->format('M Y'));
        $donorGrowthCounts = $donor_growth->pluck('count')->map(fn($v) => (int) $v);
    @endphp

    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Donation Trends Chart
            const donationCtx = document.getElementById('donationChart').getContext('2d');
            new Chart(donationCtx, {
                type: 'line',
                data: {
                    labels: @json($months),
                    datasets: [{
                        label: 'Amount (KES)',
                        data: @json($totals),
                        borderColor: '#7AA36A',
                        backgroundColor: 'rgba(122, 163, 106, 0.1)',
                        fill: true,
                        tension: 0.4,
                        pointRadius: 4,
                        pointBackgroundColor: '#7AA36A',
                        borderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) { return 'KES ' + value.toLocaleString(); }
                            },
                            grid: { color: 'rgba(0,0,0,0.05)' }
                        },
                        x: {
                            grid: { display: false }
                        }
                    }
                }
            });

            // Top Campaigns Bar Chart
            const topCtx = document.getElementById('topCampaignsChart').getContext('2d');
            new Chart(topCtx, {
                type: 'bar',
                data: {
                    labels: @json($topCampaignLabels),
                    datasets: [{
                        label: 'Raised (KES)',
                        data: @json($topCampaignData),
                        backgroundColor: ['#1b2a4a', '#CE5F26', '#7AA36A', '#7B2D2D', '#B48C4A'],
                        borderRadius: 0,
                        barThickness: 20
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) { return 'KES ' + value.toLocaleString(); }
                            },
                            grid: { color: 'rgba(0,0,0,0.05)' }
                        },
                        x: {
                            grid: { display: false },
                            ticks: {
                                font: { size: 9 }
                            }
                        }
                    }
                }
            });

            // Campaign Status Chart
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
                            labels: {
                                padding: 16,
                                usePointStyle: true,
                                pointStyleWidth: 8,
                                font: { size: 11 }
                            }
                        }
                    },
                    cutout: '65%'
                }
            });

            // Donor Growth Chart
            const donorCtx = document.getElementById('donorGrowthChart').getContext('2d');
            new Chart(donorCtx, {
                type: 'line',
                data: {
                    labels: @json($donorGrowthMonths),
                    datasets: [{
                        label: 'New Donors',
                        data: @json($donorGrowthCounts),
                        borderColor: '#1B2A4A',
                        backgroundColor: 'rgba(27, 42, 74, 0.1)',
                        fill: true,
                        tension: 0.4,
                        pointRadius: 4,
                        pointBackgroundColor: '#1B2A4A',
                        borderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1
                            },
                            grid: { color: 'rgba(0,0,0,0.05)' }
                        },
                        x: {
                            grid: { display: false }
                        }
                    }
                }
            });
        });
    </script>
</x-admin-layout>
