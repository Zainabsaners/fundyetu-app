<x-admin-layout title="Reports">
    <x-slot name="header">Analytics</x-slot>

    {{-- Summary Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 lg:gap-6 mb-6">
        <div class="bg-white border border-gray-200 p-5">
            <div class="flex items-center justify-between mb-3">
                <span class="text-sm font-medium text-gray-500">Platform Earnings</span>
                <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <p class="text-2xl font-bold text-gray-900">KES {{ number_format($total_platform_fees, 0) }}</p>
            <p class="text-xs text-gray-400 mt-1">From {{ number_format($total_withdrawals) }} disbursed withdrawals</p>
        </div>

        <div class="bg-white border border-gray-200 p-5">
            <div class="flex items-center justify-between mb-3">
                <span class="text-sm font-medium text-gray-500">SMS Costs</span>
                <svg class="w-8 h-8 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
                </svg>
            </div>
            <p class="text-2xl font-bold text-gray-900">KES {{ number_format($total_sms_costs, 0) }}</p>
            <p class="text-xs text-gray-400 mt-1">{{ number_format($total_withdrawals) }} withdrawal notifications</p>
        </div>

        <div class="bg-white border border-gray-200 p-5">
            <div class="flex items-center justify-between mb-3">
                <span class="text-sm font-medium text-gray-500">Net Earnings</span>
                <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                </svg>
            </div>
            <p class="text-2xl font-bold {{ $net_earnings >= 0 ? 'text-green-600' : 'text-red-600' }}">KES {{ number_format($net_earnings, 0) }}</p>
            <p class="text-xs text-gray-400 mt-1">Platform fees minus SMS costs</p>
        </div>

        <div class="bg-white border border-gray-200 p-5">
            <div class="flex items-center justify-between mb-3">
                <span class="text-sm font-medium text-gray-500">Total Disbursed</span>
                <svg class="w-8 h-8 text-[#1B2A4A]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
            </div>
            <p class="text-2xl font-bold text-gray-900">KES {{ number_format($total_disbursed, 0) }}</p>
            <p class="text-xs text-gray-400 mt-1">To fundraisers across {{ number_format($total_withdrawals) }} withdrawals</p>
        </div>
    </div>

    {{-- Monthly Chart --}}
    <div class="bg-white border border-gray-200 p-5 mb-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="font-semibold text-gray-800">Monthly Revenue Breakdown</h3>
            <span class="text-xs text-gray-400">Last 12 months</span>
        </div>
        <div class="h-72">
            <canvas id="revenueChart"></canvas>
        </div>
    </div>

    {{-- Recent Withdrawals Table --}}
    <div class="bg-white border border-gray-200 overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-100">
            <h3 class="font-semibold text-gray-800">Recent Disbursed Withdrawals</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm border-collapse">
                <thead>
                    <tr class="bg-gray-50/50">
                        <th class="text-left px-4 py-3 font-semibold text-gray-600 text-xs uppercase tracking-wider border border-gray-200">Campaign</th>
                        <th class="text-right px-4 py-3 font-semibold text-gray-600 text-xs uppercase tracking-wider border border-gray-200">Amount</th>
                        <th class="text-right px-4 py-3 font-semibold text-gray-600 text-xs uppercase tracking-wider border border-gray-200">Platform Fee</th>
                        <th class="text-right px-4 py-3 font-semibold text-gray-600 text-xs uppercase tracking-wider border border-gray-200">SMS Cost</th>
                        <th class="text-left px-4 py-3 font-semibold text-gray-600 text-xs uppercase tracking-wider border border-gray-200">Disbursed</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recent as $withdrawal)
                        <tr class="hover:bg-gray-50/50 transition">
                            <td class="px-4 py-3 border border-gray-200">
                                <div class="font-medium text-gray-800 truncate max-w-xs">{{ $withdrawal->campaign?->title ?? 'N/A' }}</div>
                            </td>
                            <td class="px-4 py-3 text-right border border-gray-200 font-medium text-gray-900">KES {{ number_format($withdrawal->amount, 0) }}</td>
                            <td class="px-4 py-3 text-right border border-gray-200 text-gray-600">KES {{ number_format($withdrawal->platform_fee, 2) }}</td>
                            <td class="px-4 py-3 text-right border border-gray-200 text-gray-600">KES {{ number_format($withdrawal->sms_charge, 2) }}</td>
                            <td class="px-4 py-3 border border-gray-200 text-xs text-gray-500 whitespace-nowrap">{{ $withdrawal->disbursed_at?->format('d M Y g:i A') ?? '—' }}</td>
                        </tr>
                    @endforeach
                    @if($recent->isEmpty())
                        <tr>
                            <td colspan="5" class="px-4 py-8 text-center text-sm text-gray-400">No withdrawals disbursed yet</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const months = @json($monthly->pluck('month')->map(fn($m) => \Carbon\Carbon::parse($m . '-01')->format('M Y')));
        const platformFees = @json($monthly->pluck('platform_fees')->map(fn($v) => (float) $v));
        const smsCosts = @json($monthly->pluck('sms_costs')->map(fn($v) => (float) $v));

        new Chart(document.getElementById('revenueChart'), {
            type: 'bar',
            data: {
                labels: months,
                datasets: [
                    {
                        label: 'Platform Fees',
                        data: platformFees,
                        backgroundColor: '#16a34a',
                        borderRadius: 0,
                        barPercentage: 0.4,
                    },
                    {
                        label: 'SMS Costs',
                        data: smsCosts,
                        backgroundColor: '#f97316',
                        borderRadius: 0,
                        barPercentage: 0.4,
                    },
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top',
                        labels: { boxWidth: 12, padding: 16, font: { size: 12 } }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: v => 'KES ' + v.toLocaleString()
                        },
                        grid: { color: '#f0f0f0' }
                    },
                    x: {
                        grid: { display: false }
                    }
                }
            }
        });
    </script>
</x-admin-layout>