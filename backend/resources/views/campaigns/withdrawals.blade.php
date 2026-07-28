<x-dashboard-layout title="Withdrawals">
    <x-slot name="header">Withdrawals: {{ $campaign->title }}</x-slot>

    <div class="space-y-6">
        {{-- Withdrawal Form --}}
        <div class="max-w-2xl bg-white border border-gray-200 p-6">
            <h3 class="font-semibold text-lg mb-4" style="color: #1B2A4A;">Request Withdrawal</h3>

            @if($hasPendingWithdrawal)
                <div class="bg-yellow-50 border border-yellow-200 text-yellow-800 px-4 py-3 text-sm">
                    You already have a pending withdrawal request for this campaign. Please wait for it to be processed before requesting another.
                </div>
            @else
            <p class="text-sm text-gray-600 mb-4">Available balance: <strong>KES {{ number_format($campaign->balance, 0) }}</strong> (min withdrawal: KES 10)</p>

            <form action="{{ route('campaigns.withdrawals.store', $campaign) }}" method="POST">
                @csrf
                <div x-data="{
                    amount: 0,
                    destinationType: 'mpesa',
                    feePercent: {{ $settings['platform_fee_percent'] ?? $campaign->platform_fee_percent }},
                    smsCredits: {{ auth()->user()->sms_credits }},
                    smsCostPerCredit: {{ $settings['sms_cost_per_credit'] ?? 5 }},
                    get platformFee() { return this.amount * this.feePercent / 100; },
                    get smsCost() { return this.smsCredits < 0 ? Math.abs(this.smsCredits) * this.smsCostPerCredit : 0; },
                    get netAmount() { return Math.max(0, this.amount - this.platformFee - this.smsCost); },
                    mpesaPhone: '{{ auth()->user()->mpesa_phone ?? '' }}',
                    bankAccount: '{{ auth()->user()->bank_account_number ?? '' }}',
                    bankName: '{{ auth()->user()->bank_name ?? '' }}',
                    bankAccountName: '{{ auth()->user()->bank_account_name ?? '' }}',
                    init() {
                        this.$watch('destinationType', val => {
                            if (val === 'mpesa') {
                                document.getElementById('destination_ref').value = this.mpesaPhone;
                            } else {
                                document.getElementById('destination_ref').value = this.bankAccount;
                            }
                        });
                    }
                }">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label for="amount" class="block text-sm font-medium text-gray-700">Amount (KES)</label>
                            <input type="number" name="amount" id="amount" x-model="amount"
                                   class="mt-1 block w-full border border-gray-300 shadow-sm focus:border-[#CE5F26] focus:ring-1 focus:ring-[#CE5F26] outline-none px-3 py-2"
                                   min="10" max="{{ $campaign->balance }}" required>
                        </div>
                        <div>
                            <label for="destination_type" class="block text-sm font-medium text-gray-700">Destination</label>
                            <select name="destination_type" id="destination_type" x-model="destinationType"
                                    class="mt-1 block w-full border border-gray-300 shadow-sm focus:border-[#CE5F26] focus:ring-1 focus:ring-[#CE5F26] outline-none px-3 py-2">
                                <option value="mpesa">M-Pesa</option>
                                <option value="bank">Bank Account</option>
                            </select>
                        </div>
                        <div>
                            <label for="destination_ref" class="block text-sm font-medium text-gray-700">Phone / Account</label>
                            <input type="text" name="destination_ref" id="destination_ref"
                                   class="mt-1 block w-full border border-gray-300 shadow-sm focus:border-[#CE5F26] focus:ring-1 focus:ring-[#CE5F26] outline-none px-3 py-2"
                                   placeholder="254712345678" required>
                        </div>
                    </div>

                    <template x-if="destinationType === 'bank' && bankName">
                        <div class="mt-3 p-3 bg-gray-50 border border-gray-200 text-sm text-gray-600 space-y-1">
                            <p><span class="font-medium">Bank:</span> <span x-text="bankName"></span></p>
                            <p><span class="font-medium">Account Name:</span> <span x-text="bankAccountName"></span></p>
                        </div>
                    </template>

                    {{-- Breakdown --}}
                    <template x-if="amount >= 10">
                        <div class="mt-5 border-t border-gray-200 pt-4 space-y-2">
                            <h4 class="font-semibold text-sm" style="color: #1B2A4A;">Withdrawal Breakdown</h4>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Withdrawal Amount</span>
                                <span class="font-medium" x-text="'KES ' + amount.toLocaleString()"></span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Platform Fee (<span x-text="feePercent"></span>%)</span>
                                <span class="font-medium text-red-600" x-text="'-KES ' + platformFee.toFixed(2)"></span>
                            </div>
                            <div class="flex justify-between text-sm" x-show="smsCredits < 0">
                                <span class="text-gray-600">SMS Credits Owed (<span x-text="Math.abs(smsCredits)"></span> @ KES <span x-text="smsCostPerCredit"></span>)</span>
                                <span class="font-medium text-red-600" x-text="'-KES ' + smsCost.toFixed(2)"></span>
                            </div>
                            <div class="flex justify-between text-sm font-semibold border-t border-gray-200 pt-2">
                                <span style="color: #1B2A4A;">Net Amount</span>
                                <span class="text-green-700" x-text="'KES ' + netAmount.toFixed(2)"></span>
                            </div>
                            <p class="text-xs text-gray-400 mt-2">Withdrawal fee of KES {{ $settings['withdrawal_fee'] ?? 30 }} will be applied separately.</p>
                        </div>
                    </template>

                    <button type="submit"
                            x-show="amount >= 10"
                            class="mt-4 px-6 py-2 text-white font-semibold transition" style="background-color: #CE5F26;">
                        Confirm &amp; Proceed
                    </button>
                </div>
            </form>
            @endif
        </div>

        {{-- Withdrawal History --}}
        <div class="bg-white border border-gray-200 overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100">
                <h3 class="font-semibold text-lg">Withdrawal History</h3>
            </div>
            <div class="overflow-x-auto">
                <table id="withdrawalsTable" class="w-full text-sm border-collapse">
                    <thead>
                        <tr class="bg-gray-50/50">
                            <th class="text-center px-3 py-3 font-semibold text-gray-600 text-xs uppercase tracking-wider border border-gray-200 w-12">No.</th>
                            <th class="text-left px-4 py-3 font-semibold text-gray-600 text-xs uppercase tracking-wider border border-gray-200">Date</th>
                            <th class="text-left px-4 py-3 font-semibold text-gray-600 text-xs uppercase tracking-wider border border-gray-200">Amount (KES)</th>
                            <th class="text-left px-4 py-3 font-semibold text-gray-600 text-xs uppercase tracking-wider border border-gray-200">Platform Fee (KES)</th>
                            <th class="text-left px-4 py-3 font-semibold text-gray-600 text-xs uppercase tracking-wider border border-gray-200">SMS (KES)</th>
                            <th class="text-left px-4 py-3 font-semibold text-gray-600 text-xs uppercase tracking-wider border border-gray-200">Withdrawal Fee (KES)</th>
                            <th class="text-left px-4 py-3 font-semibold text-gray-600 text-xs uppercase tracking-wider border border-gray-200">Net Amount (KES)</th>
                            <th class="text-left px-4 py-3 font-semibold text-gray-600 text-xs uppercase tracking-wider border border-gray-200">Destination</th>
                            <th class="text-left px-4 py-3 font-semibold text-gray-600 text-xs uppercase tracking-wider border border-gray-200">Status</th>
                            <th class="text-center px-3 py-3 font-semibold text-gray-600 text-xs uppercase tracking-wider border border-gray-200 w-20">Invoice</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($withdrawals as $withdrawal)
                            <tr class="hover:bg-gray-50/50 transition">
                                <td class="px-3 py-3 text-center text-gray-500 border border-gray-200">{{ $loop->iteration }}</td>
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
                                </td>
                                <td class="px-3 py-3 text-center border border-gray-200">
                                    @if($withdrawal->status->value === 'disbursed')
                                        <a href="{{ route('campaigns.withdrawals.invoice', [$campaign, $withdrawal]) }}" target="_blank"
                                           class="inline-flex items-center gap-1 text-xs font-medium text-[#CE5F26] hover:text-[#B04E1E] transition">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                            PDF
                                        </a>
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
    <script>
        $(document).ready(function () {
            $('#withdrawalsTable').DataTable({
                paging: true,
                pageLength: 12,
                lengthChange: false,
                info: true,
                ordering: true,
                order: [[1, 'desc']],
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
</x-dashboard-layout>
