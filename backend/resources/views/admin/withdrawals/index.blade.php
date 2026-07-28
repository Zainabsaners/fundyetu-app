<x-admin-layout title="Withdrawals">
    <x-slot name="header">Withdrawals</x-slot>

    @if($errors->any())
        <div class="mb-4">
            @foreach($errors->all() as $error)
                <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 text-sm mb-1">{{ $error }}</div>
            @endforeach
        </div>
    @endif

    <div class="bg-white border border-gray-200 overflow-hidden">
        <div class="p-4 border-b border-gray-100 flex flex-wrap gap-2">
            <a href="{{ route('admin.withdrawals.index') }}" class="px-3 py-1.5 text-sm font-medium transition @if(!request('status')) bg-[#1B2A4A] text-white @else bg-gray-100 text-gray-600 hover:bg-gray-200 @endif">All</a>
            @foreach(['pending', 'treasurer_approved', 'admin_approved', 'disbursed', 'rejected'] as $s)
                <a href="{{ route('admin.withdrawals.index', ['status' => $s]) }}"
                   class="px-3 py-1.5 text-sm font-medium transition @if(request('status') === $s) bg-[#1B2A4A] text-white @else bg-gray-100 text-gray-600 hover:bg-gray-200 @endif">
                    {{ str_replace('_', ' ', ucfirst($s)) }}
                </a>
            @endforeach
        </div>

        <div class="overflow-x-auto">
            <table id="adminWithdrawalsTable" class="w-full text-sm border-collapse">
                <thead>
                    <tr class="bg-gray-50/50">
                        <th class="text-center px-3 py-3 font-semibold text-gray-600 text-xs uppercase tracking-wider border border-gray-200 w-12">No.</th>
                        <th class="text-left px-4 py-3 font-semibold text-gray-600 text-xs uppercase tracking-wider border border-gray-200">Campaign</th>
                        <th class="text-left px-4 py-3 font-semibold text-gray-600 text-xs uppercase tracking-wider border border-gray-200">Owner</th>
                        <th class="text-left px-4 py-3 font-semibold text-gray-600 text-xs uppercase tracking-wider border border-gray-200">Amount (KES)</th>
                        <th class="text-left px-4 py-3 font-semibold text-gray-600 text-xs uppercase tracking-wider border border-gray-200">Platform Fee (KES)</th>
                        <th class="text-left px-4 py-3 font-semibold text-gray-600 text-xs uppercase tracking-wider border border-gray-200">SMS (KES)</th>
                        <th class="text-left px-4 py-3 font-semibold text-gray-600 text-xs uppercase tracking-wider border border-gray-200">Withdrawal Fee (KES)</th>
                        <th class="text-left px-4 py-3 font-semibold text-gray-600 text-xs uppercase tracking-wider border border-gray-200">Net Amount (KES)</th>
                        <th class="text-left px-4 py-3 font-semibold text-gray-600 text-xs uppercase tracking-wider border border-gray-200">Destination</th>
                        <th class="text-center px-4 py-3 font-semibold text-gray-600 text-xs uppercase tracking-wider border border-gray-200">Status</th>
                        <th class="text-left px-4 py-3 font-semibold text-gray-600 text-xs uppercase tracking-wider border border-gray-200">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($withdrawals as $withdrawal)
                        <tr class="hover:bg-gray-50/50 transition">
                            <td class="px-3 py-3 text-center text-gray-500 border border-gray-200">{{ $loop->iteration }}</td>
                            <td class="px-4 py-3 border border-gray-200 max-w-xs">
                                <a href="{{ route('admin.campaigns.show', $withdrawal->campaign) }}" class="font-medium text-[#CE5F26] hover:text-[#B04E1E] block truncate">
                                    {{ $withdrawal->campaign->title }}
                                </a>
                            </td>
                            <td class="px-4 py-3 border border-gray-200">
                                <div class="font-medium text-gray-800">{{ $withdrawal->requester->name }}</div>
                                <div class="text-xs text-gray-400">{{ $withdrawal->requester->phone }}</div>
                            </td>
                            <td class="px-4 py-3 border border-gray-200 font-semibold text-gray-900">KES {{ number_format($withdrawal->amount, 0) }}</td>
                            <td class="px-4 py-3 border border-gray-200 text-gray-600">KES {{ number_format($withdrawal->platform_fee, 0) }}</td>
                            <td class="px-4 py-3 border border-gray-200 text-gray-600">KES {{ number_format($withdrawal->sms_charge, 0) }}</td>
                            <td class="px-4 py-3 border border-gray-200 text-gray-600">KES {{ number_format($withdrawal->fee, 0) }}</td>
                            <td class="px-4 py-3 border border-gray-200 font-semibold text-green-700">KES {{ number_format($withdrawal->net_amount, 0) }}</td>
                            <td class="px-4 py-3 border border-gray-200 text-gray-600 text-xs">{{ strtoupper($withdrawal->destination_type) }}<br>{{ $withdrawal->destination_ref }}</td>
                            <td class="px-4 py-3 text-center border border-gray-200">
                                <span class="px-2.5 py-1 text-xs font-semibold
                                    @if(in_array($withdrawal->status->value, ['disbursed'])) bg-green-50 text-green-700
                                    @elseif($withdrawal->status->value === 'rejected') bg-red-50 text-red-700
                                    @elseif($withdrawal->status->value === 'treasurer_approved') bg-blue-50 text-blue-700
                                    @else bg-yellow-50 text-yellow-700 @endif">
                                    {{ str_replace('_', ' ', ucfirst($withdrawal->status->value)) }}
                                </span>
                                @if($withdrawal->disbursed_at)
                                    <div class="text-[10px] text-gray-400 mt-0.5">{{ $withdrawal->disbursed_at->format('M j, Y g:i A') }}</div>
                                @elseif($withdrawal->rejected_at)
                                    <div class="text-[10px] text-gray-400 mt-0.5">{{ $withdrawal->rejected_at->format('M j, Y g:i A') }}</div>
                                @endif
                            </td>
                            <td class="px-4 py-3 border border-gray-200 whitespace-nowrap">
                                <div class="flex items-center gap-1.5">
                                    <a href="{{ route('admin.withdrawals.invoice', $withdrawal) }}" class="text-[11px] font-medium px-2 py-0.5 text-white transition whitespace-nowrap" style="background-color: #1B2A4A;">Invoice</a>
                                    @php $otpSentFor = session('otp_withdrawal_id'); @endphp
                                    @if(in_array($withdrawal->status->value, ['pending', 'treasurer_approved']))
                                        <button type="button"
                                                data-campaign="{{ $withdrawal->campaign->title }}"
                                                data-amount="{{ number_format($withdrawal->amount, 0) }}"
                                                data-net="{{ number_format($withdrawal->net_amount, 0) }}"
                                                data-dest="{{ strtoupper($withdrawal->destination_type) }} - {{ $withdrawal->destination_ref }}"
                                                data-otp-sent="{{ (int) ($otpSentFor == $withdrawal->id) }}"
                                                onclick="openApproveModal({{ $withdrawal->id }}, this)"
                                                class="text-[11px] font-medium px-2 py-0.5 text-white transition whitespace-nowrap" style="background-color: #7AA36A;">Approve</button>
                                        <button type="button" onclick="openRejectModal({{ $withdrawal->id }})" class="text-[11px] font-medium px-2 py-0.5 text-white transition whitespace-nowrap" style="background-color: #DC2626;">Reject</button>
                                    @endif
                                    @if($withdrawal->status->value === 'disbursed' && $withdrawal->evidence)
                                        @php $ev = json_decode($withdrawal->evidence, true); @endphp
                                        @if($ev && isset($ev['transaction_id']))
                                            <button type="button" onclick="openEvidence('{{ $ev['conversation_id'] }}', '{{ $ev['result_desc'] ?? $ev['response_description'] ?? '' }}', '{{ $ev['completed_at'] ?? $ev['disbursed_at'] ?? '' }}', '{{ $ev['transaction_id'] }}')" class="text-[11px] font-medium px-2 py-0.5 text-white transition whitespace-nowrap" style="background-color: #7AA36A;">{{ $ev['transaction_id'] }}</button>
                                        @elseif($ev && isset($ev['conversation_id']))
                                            <button type="button" onclick="openEvidence('{{ $ev['conversation_id'] }}', '{{ $ev['response_description'] ?? '' }}', '{{ $ev['disbursed_at'] ?? '' }}')" class="text-[11px] font-medium px-2 py-0.5 text-white transition whitespace-nowrap" style="background-color: #6B7280;">{{ $ev['conversation_id'] }}</button>
                                        @elseif($ev && isset($ev['error']))
                                            <span class="text-[11px] text-red-600">Failed</span>
                                        @endif
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- Approve Modal --}}
    <div id="approveModal" class="fixed inset-0 z-50 hidden bg-black/40 flex items-center justify-center" onclick="if(event.target===this)closeApproveModal()">
        <div class="bg-white shadow-lg w-full max-w-md mx-4 p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-semibold text-lg" style="color: #1B2A4A;">Approve Withdrawal</h3>
                <button type="button" onclick="closeApproveModal()" class="text-gray-400 hover:text-gray-600 text-xl leading-none">&times;</button>
            </div>
            <div class="mb-4 p-3 bg-gray-50 border border-gray-200 text-sm space-y-1">
                <div class="flex justify-between"><span class="text-gray-600">Campaign</span><span class="font-semibold text-gray-900 truncate max-w-[200px]" id="approveCampaign">—</span></div>
                <div class="flex justify-between"><span class="text-gray-600">Amount</span><span class="font-semibold text-gray-900" id="approveAmount">KES 0</span></div>
                <div class="flex justify-between"><span class="text-gray-600">Net Amount</span><span class="font-semibold text-green-700" id="approveNet">KES 0</span></div>
                <div class="flex justify-between"><span class="text-gray-600">Destination</span><span class="text-gray-800" id="approveDest">—</span></div>
            </div>
            <div id="otpStep1">
                <p class="text-sm text-gray-600 mb-4">Send a verification code to your phone to confirm the M-Pesa B2C disbursement.</p>
                <form method="POST" id="otpSendForm">
                    @csrf
                    <div class="flex justify-end gap-2">
                        <button type="button" onclick="closeApproveModal()" class="px-4 py-2 text-sm font-medium text-gray-600 bg-gray-100 hover:bg-gray-200 transition">Cancel</button>
                        <button type="submit" class="px-4 py-2 text-sm font-semibold text-white transition" style="background-color: #1B2A4A;">Send OTP</button>
                    </div>
                </form>
            </div>
            <div id="otpStep2" class="hidden">
                <p class="text-sm text-gray-600 mb-4">Enter the verification code sent to your phone to confirm disbursement.</p>
                <form id="approveForm" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Verification Code</label>
                        <input type="text" name="otp" maxlength="6" required
                               class="block w-full border border-gray-300 px-3 py-2.5 text-sm text-center tracking-[0.5em] font-mono focus:border-[#CE5F26] focus:ring-1 focus:ring-[#CE5F26] outline-none"
                               placeholder="000000">
                    </div>
                    <div class="flex justify-end gap-2">
                        <button type="button" onclick="closeApproveModal()" class="px-4 py-2 text-sm font-medium text-gray-600 bg-gray-100 hover:bg-gray-200 transition">Cancel</button>
                        <button type="submit" class="px-4 py-2 text-sm font-semibold text-white transition" style="background-color: #7AA36A;">Confirm &amp; Disburse</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- M-Pesa Evidence Modal --}}
    <div id="evidenceModal" class="fixed inset-0 z-50 hidden bg-black/40 flex items-center justify-center" onclick="if(event.target===this)closeEvidence()">
        <div class="bg-white shadow-lg w-full max-w-md mx-4 p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-semibold text-lg" style="color: #1B2A4A;">M-Pesa B2C Disbursement</h3>
                <button type="button" onclick="closeEvidence()" class="text-gray-400 hover:text-gray-600 text-xl leading-none">&times;</button>
            </div>
            <div class="space-y-3 text-sm" id="evidenceDetails">
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

    {{-- Reject Modal --}}
    <div id="rejectModal" class="fixed inset-0 z-50 hidden bg-black/40 flex items-center justify-center" onclick="if(event.target===this)closeRejectModal()">
        <div class="bg-white shadow-lg w-full max-w-md mx-4 p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-semibold text-lg" style="color: #1B2A4A;">Reject Withdrawal</h3>
                <button type="button" onclick="closeRejectModal()" class="text-gray-400 hover:text-gray-600 text-xl leading-none">&times;</button>
            </div>
            <p class="text-sm text-gray-600 mb-4">Provide a reason for rejection. The user will be notified via email.</p>
            <form id="rejectForm" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Reason for Rejection *</label>
                    <textarea name="rejection_reason" rows="4" required
                              class="block w-full border border-gray-300 px-3 py-2 text-sm focus:border-[#CE5F26] focus:ring-1 focus:ring-[#CE5F26] outline-none"
                              placeholder="Explain why this withdrawal is being rejected..."></textarea>
                </div>
                <div class="flex justify-end gap-2">
                    <button type="button" onclick="closeRejectModal()" class="px-4 py-2 text-sm font-medium text-gray-600 bg-gray-100 hover:bg-gray-200 transition">Cancel</button>
                    <button type="submit" class="px-4 py-2 text-sm font-semibold text-white transition" style="background-color: #DC2626;">Reject</button>
                </div>
            </form>
        </div>
    </div>

    <link rel="stylesheet" href="https://cdn.datatables.net/2.2.2/css/dataTables.dataTables.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.dataTables.css">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/2.2.2/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.js"></script>
    <script>
        function openApproveModal(id, btn) {
            var baseUrl = '{{ url('admin/withdrawals') }}/' + id;
            document.getElementById('otpSendForm').action = baseUrl + '/send-otp';
            document.getElementById('approveForm').action = baseUrl + '/approve';
            document.getElementById('approveCampaign').textContent = btn.getAttribute('data-campaign');
            document.getElementById('approveAmount').textContent = 'KES ' + btn.getAttribute('data-amount');
            document.getElementById('approveNet').textContent = 'KES ' + btn.getAttribute('data-net');
            document.getElementById('approveDest').textContent = btn.getAttribute('data-dest');
            var otpSent = btn.getAttribute('data-otp-sent');
            if (otpSent === '1') {
                document.getElementById('otpStep1').classList.add('hidden');
                document.getElementById('otpStep2').classList.remove('hidden');
            } else {
                document.getElementById('otpStep1').classList.remove('hidden');
                document.getElementById('otpStep2').classList.add('hidden');
            }
            document.getElementById('approveModal').classList.remove('hidden');
        }
        function closeApproveModal() {
            document.getElementById('approveModal').classList.add('hidden');
        }
        function openRejectModal(id) {
            document.getElementById('rejectForm').action = '{{ url('admin/withdrawals') }}/' + id + '/reject';
            document.getElementById('rejectModal').classList.remove('hidden');
        }
        function closeRejectModal() {
            document.getElementById('rejectModal').classList.add('hidden');
        }
        function openEvidence(conversationId, responseDesc, disbursedAt, receipt) {
            document.getElementById('evReceipt').textContent = receipt || (document.querySelector('[data-receipt]')?.getAttribute('data-receipt')) || '—';
            document.getElementById('evConversationId').textContent = conversationId || '—';
            document.getElementById('evResponseDesc').textContent = responseDesc || '—';
            document.getElementById('evDisbursedAt').textContent = disbursedAt || '—';
            document.getElementById('evidenceModal').classList.remove('hidden');
        }
        function closeEvidence() {
            document.getElementById('evidenceModal').classList.add('hidden');
        }
        $(document).ready(function () {
            var otpSentId = '{{ session('otp_withdrawal_id') }}';
            if (otpSentId) {
                var btn = document.querySelector('[data-otp-sent="1"]');
                if (btn) btn.click();
            }
            $('#adminWithdrawalsTable').DataTable({
                paging: true,
                pageLength: 12,
                lengthChange: false,
                info: true,
                ordering: true,
                order: [],
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
                            columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9]
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
</x-admin-layout>
