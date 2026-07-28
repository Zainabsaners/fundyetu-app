<x-dashboard-layout title="Withdraw Funds">
    <x-slot name="header">Withdraw Funds</x-slot>

    <div class="space-y-6">
        {{-- Campaigns with Balances --}}
        <div class="bg-white border border-gray-200 overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
                <h3 class="font-semibold text-gray-800">Your Campaigns</h3>
                <span class="text-xs text-gray-400">{{ $campaigns->count() }} campaign{{ $campaigns->count() !== 1 ? 's' : '' }}</span>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm border-collapse">
                    <thead>
                        <tr class="bg-gray-50/50">
                            <th class="text-left px-4 py-3 font-semibold text-gray-600 text-xs uppercase tracking-wider border border-gray-200">Campaign</th>
                            <th class="text-left px-4 py-3 font-semibold text-gray-600 text-xs uppercase tracking-wider border border-gray-200">Total Raised (KES)</th>
                            <th class="text-left px-4 py-3 font-semibold text-gray-600 text-xs uppercase tracking-wider border border-gray-200">Approved Withdrawal (KES)</th>
                            <th class="text-left px-4 py-3 font-semibold text-gray-600 text-xs uppercase tracking-wider border border-gray-200">Balance (KES)</th>
                            <th class="text-left px-4 py-3 font-semibold text-gray-600 text-xs uppercase tracking-wider border border-gray-200">Withdrawals</th>
                            <th class="text-left px-4 py-3 font-semibold text-gray-600 text-xs uppercase tracking-wider border border-gray-200"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($campaigns as $campaign)
                            <tr class="hover:bg-gray-50/50 transition">
                                <td class="px-4 py-3 border border-gray-200 font-medium text-gray-800">{{ $campaign->title }}</td>
                                <td class="px-4 py-3 border border-gray-200 font-semibold text-gray-900">KES {{ number_format($campaign->raised_amount, 0) }}</td>
                                <td class="px-4 py-3 border border-gray-200 text-gray-600">KES {{ number_format($campaign->approved_withdrawals_sum ?? 0, 0) }}</td>
                                <td class="px-4 py-3 border border-gray-200 font-semibold {{ $campaign->balance > 0 ? 'text-gray-900' : 'text-gray-400' }}">KES {{ number_format($campaign->balance, 0) }}</td>
                                <td class="px-4 py-3 border border-gray-200 text-sm">
                                    <a href="{{ route('campaigns.withdrawals', $campaign) }}" class="text-[#CE5F26] hover:text-[#B04E1E] font-semibold">{{ $campaign->withdrawals_count }}</a>
                                </td>
                                <td class="px-4 py-3 text-left border border-gray-200 whitespace-nowrap">
                                    <div class="flex items-center justify-start gap-1.5">
                                        @if($campaign->balance > 0)
                                            <a href="{{ route('campaigns.withdrawals', $campaign) }}"
                                               class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-semibold text-white transition shadow-sm" style="background-color: #CE5F26;">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                                </svg>
                                                Withdraw
                                            </a>
                                        @else
                                            <span class="text-xs text-gray-400">No funds available</span>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="p-6 text-center text-sm text-gray-500 border border-gray-200">Campaigns with available funds will show here. All withdrawals require processing and approval.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Withdrawal History --}}
        <div class="bg-white border border-gray-200 overflow-hidden">
            <div class="px-5 py-4 flex items-center justify-between" style="background-color: #1B2A4A;">
                <h3 class="font-semibold text-white">Withdrawal Requests</h3>
            </div>
            <div class="overflow-x-auto">
                    <table id="withdrawalsTable" class="w-full text-sm border-collapse">
                        <thead>
                            <tr class="bg-gray-50/50">
                                <th class="text-center px-3 py-3 font-semibold text-gray-600 text-xs uppercase tracking-wider border border-gray-200 w-12">No.</th>
                                <th class="text-left px-4 py-3 font-semibold text-gray-600 text-xs uppercase tracking-wider border border-gray-200">Campaign</th>
                                <th class="text-left px-4 py-3 font-semibold text-gray-600 text-xs uppercase tracking-wider border border-gray-200">Date</th>
                                <th class="text-left px-4 py-3 font-semibold text-gray-600 text-xs uppercase tracking-wider border border-gray-200">Amount (KES)</th>
                                <th class="text-left px-4 py-3 font-semibold text-gray-600 text-xs uppercase tracking-wider border border-gray-200">Platform Fee (KES)</th>
                                <th class="text-left px-4 py-3 font-semibold text-gray-600 text-xs uppercase tracking-wider border border-gray-200">SMS (KES)</th>
                                <th class="text-left px-4 py-3 font-semibold text-gray-600 text-xs uppercase tracking-wider border border-gray-200">Withdrawal Fee (KES)</th>
                                <th class="text-left px-4 py-3 font-semibold text-gray-600 text-xs uppercase tracking-wider border border-gray-200">Net Amount (KES)</th>
                                <th class="text-left px-4 py-3 font-semibold text-gray-600 text-xs uppercase tracking-wider border border-gray-200">Destination</th>
                                <th class="text-left px-4 py-3 font-semibold text-gray-600 text-xs uppercase tracking-wider border border-gray-200">Status</th>
                                <th class="text-center px-3 py-3 font-semibold text-gray-600 text-xs uppercase tracking-wider border border-gray-200 w-20">Evidence</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($allWithdrawals as $withdrawal)
                                <tr class="hover:bg-gray-50/50 transition">
                                    <td class="px-3 py-3 text-center text-gray-500 border border-gray-200">{{ $loop->iteration }}</td>
                                    <td class="px-4 py-3 border border-gray-200">
                                        <a href="{{ route('campaigns.show', $withdrawal->campaign) }}" class="font-medium text-gray-800 hover:text-[#CE5F26] truncate block max-w-[200px]">
                                            {{ $withdrawal->campaign->title }}
                                        </a>
                                    </td>
                                    <td class="px-4 py-3 text-gray-500 border border-gray-200 text-xs whitespace-nowrap" data-order="{{ $withdrawal->created_at->timestamp }}">{{ $withdrawal->created_at->format('M j, Y g:i A') }}</td>
                                    <td class="px-4 py-3 border border-gray-200 font-semibold text-gray-900">KES {{ number_format($withdrawal->amount, 0) }}</td>
                                    <td class="px-4 py-3 border border-gray-200 text-gray-600">KES {{ number_format($withdrawal->platform_fee, 0) }}</td>
                                    <td class="px-4 py-3 border border-gray-200 text-gray-600">KES {{ number_format($withdrawal->sms_charge, 0) }}</td>
                                    <td class="px-4 py-3 border border-gray-200 text-gray-600">KES {{ number_format($withdrawal->fee, 0) }}</td>
                                    <td class="px-4 py-3 border border-gray-200 font-semibold text-green-700">KES {{ number_format($withdrawal->net_amount, 0) }}</td>
                                    <td class="px-4 py-3 text-gray-600 border border-gray-200 text-xs">{{ strtoupper($withdrawal->destination_type) }}<br>{{ $withdrawal->destination_ref }}</td>
                                    <td class="px-4 py-3 border border-gray-200">
                                        <span class="px-2 py-0.5 text-xs font-semibold
                                            @if(in_array($withdrawal->status->value, ['disbursed', 'admin_approved'])) bg-green-50 text-green-700
                                            @elseif($withdrawal->status->value === 'rejected') bg-red-50 text-red-700
                                            @else bg-yellow-50 text-yellow-700 @endif">
                                            {{ str_replace('_', ' ', ucfirst($withdrawal->status->value)) }}
                                        </span>
                                        @if($withdrawal->disbursed_at)
                                            <div class="text-[10px] text-gray-400 mt-0.5">{{ $withdrawal->disbursed_at->format('M j, Y g:i A') }}</div>
                                        @elseif($withdrawal->rejected_at)
                                            <div class="text-[10px] text-gray-400 mt-0.5">{{ $withdrawal->rejected_at->format('M j, Y g:i A') }}</div>
                                        @endif
                                    </td>
                                    <td class="px-3 py-3 text-center border border-gray-200">
                                        @if($withdrawal->status->value === 'disbursed' && $withdrawal->evidence)
                                            @php $ev = json_decode($withdrawal->evidence, true); @endphp
                                            @if($ev && isset($ev['transaction_id']))
                                                <button type="button" onclick="openEvidence('{{ $ev['conversation_id'] }}', '{{ $ev['result_desc'] ?? $ev['response_description'] ?? '' }}', '{{ $ev['completed_at'] ?? $ev['disbursed_at'] ?? '' }}', '{{ $ev['transaction_id'] }}')" class="text-xs font-mono text-green-700 hover:text-[#CE5F26]" title="{{ $ev['result_desc'] ?? '' }}">{{ $ev['transaction_id'] }}</button>
                                            @elseif($ev && isset($ev['conversation_id']))
                                                <button type="button" onclick="openEvidence('{{ $ev['conversation_id'] }}', '{{ $ev['response_description'] ?? '' }}', '{{ $ev['disbursed_at'] ?? '' }}', '')" class="text-xs font-mono text-green-700 hover:text-[#CE5F26]" title="{{ $ev['response_description'] ?? '' }}">{{ $ev['conversation_id'] }}</button>
                                            @else
                                                <span class="text-gray-300 text-xs">—</span>
                                            @endif
                                        @else
                                            <span class="text-gray-300 text-xs">—</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
            </div>
        </div>
    </div>

    <link rel="stylesheet" href="https://cdn.datatables.net/2.2.2/css/dataTables.dataTables.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.dataTables.css">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/2.2.2/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.js"></script>
    {{-- M-Pesa Evidence Modal --}}
    <div id="evidenceModal" class="fixed inset-0 z-50 hidden bg-black/40 flex items-center justify-center" onclick="if(event.target===this)closeEvidence()">
        <div class="bg-white shadow-lg w-full max-w-md mx-4 p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-semibold text-lg" style="color: #1B2A4A;">M-Pesa B2C Disbursement</h3>
                <button type="button" onclick="closeEvidence()" class="text-gray-400 hover:text-gray-600 text-xl leading-none">&times;</button>
            </div>
            <div class="space-y-3 text-sm">
                <div class="flex justify-between p-3 bg-gray-50 border border-gray-200">
                    <span class="text-gray-600">M-Pesa Receipt</span>
                    <span class="font-semibold text-green-700" id="evReceipt">—</span>
                </div>
                <div class="flex justify-between p-3 bg-gray-50 border border-gray-200">
                    <span class="text-gray-600">Conversation ID</span>
                    <span class="font-semibold text-gray-900" id="evConversationId">—</span>
                </div>
                <div class="flex justify-between p-3 bg-gray-50 border border-gray-200">
                    <span class="text-gray-600">Response</span>
                    <span class="text-gray-800" id="evResponseDesc">—</span>
                </div>
                <div class="flex justify-between p-3 bg-gray-50 border border-gray-200">
                    <span class="text-gray-600">Completed At</span>
                    <span class="text-gray-800" id="evDisbursedAt">—</span>
                </div>
            </div>
            <div class="flex justify-end mt-4">
                <button type="button" onclick="closeEvidence()" class="px-4 py-2 text-sm font-medium text-gray-600 bg-gray-100 hover:bg-gray-200 transition">Close</button>
            </div>
        </div>
    </div>

    <script>
        function openEvidence(conversationId, responseDesc, disbursedAt, receipt) {
            document.getElementById('evReceipt').textContent = receipt || '—';
            document.getElementById('evConversationId').textContent = conversationId || '—';
            document.getElementById('evResponseDesc').textContent = responseDesc || '—';
            document.getElementById('evDisbursedAt').textContent = disbursedAt || '—';
            document.getElementById('evidenceModal').classList.remove('hidden');
        }
        function closeEvidence() {
            document.getElementById('evidenceModal').classList.add('hidden');
        }
        $(document).ready(function () {
            $('#withdrawalsTable').DataTable({
                paging: true,
                pageLength: 12,
                lengthChange: false,
                info: true,
                ordering: true,
                order: [[2, 'desc']],
                language: {
                    search: '',
                    searchPlaceholder: 'Search withdrawals...',
                    emptyTable: 'No withdrawals found'
                },
                dom: '<"mb-3 flex items-center justify-between"<"flex items-center gap-2"B><f>>tip',
                buttons: [
                    {
                        extend: 'excelHtml5',
                        text: 'Export to Excel',
                        className: 'px-3 py-1.5 text-sm font-medium bg-green-700 text-white hover:bg-green-800 transition border-0',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10]
                        }
                    }
                ],
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
