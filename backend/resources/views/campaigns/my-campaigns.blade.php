<x-dashboard-layout title="My Campaigns">
    <x-slot name="header">My Campaigns</x-slot>

    <div class="flex items-center justify-between mb-6">
        <p class="text-sm text-gray-500">{{ $campaigns->count() }} campaign{{ $campaigns->count() !== 1 ? 's' : '' }}</p>
        <a href="{{ route('campaigns.create') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-[#CE5F26] text-white text-sm font-semibold hover:bg-[#B04E1E] transition shadow-lg shadow-[#CE5F26]/30">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            New Campaign
        </a>
    </div>

    <div class="bg-white border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table id="campaignsTable" class="w-full text-sm border-collapse">
                <thead>
                    <tr class="bg-gray-50/50">
                        <th class="text-center px-3 py-3 font-semibold text-gray-600 text-xs uppercase tracking-wider border border-gray-200 w-12">No.</th>
                        <th class="text-left px-4 py-3 font-semibold text-gray-600 text-xs uppercase tracking-wider border border-gray-200">Campaign</th>
                        <th class="text-left px-4 py-3 font-semibold text-gray-600 text-xs uppercase tracking-wider border border-gray-200">Category</th>
                        <th class="text-right px-4 py-3 font-semibold text-gray-600 text-xs uppercase tracking-wider border border-gray-200">Target (KES)</th>
                        <th class="text-right px-4 py-3 font-semibold text-gray-600 text-xs uppercase tracking-wider border border-gray-200">Raised (KES)</th>
                        <th class="text-left px-4 py-3 font-semibold text-gray-600 text-xs uppercase tracking-wider border border-gray-200">Progress</th>
                        <th class="text-center px-4 py-3 font-semibold text-gray-600 text-xs uppercase tracking-wider border border-gray-200">Status</th>
                        <th class="text-left px-4 py-3 font-semibold text-gray-600 text-xs uppercase tracking-wider border border-gray-200">Published</th>
                        <th class="text-left px-4 py-3 font-semibold text-gray-600 text-xs uppercase tracking-wider border border-gray-200">Deadline</th>
                        <th class="text-center px-4 py-3 font-semibold text-gray-600 text-xs uppercase tracking-wider border border-gray-200">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($campaigns as $campaign)
                        <tr class="hover:bg-gray-50/50 transition">
                            <td class="px-3 py-3 text-center text-gray-500 border border-gray-200">{{ $loop->iteration }}</td>
                            <td class="px-4 py-3 border border-gray-200">
                                <a href="{{ route('campaigns.show', $campaign) }}" class="font-medium text-[#CE5F26] hover:text-[#B04E1E] transition underline underline-offset-2 decoration-[#CE5F26]/30">
                                    {{ $campaign->title }}
                                </a>
                                <p class="text-xs text-gray-400 mt-0.5">{{ $campaign->created_at->format('M j, Y g:i A') }}</p>
                            </td>
                            <td class="px-4 py-3 text-gray-500 border border-gray-200">{{ $campaign->category?->name ?? '—' }}</td>
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
                                @if($campaign->rejection_reason && $campaign->status->value === 'draft')
                                    <div class="relative inline-block" x-data="{ show: false }">
                                        <button @click="show = !show" class="ml-1 text-red-400 hover:text-red-600 align-middle" title="Rejection reason">
                                            <svg class="w-3.5 h-3.5 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/></svg>
                                        </button>
                                        <div x-show="show" @click.outside="show = false" x-cloak
                                             class="absolute right-0 top-full mt-1 w-64 bg-white border border-gray-200 shadow-lg p-3 z-50 text-left">
                                            <p class="text-xs font-semibold text-red-700 mb-1">Rejection Reason:</p>
                                            <p class="text-xs text-gray-600">{{ $campaign->rejection_reason }}</p>
                                        </div>
                                    </div>
                                @endif
                            </td>
                            <td class="px-4 py-3 border border-gray-200">
                                @if($campaign->verified_at)
                                    <span class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($campaign->verified_at)->format('M j, Y') }}</span>
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
                            <td class="px-4 py-3 text-center border border-gray-200 whitespace-nowrap">
                                <div class="flex items-center justify-center gap-1.5">
                                    <div x-data="{ open: false }" class="relative inline-block">
                                        <button @click="open = !open" class="text-[11px] font-medium px-2 py-0.5 text-white rounded transition" style="background-color: #7AA36A;">Share</button>
                                        <div x-show="open" @click.outside="open = false" x-cloak
                                             class="absolute left-1/2 -translate-x-1/2 top-full mt-1 bg-white border border-gray-200 rounded-lg shadow-lg p-2 min-w-[160px] z-50">
                                            <div class="flex flex-col gap-1">
                                                <a href="https://wa.me/?text={{ urlencode($campaign->title . ' - ' . route('campaigns.show', $campaign)) }}" target="_blank"
                                                   class="flex items-center gap-2 px-3 py-2 text-xs font-medium text-gray-700 hover:bg-gray-100 rounded transition">
                                                    <svg class="w-4 h-4 text-green-500 shrink-0" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                                                    WhatsApp
                                                </a>
                                                <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(route('campaigns.show', $campaign)) }}" target="_blank"
                                                   class="flex items-center gap-2 px-3 py-2 text-xs font-medium text-gray-700 hover:bg-gray-100 rounded transition">
                                                    <svg class="w-4 h-4 text-blue-600 shrink-0" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                                                    Facebook
                                                </a>
                                                <a href="https://twitter.com/intent/tweet?text={{ urlencode($campaign->title . ' ' . route('campaigns.show', $campaign)) }}" target="_blank"
                                                   class="flex items-center gap-2 px-3 py-2 text-xs font-medium text-gray-700 hover:bg-gray-100 rounded transition">
                                                    <svg class="w-4 h-4 text-gray-800 shrink-0" fill="currentColor" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                                                    X (Twitter)
                                                </a>
                                                <button @click="navigator.clipboard.writeText('{{ route('campaigns.show', $campaign) }}');$el.innerHTML = 'Copied!';setTimeout(() => $el.innerHTML = 'Copy Link', 2000)"
                                                        class="flex items-center gap-2 px-3 py-2 text-xs font-medium text-gray-700 hover:bg-gray-100 rounded transition w-full text-left">
                                                    <svg class="w-4 h-4 text-gray-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/></svg>
                                                    Copy Link
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <a href="{{ route('campaigns.statement', $campaign) }}" class="text-[11px] font-medium px-2 py-0.5 text-white rounded transition whitespace-nowrap" style="background-color: #1B2A4A;">Report</a>
                                    @if(in_array($campaign->status->value, ['active', 'completed', 'closed']))
                                        <a href="{{ route('campaigns.withdrawals', $campaign) }}" class="text-[11px] font-medium px-2 py-0.5 text-white rounded transition whitespace-nowrap" style="background-color: #CE5F26;">Withdraw</a>
                                    @endif
                                    @if($campaign->status->value === 'draft')
                                        <a href="{{ route('campaigns.edit', $campaign) }}" class="text-[11px] font-medium px-2 py-0.5 text-white rounded transition whitespace-nowrap" style="background-color: #6B7280;">Edit</a>
                                        <form action="{{ route('campaigns.destroy', $campaign) }}" method="POST" class="inline" onsubmit="return confirm('Delete this campaign permanently? This cannot be undone.')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-[11px] font-medium px-2 py-0.5 text-white rounded transition whitespace-nowrap" style="background-color: #DC2626;">Delete</button>
                                        </form>
                                    @endif
                                </div>
                            </td>
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
            $('#campaignsTable').DataTable({
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
</x-dashboard-layout>
