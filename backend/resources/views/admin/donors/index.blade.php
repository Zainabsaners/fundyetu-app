<x-admin-layout title="Donors">
    <x-slot name="header">Donors</x-slot>

    <div class="bg-white border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table id="adminDonorsTable" class="w-full text-sm border-collapse">
                <thead>
                    <tr class="bg-gray-50/50">
                        <th class="text-center px-3 py-3 font-semibold text-gray-600 text-xs uppercase tracking-wider border border-gray-200 w-12">No.</th>
                        <th class="text-left px-4 py-3 font-semibold text-gray-600 text-xs uppercase tracking-wider border border-gray-200">Donor</th>
                        <th class="text-left px-4 py-3 font-semibold text-gray-600 text-xs uppercase tracking-wider border border-gray-200">Phone</th>
                        <th class="text-center px-4 py-3 font-semibold text-gray-600 text-xs uppercase tracking-wider border border-gray-200">Donations</th>
                        <th class="text-right px-4 py-3 font-semibold text-gray-600 text-xs uppercase tracking-wider border border-gray-200">Total Donated (KES)</th>
                        <th class="text-left px-4 py-3 font-semibold text-gray-600 text-xs uppercase tracking-wider border border-gray-200">First Donation</th>
                        <th class="text-left px-4 py-3 font-semibold text-gray-600 text-xs uppercase tracking-wider border border-gray-200">Last Donation</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($donors as $donor)
                        <tr class="hover:bg-gray-50/50 transition">
                            <td class="px-3 py-3 text-center text-gray-500 border border-gray-200">{{ $loop->iteration }}</td>
                            <td class="px-4 py-3 border border-gray-200">
                                <div class="font-medium text-gray-800">{{ $donor->donor_name ?: 'Anonymous' }}</div>
                            </td>
                            <td class="px-4 py-3 border border-gray-200 text-gray-600 font-mono text-xs">{{ $donor->donor_phone ?? '—' }}</td>
                            <td class="px-4 py-3 text-center border border-gray-200 font-medium text-gray-900">{{ number_format($donor->total_donations) }}</td>
                            <td class="px-4 py-3 text-right border border-gray-200 font-semibold text-gray-900">KES {{ number_format($donor->total_amount, 0) }}</td>
                            <td class="px-4 py-3 border border-gray-200 text-xs text-gray-500 whitespace-nowrap">{{ \Carbon\Carbon::parse($donor->first_donation)->format('d M Y g:i A') }}</td>
                            <td class="px-4 py-3 border border-gray-200 text-xs text-gray-500 whitespace-nowrap">{{ \Carbon\Carbon::parse($donor->last_donation)->format('d M Y g:i A') }}</td>
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
            $('#adminDonorsTable').DataTable({
                paging: true,
                pageLength: 12,
                lengthChange: false,
                info: true,
                ordering: true,
                order: [],
                language: {
                    search: '',
                    searchPlaceholder: 'Search donors...',
                    emptyTable: 'No donors found'
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
