<x-admin-layout title="Campaigns">
    <x-slot name="header">Campaigns</x-slot>

    <div class="bg-white border border-gray-200 overflow-hidden">
        <div class="p-4 border-b border-gray-100 flex flex-wrap gap-2">
            <a href="{{ route('admin.campaigns.index') }}" class="px-3 py-1.5 text-sm font-medium transition @if(!request('status')) bg-[#1B2A4A] text-white @else bg-gray-100 text-gray-600 hover:bg-gray-200 @endif">All</a>
            @foreach(['pending_verification', 'active', 'draft', 'completed', 'closed', 'cancelled'] as $s)
                <a href="{{ route('admin.campaigns.index', ['status' => $s]) }}"
                   class="px-3 py-1.5 text-sm font-medium transition @if(request('status') === $s) bg-[#1B2A4A] text-white @else bg-gray-100 text-gray-600 hover:bg-gray-200 @endif">
                    {{ str_replace('_', ' ', ucfirst($s)) }}
                </a>
            @endforeach
        </div>

        <div class="overflow-x-auto">
            <table id="adminCampaignsTable" class="w-full text-sm border-collapse">
                <thead>
                    <tr class="bg-gray-50/50">
                        <th class="text-center px-3 py-3 font-semibold text-gray-600 text-xs uppercase tracking-wider border border-gray-200 w-12">No.</th>
                        <th class="text-left px-4 py-3 font-semibold text-gray-600 text-xs uppercase tracking-wider border border-gray-200">Campaign</th>
                        <th class="text-left px-4 py-3 font-semibold text-gray-600 text-xs uppercase tracking-wider border border-gray-200">Owner</th>
                        <th class="text-right px-4 py-3 font-semibold text-gray-600 text-xs uppercase tracking-wider border border-gray-200">Target (KES)</th>
                        <th class="text-right px-4 py-3 font-semibold text-gray-600 text-xs uppercase tracking-wider border border-gray-200">Raised (KES)</th>
                        <th class="text-left px-4 py-3 font-semibold text-gray-600 text-xs uppercase tracking-wider border border-gray-200">Progress</th>
                        <th class="text-center px-4 py-3 font-semibold text-gray-600 text-xs uppercase tracking-wider border border-gray-200">Status</th>
                        <th class="text-left px-4 py-3 font-semibold text-gray-600 text-xs uppercase tracking-wider border border-gray-200">Treasurer</th>
                        <th class="text-left px-4 py-3 font-semibold text-gray-600 text-xs uppercase tracking-wider border border-gray-200">Deadline</th>
                        <th class="text-left px-4 py-3 font-semibold text-gray-600 text-xs uppercase tracking-wider border border-gray-200">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($campaigns as $campaign)
                        <tr class="hover:bg-gray-50/50 transition">
                            <td class="px-3 py-3 text-center text-gray-500 border border-gray-200">{{ $loop->iteration }}</td>
                            <td class="px-4 py-3 border border-gray-200 max-w-xs">
                                <a href="{{ route('admin.campaigns.show', $campaign) }}" class="font-medium text-[#CE5F26] hover:text-[#B04E1E] transition underline underline-offset-2 decoration-[#CE5F26]/30 block truncate">{{ $campaign->title }}</a>
                                @if($campaign->created_at)
                                    <span class="text-xs text-gray-400">{{ $campaign->created_at->format('M j, Y g:i A') }}</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 border border-gray-200">
                                <div class="font-medium text-gray-800">{{ $campaign->user->name }}</div>
                                <div class="text-xs text-gray-400">{{ $campaign->user->email }}</div>
                            </td>
                            <td class="px-4 py-3 text-right font-medium text-gray-700 border border-gray-200">{{ number_format($campaign->target_amount, 0) }}</td>
                            <td class="px-4 py-3 text-right font-semibold text-gray-900 border border-gray-200">{{ number_format($campaign->raised_amount, 0) }}</td>
                            <td class="px-4 py-3 border border-gray-200">
                                <div class="flex items-center gap-3 min-w-[140px]">
                                    <div class="flex-1 h-2 bg-gray-100 rounded-full overflow-hidden">
                                        <div class="h-full rounded-full transition-all duration-300
                                            @if($campaign->progressPercent() >= 100) bg-[#7AA36A]
                                            @elseif($campaign->progressPercent() >= 50) bg-[#CE5F26]
                                            @else bg-[#1B2A4A] @endif"
                                            style="width: {{ min($campaign->progressPercent(), 100) }}%">
                                        </div>
                                    </div>
                                    <span class="text-xs font-medium text-gray-500 w-10 text-right">{{ $campaign->progressPercent() }}%</span>
                                </div>
                            </td>
                            <td class="px-4 py-3 text-center border border-gray-200">
                                <span class="text-xs font-medium px-2.5 py-1
                                    @if($campaign->status->value === 'active') bg-green-50 text-green-700
                                    @elseif($campaign->status->value === 'pending_verification') bg-yellow-50 text-yellow-700
                                    @elseif($campaign->status->value === 'draft') bg-gray-100 text-gray-600
                                    @else bg-gray-100 text-gray-500 @endif">
                                    {{ str_replace('_', ' ', ucfirst($campaign->status->value)) }}
                                </span>
                            </td>
                            <td class="px-4 py-3 border border-gray-200">
                                @if($campaign->is_treasurer_controlled)
                                    <span class="inline-flex items-center gap-1 text-xs font-medium text-[#CE5F26] bg-[#CE5F26]/10 px-2 py-0.5 rounded-full">Treasurer</span>
                                    <div class="text-xs text-gray-500 mt-1">{{ $campaign->treasurer_name ?? 'N/A' }}</div>
                                @else
                                    <span class="text-xs text-gray-400">—</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 border border-gray-200">
                                @php
                                    $expiry = $campaign->expiry_date ? \Carbon\Carbon::parse($campaign->expiry_date) : null;
                                    $daysLeft = $expiry ? (int) now()->diffInDays($expiry, false) : null;
                                @endphp
                                @if($expiry)
                                    <div class="text-xs whitespace-nowrap">
                                        <span class="text-gray-500">{{ $expiry->format('M j, Y') }}</span>
                                        @if($daysLeft > 0)
                                            <span class="ml-1 font-medium {{ $daysLeft <= 7 ? 'text-red-500' : 'text-gray-500' }}">({{ $daysLeft }} {{ Str::plural('day', $daysLeft) }} remaining)</span>
                                        @elseif($daysLeft == 0)
                                            <span class="text-red-500 ml-1 font-medium">(Last day)</span>
                                        @else
                                            <span class="text-red-500 ml-1 font-medium">(Expired)</span>
                                        @endif
                                    </div>
                                @else
                                    <span class="text-xs text-gray-400">—</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-left border border-gray-200 whitespace-nowrap">
                                <div class="flex items-center justify-start gap-1.5">
                                    <a href="{{ route('admin.campaigns.show', $campaign) }}" class="text-[11px] font-medium px-2 py-0.5 text-white rounded transition whitespace-nowrap" style="background-color: #1B2A4A;">View</a>
                                    <a href="{{ route('admin.campaigns.edit', $campaign) }}" class="text-[11px] font-medium px-2 py-0.5 text-white rounded transition whitespace-nowrap" style="background-color: #6B7280;">Edit</a>
                                    <form action="{{ route('admin.campaigns.delete', $campaign) }}" method="POST" class="inline"
                                          onsubmit="return confirm('Delete this campaign permanently?')">
                                        @csrf
                                        <button type="submit" class="text-[11px] font-medium px-2 py-0.5 text-white rounded transition whitespace-nowrap" style="background-color: #DC2626;">Delete</button>
                                    </form>
                                    @if(!in_array($campaign->status->value, ['completed', 'closed', 'cancelled']))
                                        <form action="{{ route('admin.campaigns.close', $campaign) }}" method="POST" class="inline"
                                              onsubmit="return confirm('Close this campaign?')">
                                            @csrf
                                            <button type="submit" class="text-[11px] font-medium px-2 py-0.5 text-gray-600 bg-gray-200 hover:bg-gray-300 rounded transition whitespace-nowrap">Close</button>
                                        </form>
                                    @endif
                                    @if($campaign->expiry_date)
                                        <button @click="$dispatch('open-extend-{{ $campaign->id }}')"
                                                class="text-[11px] font-medium px-2 py-0.5 text-white rounded transition whitespace-nowrap" style="background-color: #1B2A4A;">Extend</button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    @foreach($campaigns as $campaign)
        @if($campaign->expiry_date)
            <div x-data="{ open: false }"
                 @open-extend-{{ $campaign->id }}.window="open = true"
                 x-show="open"
                 x-cloak
                 class="fixed inset-0 z-50 flex items-center justify-center bg-black/50"
                 @click.outside="open = false">
                <div class="bg-white rounded-lg shadow-xl w-full max-w-md p-6">
                    <h3 class="text-lg font-semibold text-[#1B2A4A] mb-4">Extend Deadline</h3>
                    <form action="{{ route('admin.campaigns.extend', $campaign) }}" method="POST">
                        @csrf
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-1">New Expiry Date</label>
                            <input type="date" name="expiry_date" required
                                   class="w-full border border-gray-200 px-3 py-2 text-sm outline-none focus:border-[#CE5F26] focus:ring-1 focus:ring-[#CE5F26]">
                        </div>
                        <div class="flex items-center justify-end gap-3">
                            <button type="button" @click="open = false"
                                    class="px-4 py-2 text-sm font-medium text-gray-600 hover:text-gray-800 transition">Cancel</button>
                            <button type="submit"
                                    class="px-4 py-2 text-sm font-medium text-white transition" style="background-color: #CE5F26;">Extend</button>
                        </div>
                    </form>
                </div>
            </div>
        @endif
    @endforeach

    <link rel="stylesheet" href="https://cdn.datatables.net/2.2.2/css/dataTables.dataTables.css">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/2.2.2/js/dataTables.js"></script>
    <script>
        $(document).ready(function () {
            $('#adminCampaignsTable').DataTable({
                paging: true,
                pageLength: 12,
                lengthChange: false,
                info: true,
                ordering: true,
                order: [],
                language: {
                    search: '',
                    searchPlaceholder: 'Search campaigns...',
                    emptyTable: 'No campaigns found'
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