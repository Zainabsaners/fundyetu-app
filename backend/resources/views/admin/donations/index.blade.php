<x-admin-layout title="Donations">
    <x-slot name="header">Donations</x-slot>

    <div class="bg-white border border-gray-200 overflow-hidden">
        <div class="p-4 border-b border-gray-100 flex flex-wrap gap-2 items-center">
            @foreach($paymentMethods as $pm)
                <a href="{{ route('admin.donations.index', array_filter(['payment_method' => $pm, 'status' => request('status')])) }}"
                   class="px-3 py-1.5 text-sm font-medium transition @if(request('payment_method') === $pm) bg-[#1B2A4A] text-white @else bg-gray-100 text-gray-600 hover:bg-gray-200 @endif">
                    {{ ucfirst($pm) }}
                </a>
            @endforeach
        </div>

        <div class="overflow-x-auto">
            <table id="adminDonationsTable" class="w-full text-sm border-collapse">
                <thead>
                    <tr class="bg-gray-50/50">
                        <th class="text-center px-3 py-3 font-semibold text-gray-600 text-xs uppercase tracking-wider border border-gray-200 w-12">No.</th>
                        <th class="text-left px-4 py-3 font-semibold text-gray-600 text-xs uppercase tracking-wider border border-gray-200">Donor</th>
                        <th class="text-left px-4 py-3 font-semibold text-gray-600 text-xs uppercase tracking-wider border border-gray-200">Campaign</th>
                        <th class="text-right px-4 py-3 font-semibold text-gray-600 text-xs uppercase tracking-wider border border-gray-200">Amount (KES)</th>
                        <th class="text-center px-4 py-3 font-semibold text-gray-600 text-xs uppercase tracking-wider border border-gray-200">Payment</th>
                        <th class="text-left px-4 py-3 font-semibold text-gray-600 text-xs uppercase tracking-wider border border-gray-200">Ref</th>
                        <th class="text-center px-4 py-3 font-semibold text-gray-600 text-xs uppercase tracking-wider border border-gray-200">Status</th>
                        <th class="text-left px-4 py-3 font-semibold text-gray-600 text-xs uppercase tracking-wider border border-gray-200">Date</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($donations as $donation)
                        <tr class="hover:bg-gray-50/50 transition">
                            <td class="px-3 py-3 text-center text-gray-500 border border-gray-200">{{ $loop->iteration }}</td>
                            <td class="px-4 py-3 border border-gray-200">
                                <div class="font-medium text-gray-800">{{ $donation->donor_name ?? $donation->user?->name ?? 'Anonymous' }}</div>
                                <div class="text-xs text-gray-400">{{ $donation->donor_phone ?? $donation->user?->phone }}</div>
                            </td>
                            <td class="px-4 py-3 border border-gray-200 max-w-[160px]">
                                <a href="{{ route('admin.campaigns.show', $donation->campaign) }}" class="text-[#CE5F26] hover:text-[#B04E1E] transition text-xs font-medium truncate block">{{ $donation->campaign?->title ?? 'N/A' }}</a>
                            </td>
                            <td class="px-4 py-3 text-right border border-gray-200 font-semibold text-gray-900">KES {{ number_format($donation->amount, 2) }}</td>
                            <td class="px-4 py-3 text-center border border-gray-200">
                                <span class="px-2 py-0.5 text-xs font-medium bg-gray-100 text-gray-600">{{ strtoupper($donation->payment_method ?? '—') }}</span>
                            </td>
                            <td class="px-4 py-3 border border-gray-200 font-mono text-xs text-gray-500">{{ $donation->payment_ref ?? '—' }}</td>
                            <td class="px-4 py-3 text-center border border-gray-200">
                                <span class="px-2.5 py-1 text-xs font-semibold
                                    @if($donation->status->value === 'completed') bg-green-50 text-green-700
                                    @elseif($donation->status->value === 'pending') bg-yellow-50 text-yellow-700
                                    @elseif($donation->status->value === 'failed') bg-red-50 text-red-700
                                    @else bg-gray-100 text-gray-600 @endif">
                                    {{ ucfirst($donation->status->value) }}
                                </span>
                            </td>
                            <td class="px-4 py-3 border border-gray-200 text-xs text-gray-500 whitespace-nowrap">{{ $donation->created_at->format('d M Y g:i A') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <link rel="stylesheet" href="https://cdn.datatables.net/2.2.2/css/dataTables.dataTables.css">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/2.2.2/js/dataTables.js"></script>
    <script>
        $(document).ready(function () {
            $('#adminDonationsTable').DataTable({
                paging: true,
                pageLength: 12,
                lengthChange: false,
                info: true,
                ordering: true,
                order: [],
                language: {
                    search: '',
                    searchPlaceholder: 'Search donors...',
                    emptyTable: 'No donations found'
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
</x-admin-layout>
