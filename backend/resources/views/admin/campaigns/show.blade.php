<x-admin-layout title="Campaign Details">
    <x-slot name="header">Campaign: {{ $campaign->title }}</x-slot>

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
                <p class="text-xs text-gray-400 mt-1">of KES {{ number_format($campaign->target_amount, 0) }} target</p>
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
                    <span class="text-sm font-medium text-gray-500">Withdrawn</span>
                    <svg class="w-8 h-8 text-[#CE5F26]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </div>
                <p class="text-2xl font-bold text-gray-900">KES {{ number_format($totalWithdrawn, 0) }}</p>
                <p class="text-xs text-gray-400 mt-1">Total disbursed</p>
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

        {{-- Main Content with Tabs --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2">
                <div x-data="{ tab: 'info' }" class="bg-white border border-gray-200 overflow-hidden">
                    {{-- Tab Headers --}}
                    <div class="flex border-b border-gray-200 bg-gray-50/50">
                        <button @click="tab = 'info'" :class="tab === 'info' ? 'border-b-2 border-[#CE5F26] text-[#CE5F26]' : 'text-gray-500 hover:text-gray-700'" class="px-5 py-3 text-sm font-semibold transition">Campaign Info</button>
                        <button @click="tab = 'media'" :class="tab === 'media' ? 'border-b-2 border-[#CE5F26] text-[#CE5F26]' : 'text-gray-500 hover:text-gray-700'" class="px-5 py-3 text-sm font-semibold transition">Media</button>
                        <button @click="tab = 'video'" :class="tab === 'video' ? 'border-b-2 border-[#CE5F26] text-[#CE5F26]' : 'text-gray-500 hover:text-gray-700'" class="px-5 py-3 text-sm font-semibold transition">Video</button>
                        <button @click="tab = 'donations'" :class="tab === 'donations' ? 'border-b-2 border-[#CE5F26] text-[#CE5F26]' : 'text-gray-500 hover:text-gray-700'" class="px-5 py-3 text-sm font-semibold transition">Donations</button>
                        <button @click="tab = 'withdrawals'" :class="tab === 'withdrawals' ? 'border-b-2 border-[#CE5F26] text-[#CE5F26]' : 'text-gray-500 hover:text-gray-700'" class="px-5 py-3 text-sm font-semibold transition">Withdrawals</button>
                    </div>

                    {{-- Tab: Campaign Info --}}
                    <div x-show="tab === 'info'" class="p-6">
                        <div>
                            <span class="text-xs font-medium text-gray-500 uppercase tracking-wider">Title</span>
                            <p class="text-gray-800 mt-1 text-lg font-semibold">{{ $campaign->title }}</p>
                        </div>
                        <div class="mt-5">
                            <span class="text-xs font-medium text-gray-500 uppercase tracking-wider">Description</span>
                            <div class="text-gray-600 mt-1 text-sm leading-relaxed whitespace-pre-wrap">{{ $campaign->story }}</div>
                        </div>
                        <div class="mt-6 overflow-x-auto">
                            <table class="w-full text-sm border-collapse">
                                <tbody>
                                    <tr class="border-b border-gray-100">
                                        <td class="py-3 pr-6 text-xs font-medium text-gray-500 uppercase tracking-wider w-40">Category</td>
                                        <td class="py-3 text-gray-800">{{ $campaign->category?->name ?? '—' }}</td>
                                    </tr>
                                    <tr class="border-b border-gray-100">
                                        <td class="py-3 pr-6 text-xs font-medium text-gray-500 uppercase tracking-wider">Location</td>
                                        <td class="py-3 text-gray-800">{{ $campaign->location ?? '—' }}</td>
                                    </tr>
                                    <tr class="border-b border-gray-100">
                                        <td class="py-3 pr-6 text-xs font-medium text-gray-500 uppercase tracking-wider">Target</td>
                                        <td class="py-3 text-gray-800 font-semibold">KES {{ number_format($campaign->target_amount, 0) }}</td>
                                    </tr>
                                    <tr class="border-b border-gray-100">
                                        <td class="py-3 pr-6 text-xs font-medium text-gray-500 uppercase tracking-wider">Raised</td>
                                        <td class="py-3 text-gray-800 font-semibold">KES {{ number_format($campaign->raised_amount, 0) }}</td>
                                    </tr>
                                    <tr class="border-b border-gray-100">
                                        <td class="py-3 pr-6 text-xs font-medium text-gray-500 uppercase tracking-wider">Owner</td>
                                        <td class="py-3 text-gray-800">{{ $campaign->user->name }}<br><span class="text-xs text-gray-400">{{ $campaign->user->email }} | {{ $campaign->user->phone }}</span></td>
                                    </tr>
                                    <tr class="border-b border-gray-100">
                                        <td class="py-3 pr-6 text-xs font-medium text-gray-500 uppercase tracking-wider">Treasurer</td>
                                        <td class="py-3">
                                            @if($campaign->is_treasurer_controlled && $campaign->treasurer_name)
                                                <span class="inline-flex items-center gap-1 text-xs font-medium text-[#CE5F26] bg-[#CE5F26]/10 px-2 py-0.5 rounded-full">Treasurer Controlled</span>
                                                <p class="text-gray-800 mt-1">{{ $campaign->treasurer_name }}</p>
                                                @if($campaign->treasurer_phone)
                                                    <p class="text-xs text-gray-400">{{ $campaign->treasurer_phone }}</p>
                                                @endif
                                            @else
                                                <span class="text-gray-400">—</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr class="border-b border-gray-100">
                                        <td class="py-3 pr-6 text-xs font-medium text-gray-500 uppercase tracking-wider">Status</td>
                                        <td class="py-3">
                                            <span class="text-xs font-medium px-2.5 py-1
                                                @if($campaign->status->value === 'active') bg-green-50 text-green-700
                                                @elseif($campaign->status->value === 'pending_verification') bg-yellow-50 text-yellow-700
                                                @elseif($campaign->status->value === 'draft') bg-gray-100 text-gray-600
                                                @else bg-gray-100 text-gray-500 @endif">
                                                {{ str_replace('_', ' ', ucfirst($campaign->status->value)) }}
                                            </span>
                                            @if($campaign->verified_at)
                                                <span class="text-xs text-gray-400 ml-2">Verified {{ \Carbon\Carbon::parse($campaign->verified_at)->format('M j, Y') }}</span>
                                            @else
                                                <span class="text-xs text-gray-400 ml-2">Not verified</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="py-3 pr-6 text-xs font-medium text-gray-500 uppercase tracking-wider">Deadline</td>
                                        <td class="py-3">
                                            @if($campaign->expiry_date)
                                                <span class="text-gray-800">{{ \Carbon\Carbon::parse($campaign->expiry_date)->format('M j, Y') }}</span>
                                                <span class="text-xs ml-2 {{ max(0, (int) now()->diffInDays(\Carbon\Carbon::parse($campaign->expiry_date), false)) <= 7 ? 'text-red-500 font-medium' : 'text-gray-400' }}">
                                                    ({{ max(0, (int) now()->diffInDays(\Carbon\Carbon::parse($campaign->expiry_date), false)) }} days remaining)
                                                </span>
                                            @else
                                                <span class="text-gray-400">—</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="py-3 pr-6 text-xs font-medium text-gray-500 uppercase tracking-wider">Created</td>
                                        <td class="py-3 text-gray-800">{{ $campaign->created_at->format('M j, Y H:i') }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- Tab: Media --}}
                    <div x-show="tab === 'media'" class="p-6">
                        <h4 class="font-semibold text-gray-800 mb-4">Gallery Images</h4>
                        @php $gallery = $campaign->getMedia('gallery'); @endphp
                        @if($gallery->count())
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                @foreach($gallery as $media)
                                    <div class="border border-gray-200 rounded-lg overflow-hidden">
                                        <img src="{{ $media->getUrl() }}" alt="{{ $media->name }}" class="w-full h-40 object-cover">
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-gray-400 text-sm">No gallery images.</p>
                        @endif

                        <h4 class="font-semibold text-gray-800 mt-6 mb-4">Cover Image</h4>
                        @if($campaign->getFirstMediaUrl('cover_image'))
                            <div class="border border-gray-200 rounded-lg overflow-hidden max-w-md">
                                <img src="{{ $campaign->getFirstMediaUrl('cover_image') }}" alt="Cover" class="w-full h-48 object-cover">
                            </div>
                        @else
                            <p class="text-gray-400 text-sm">No cover image.</p>
                        @endif
                    </div>

                    {{-- Tab: Video --}}
                    <div x-show="tab === 'video'" class="p-6">
                        <h4 class="font-semibold text-gray-800 mb-4">Campaign Video</h4>
                        @php $videoFile = $campaign->getFirstMedia('video'); @endphp
                        @if($videoFile)
                            <div class="aspect-video bg-black rounded-lg overflow-hidden">
                                <video class="w-full h-full" controls>
                                    <source src="{{ $videoFile->getUrl() }}" type="{{ $videoFile->mime_type }}">
                                    Your browser does not support the video tag.
                                </video>
                            </div>
                        @elseif($campaign->video_url)
                            <div class="aspect-video bg-gray-100 rounded-lg overflow-hidden">
                                @php
                                    $videoId = null;
                                    if (preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/ ]{11})/', $campaign->video_url, $match)) {
                                        $videoId = $match[1];
                                    }
                                @endphp
                                @if($videoId)
                                    <iframe class="w-full h-full" src="https://www.youtube.com/embed/{{ $videoId }}" frameborder="0" allowfullscreen></iframe>
                                @else
                                    <a href="{{ $campaign->video_url }}" target="_blank" class="flex items-center justify-center h-full text-[#CE5F26] hover:underline">{{ $campaign->video_url }}</a>
                                @endif
                            </div>
                        @else
                            <p class="text-gray-400 text-sm">No video attached.</p>
                        @endif
                    </div>

                    {{-- Tab: Donations --}}
                    <div x-show="tab === 'donations'" class="p-6">
                        <h4 class="font-semibold text-gray-800 mb-4">Donation History</h4>
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm border-collapse">
                                <thead>
                                    <tr class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider bg-gray-50/50">
                                        <th class="px-3 py-3 text-center border border-gray-200 w-10">No.</th>
                                        <th class="px-3 py-3 border border-gray-200">Date</th>
                                        <th class="px-3 py-3 border border-gray-200">Donor</th>
                                        <th class="px-3 py-3 text-right border border-gray-200">Amount</th>
                                        <th class="px-3 py-3 border border-gray-200">Transaction Code</th>
                                        <th class="px-3 py-3 border border-gray-200">Payment</th>
                                        <th class="px-3 py-3 border border-gray-200">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($donations as $donation)
                                        <tr class="hover:bg-gray-50/50 transition">
                                            <td class="px-3 py-3 text-center text-gray-500 text-xs border border-gray-200">{{ $donations->firstItem() + $loop->index }}</td>
                                            <td class="px-3 py-3 text-gray-500 text-xs border border-gray-200">{{ $donation->created_at->format('M j, Y H:i') }}</td>
                                            <td class="px-3 py-3 font-medium text-gray-800 text-sm border border-gray-200">{{ $donation->donor_name ?? ($donation->user->name ?? 'Anonymous') }}</td>
                                            <td class="px-3 py-3 text-right font-semibold text-gray-900 border border-gray-200">KES {{ number_format($donation->amount, 0) }}</td>
                                            <td class="px-3 py-3 text-gray-500 text-xs font-mono border border-gray-200">{{ $donation->payment_ref ?? '—' }}</td>
                                            <td class="px-3 py-3 text-gray-500 text-xs capitalize border border-gray-200">{{ $donation->payment_method }}</td>
                                            <td class="px-3 py-3 border border-gray-200">
                                                <span class="px-2 py-0.5 text-xs font-semibold @if($donation->status->value === 'completed') bg-green-50 text-green-700 @else bg-yellow-50 text-yellow-700 @endif">{{ ucfirst($donation->status->value) }}</span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="p-6 text-center text-gray-500 border border-gray-200">No donations yet.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-3">{{ $donations->links() }}</div>
                    </div>

                    {{-- Tab: Withdrawals --}}
                    <div x-show="tab === 'withdrawals'" class="p-6">
                        <h4 class="font-semibold text-gray-800 mb-4">Withdrawal History</h4>
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm border-collapse">
                                <thead>
                                    <tr class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider bg-gray-50/50">
                                        <th class="px-3 py-3 text-center border border-gray-200 w-10">No.</th>
                                        <th class="px-3 py-3 border border-gray-200">Date</th>
                                        <th class="px-3 py-3 text-right border border-gray-200">Amount</th>
                                        <th class="px-3 py-3 border border-gray-200">Destination</th>
                                        <th class="px-3 py-3 border border-gray-200">Date Approved</th>
                                        <th class="px-3 py-3 border border-gray-200">Status</th>
                                        <th class="px-3 py-3 text-center border border-gray-200 w-20">Invoice</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($withdrawals as $withdrawal)
                                        <tr class="hover:bg-gray-50/50 transition">
                                            <td class="px-3 py-3 text-center text-gray-500 text-xs border border-gray-200">{{ $withdrawals->firstItem() + $loop->index }}</td>
                                            <td class="px-3 py-3 text-gray-500 text-xs border border-gray-200">{{ $withdrawal->created_at->format('M j, Y H:i') }}</td>
                                            <td class="px-3 py-3 text-right font-semibold text-gray-900 border border-gray-200">KES {{ number_format($withdrawal->amount, 0) }}</td>
                                            <td class="px-3 py-3 text-gray-600 text-xs border border-gray-200">{{ strtoupper($withdrawal->destination_type) }} - {{ $withdrawal->destination_ref }}</td>
                                            <td class="px-3 py-3 text-gray-500 text-xs border border-gray-200">
                                                {{ $withdrawal->disbursed_at ? $withdrawal->disbursed_at->format('M j, Y H:i') : '—' }}
                                            </td>
                                            <td class="px-3 py-3 border border-gray-200">
                                                <span class="px-2 py-0.5 text-xs font-semibold
                                                    @if($withdrawal->status->value === 'disbursed') bg-green-50 text-green-700
                                                    @elseif($withdrawal->status->value === 'rejected') bg-red-50 text-red-700
                                                    @else bg-yellow-50 text-yellow-700 @endif">
                                                    {{ str_replace('_', ' ', ucfirst($withdrawal->status->value)) }}
                                                </span>
                                            </td>
                                            <td class="px-3 py-3 text-center border border-gray-200">
                                                @if($withdrawal->status->value === 'disbursed')
                                                    <a href="{{ route('admin.withdrawals.invoice', $withdrawal) }}" target="_blank"
                                                       class="inline-flex items-center gap-1 text-xs font-medium text-[#CE5F26] hover:text-[#B04E1E] transition">
                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                                        PDF
                                                    </a>
                                                @else
                                                    <span class="text-gray-300 text-xs">—</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="p-6 text-center text-gray-500 border border-gray-200">No withdrawals yet.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-3">{{ $withdrawals->links() }}</div>
                    </div>
                </div>
            </div>

            {{-- Sidebar --}}
            <div class="space-y-6">
                <div class="bg-white border border-gray-200 p-6">
                    <h3 class="font-semibold text-lg mb-4" style="color: #1B2A4A;">Actions</h3>
                    <div class="space-y-3">
                        @if($campaign->status->value === 'pending_verification')
                            <form action="{{ route('admin.campaigns.verify', $campaign) }}" method="POST" onsubmit="return confirm('Approve this campaign? The fundraiser will be notified via email.')">
                                @csrf
                                <button type="submit" class="w-full px-4 py-2.5 bg-green-600 text-white text-sm font-semibold hover:bg-green-700 transition">Approve Campaign</button>
                            </form>
                            <button @click="$dispatch('open-reject-modal')" class="w-full px-4 py-2.5 bg-red-600 text-white text-sm font-semibold hover:bg-red-700 transition">Reject Campaign</button>
                        @endif
                        @if(!in_array($campaign->status->value, ['completed', 'closed', 'cancelled']))
                            <form action="{{ route('admin.campaigns.close', $campaign) }}" method="POST" onsubmit="return confirm('Close this campaign?')">
                                @csrf
                                <button type="submit" class="w-full px-4 py-2.5 bg-orange-600 text-white text-sm font-semibold hover:bg-orange-700 transition">Close Campaign</button>
                            </form>
                        @endif
                        @if($campaign->expiry_date)
                            <button @click="$dispatch('open-extend')" class="w-full px-4 py-2.5 text-sm font-semibold text-white transition" style="background-color: #1B2A4A;">Extend Deadline</button>
                        @endif
                        <a href="{{ route('admin.campaigns.edit', $campaign) }}" class="block w-full text-center px-4 py-2.5 text-sm font-semibold text-white transition" style="background-color: #6B7280;">Edit Campaign</a>
                        <form action="{{ route('admin.campaigns.delete', $campaign) }}" method="POST" onsubmit="return confirm('Delete this campaign permanently?')">
                            @csrf
                            <button type="submit" class="w-full px-4 py-2.5 bg-red-600 text-white text-sm font-semibold hover:bg-red-700 transition">Delete Campaign</button>
                        </form>
                        <a href="{{ route('campaigns.show', $campaign) }}" target="_blank" class="block w-full text-center px-4 py-2.5 text-sm font-semibold text-gray-600 bg-gray-100 hover:bg-gray-200 transition">View Public Page</a>
                    </div>
                </div>

                <div class="bg-white border border-gray-200 p-6">
                    <h3 class="font-semibold text-lg mb-4" style="color: #1B2A4A;">Change Status</h3>
                    <form action="{{ route('admin.campaigns.status', $campaign) }}" method="POST">
                        @csrf
                        <select name="status" class="w-full border border-gray-200 px-3 py-2 text-sm outline-none focus:border-[#CE5F26] focus:ring-1 focus:ring-[#CE5F26] mb-3">
                            <option value="draft" @selected($campaign->status->value === 'draft')>Draft</option>
                            <option value="pending_verification" @selected($campaign->status->value === 'pending_verification')>Pending Verification</option>
                            <option value="active" @selected($campaign->status->value === 'active')>Active</option>
                            <option value="paused" @selected($campaign->status->value === 'paused')>Paused</option>
                            <option value="completed" @selected($campaign->status->value === 'completed')>Completed</option>
                            <option value="cancelled" @selected($campaign->status->value === 'cancelled')>Cancelled</option>
                            <option value="closed" @selected($campaign->status->value === 'closed')>Closed</option>
                        </select>
                        <button type="submit" class="w-full px-4 py-2.5 bg-gray-600 text-white text-sm font-semibold hover:bg-gray-700 transition">Update Status</button>
                    </form>
                </div>

                <div class="bg-white border border-gray-200 p-6">
                    <h3 class="font-semibold text-lg mb-4" style="color: #1B2A4A;">Progress</h3>
                    <div class="space-y-4">
                        <div>
                            <div class="flex justify-between text-sm mb-1">
                                <span class="text-gray-600">Raised</span>
                                <span class="font-semibold">{{ $campaign->progressPercent() }}%</span>
                            </div>
                            <div class="h-3 bg-gray-100 rounded-full overflow-hidden">
                                <div class="h-full rounded-full transition-all duration-300
                                    @if($campaign->progressPercent() >= 100) bg-[#7AA36A]
                                    @elseif($campaign->progressPercent() >= 50) bg-[#CE5F26]
                                    @else bg-[#1B2A4A] @endif"
                                    style="width: {{ min($campaign->progressPercent(), 100) }}%">
                                </div>
                            </div>
                        </div>
                        <div class="text-sm text-gray-600">
                            <p>KES {{ number_format($campaign->raised_amount, 0) }} raised of KES {{ number_format($campaign->target_amount, 0) }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Extend Deadline Modal --}}
    @if($campaign->expiry_date)
        <div x-data="{ open: false }"
             @open-extend.window="open = true"
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
                        <button type="button" @click="open = false" class="px-4 py-2 text-sm font-medium text-gray-600 hover:text-gray-800 transition">Cancel</button>
                        <button type="submit" class="px-4 py-2 text-sm font-medium text-white transition" style="background-color: #CE5F26;">Extend</button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    {{-- Reject Modal --}}
    <div x-data="{ open: false }"
         @open-reject-modal.window="open = true"
         x-show="open"
         x-cloak
         class="fixed inset-0 z-50 flex items-center justify-center bg-black/50"
         @click.outside="open = false">
        <div class="bg-white w-full max-w-lg p-6">
            <h3 class="text-lg font-semibold text-[#1B2A4A] mb-4">Reject Campaign</h3>
            <form action="{{ route('admin.campaigns.reject', $campaign) }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Reason for Rejection</label>
                    <textarea name="rejection_reason" rows="5" required minlength="10"
                              class="w-full border border-gray-200 px-3 py-2 text-sm outline-none focus:border-[#CE5F26] focus:ring-1 focus:ring-[#CE5F26]"
                              placeholder="Explain why the campaign was rejected so the fundraiser can make improvements..."></textarea>
                    <p class="text-xs text-gray-400 mt-1">The fundraiser will see this reason and can edit their campaign to resubmit.</p>
                </div>
                <div class="flex items-center justify-end gap-3">
                    <button type="button" @click="open = false" class="px-4 py-2 text-sm font-medium text-gray-600 hover:text-gray-800 transition">Cancel</button>
                    <button type="submit" class="px-4 py-2 text-sm font-medium text-white transition bg-red-600 hover:bg-red-700">Reject Campaign</button>
                </div>
            </form>
        </div>
    </div>
</x-admin-layout>