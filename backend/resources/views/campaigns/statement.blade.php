<x-dashboard-layout title="Report">
    <x-slot name="header">Report: {{ $campaign->title }} <span class="text-sm font-normal text-gray-400">— Target: KES {{ number_format($campaign->target_amount, 0) }}</span></x-slot>

    <div class="space-y-6">
        {{-- Summary Cards --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 lg:gap-6">
            <div class="bg-white border border-gray-200 p-5">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-sm font-medium text-gray-500">Total Raised</span>
                    <svg class="w-8 h-8 text-[#CE5F26]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <p class="text-2xl font-bold text-gray-900">KES {{ number_format($campaign->raised_amount, 0) }}</p>
                <p class="text-xs text-gray-400 mt-1">Total funds raised</p>
            </div>
            <div class="bg-white border border-gray-200 p-5">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-sm font-medium text-gray-500">Donors</span>
                    <svg class="w-8 h-8 text-[#CE5F26]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </div>
                <p class="text-2xl font-bold text-gray-900">{{ $donations->total() }}</p>
                <p class="text-xs text-gray-400 mt-1">Total supporters</p>
            </div>
            <div class="bg-white border border-gray-200 p-5">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-sm font-medium text-gray-500">Approved Withdrawals</span>
                    <svg class="w-8 h-8 text-[#CE5F26]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <p class="text-2xl font-bold text-gray-900">KES {{ number_format($approvedWithdrawals, 0) }}</p>
                <p class="text-xs text-gray-400 mt-1">Disbursed to date</p>
            </div>
            <div class="bg-white border border-gray-200 p-5">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-sm font-medium text-gray-500">Balance</span>
                    <svg class="w-8 h-8 text-[#CE5F26]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <p class="text-2xl font-bold text-gray-900">KES {{ number_format($balance, 0) }}</p>
                <p class="text-xs text-gray-400 mt-1">Remaining available</p>
            </div>
        </div>

        {{-- Donations Table --}}
        <div class="bg-white border border-gray-200 overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100">
                <h3 class="font-semibold text-gray-800">Donations</h3>
            </div>
            <div class="overflow-x-auto">
                <table id="donationsTable" class="w-full text-sm">
                    <thead>
                        <tr class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider bg-gray-50/50">
                            <th class="p-4 border border-gray-200 w-12">No.</th>
                            <th class="p-4 border border-gray-200">Date &amp; Time</th>
                            <th class="p-4 border border-gray-200">Payment Code</th>
                            <th class="p-4 border border-gray-200">Payment Mode</th>
                            <th class="p-4 border border-gray-200">Donor Name</th>
                            <th class="p-4 border border-gray-200">Message</th>
                            <th class="p-4 border border-gray-200 text-left">Amount (KES)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($donations as $donation)
                            <tr class="hover:bg-gray-50/50 transition">
                                <td class="p-4 text-gray-500 text-center border border-gray-200">{{ $loop->iteration }}</td>
                                <td class="p-4 text-gray-500 whitespace-nowrap border border-gray-200" data-order="{{ $donation->created_at->timestamp }}">{{ $donation->created_at->format('M j, Y H:i') }}</td>
                                <td class="p-4 text-gray-600 font-mono text-xs border border-gray-200">{{ $donation->payment_ref ?? '—' }}</td>
                                <td class="p-4 text-gray-600 capitalize border border-gray-200">{{ $donation->payment_method }}</td>
                                <td class="p-4 font-medium text-gray-800 border border-gray-200">{{ $donation->donor_name ?? ($donation->user->name ?? 'Anonymous') }}</td>
                                <td class="p-4 text-gray-600 max-w-[200px] truncate border border-gray-200">{{ $donation->message ?? '—' }}</td>
                                <td class="p-4 text-left font-semibold text-gray-900 border border-gray-200">{{ number_format($donation->amount, 0) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    <link rel="stylesheet" href="https://cdn.datatables.net/2.2.2/css/dataTables.dataTables.css">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/2.2.2/js/dataTables.js"></script>
    <script>
        $(document).ready(function () {
            $('#donationsTable').DataTable({
                paging: true,
                pageLength: 12,
                lengthChange: false,
                info: true,
                ordering: true,
                order: [[1, 'desc']],
                language: {
                    search: '',
                    searchPlaceholder: 'Search donations...',
                    emptyTable: 'No donations yet'
                },
                dom: '<"mb-3 flex items-center justify-between"<"flex items-center gap-2"l><f>>tip',
                initComplete: function () {
                    $('div.dataTables_filter input').addClass('pl-9 pr-3 py-1.5 text-sm border border-gray-200 focus:border-[#CE5F26] focus:ring-1 focus:ring-[#CE5F26] outline-none w-56');
                    $('div.dataTables_filter').before(
                        '<svg class="w-4 h-4 absolute ml-3 mt-2.5 text-gray-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">' +
                        '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>'
                    );
                    $('div.dataTables_filter').css('position', 'relative');
                }
            });
        });
    </script>
</x-dashboard-layout>