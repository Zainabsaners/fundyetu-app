<x-admin-layout title="User Details">
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <span>{{ $user->name }}</span>
            <a href="{{ route('admin.users.index') }}" class="text-sm font-medium text-[#CE5F26] hover:text-[#B04E1E] transition">&larr; Back to Users</a>
        </div>
    </x-slot>

    <div class="space-y-6">
        {{-- Summary Cards --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 lg:gap-6">
            <div class="bg-white border border-gray-200 p-5">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-sm font-medium text-gray-500">Campaigns</span>
                    <svg class="w-8 h-8 text-[#CE5F26]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                    </svg>
                </div>
                <p class="text-2xl font-bold text-gray-900">{{ $totalCampaigns }}</p>
                <p class="text-xs text-gray-400 mt-1">{{ $activeCampaigns }} active</p>
            </div>
            <div class="bg-white border border-gray-200 p-5">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-sm font-medium text-gray-500">Total Raised</span>
                    <svg class="w-8 h-8 text-[#CE5F26]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <p class="text-2xl font-bold text-gray-900">KES {{ number_format($totalRaised, 0) }}</p>
                <p class="text-xs text-gray-400 mt-1">Across all campaigns</p>
            </div>
            <div class="bg-white border border-gray-200 p-5">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-sm font-medium text-gray-500">KYC Status</span>
                    <svg class="w-8 h-8 text-[#CE5F26]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                </div>
                <p class="text-2xl font-bold
                    @if($user->kyc_status === 'verified') text-green-600
                    @elseif($user->kyc_status === 'rejected') text-red-600
                    @elseif($user->kyc_status === 'pending') text-yellow-600
                    @else text-gray-500 @endif">
                    @if($user->kyc_status === 'pending') KYC Pending Review
                    @else {{ ucfirst($user->kyc_status) }}
                    @endif
                </p>
                <p class="text-xs text-gray-400 mt-1">
                    @if($user->is_approved) Account activated @else Pending approval @endif
                </p>
            </div>
            <div class="bg-white border border-gray-200 p-5">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-sm font-medium text-gray-500">SMS Credits</span>
                    <svg class="w-8 h-8 text-[#CE5F26]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
                    </svg>
                </div>
                <p class="text-2xl font-bold text-gray-900">{{ $user->sms_credits ?? 0 }}</p>
                <p class="text-xs text-gray-400 mt-1">Remaining</p>
            </div>
        </div>

        {{-- Main Content with Tabs --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2">
                <div x-data="{ tab: new URLSearchParams(window.location.search).get('tab') || 'info' }" class="bg-white border border-gray-200 overflow-hidden">
                    {{-- Tab Headers --}}
                    <div class="flex border-b border-gray-200 bg-gray-50/50">
                        <button @click="tab = 'info'" :class="tab === 'info' ? 'border-b-2 border-[#CE5F26] text-[#CE5F26]' : 'text-gray-500 hover:text-gray-700'" class="px-5 py-3 text-sm font-semibold transition">User Info</button>
                        <button @click="tab = 'kyc'" :class="tab === 'kyc' ? 'border-b-2 border-[#CE5F26] text-[#CE5F26]' : 'text-gray-500 hover:text-gray-700'" class="px-5 py-3 text-sm font-semibold transition">KYC Documents</button>
                        <button @click="tab = 'payout'" :class="tab === 'payout' ? 'border-b-2 border-[#CE5F26] text-[#CE5F26]' : 'text-gray-500 hover:text-gray-700'" class="px-5 py-3 text-sm font-semibold transition">Payout Info</button>
                        <button @click="tab = 'campaigns'" :class="tab === 'campaigns' ? 'border-b-2 border-[#CE5F26] text-[#CE5F26]' : 'text-gray-500 hover:text-gray-700'" class="px-5 py-3 text-sm font-semibold transition">Campaigns</button>
                    </div>

                    {{-- Tab: User Info --}}
                    <div x-show="tab === 'info'" class="p-6">
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm border-collapse">
                                <tbody>
                                    <tr class="border-b border-gray-100">
                                        <td class="py-3 pr-6 text-xs font-medium text-gray-500 uppercase tracking-wider w-40">Full Name</td>
                                        <td class="py-3 text-gray-800 font-medium">{{ $user->name }}</td>
                                    </tr>
                                    <tr class="border-b border-gray-100">
                                        <td class="py-3 pr-6 text-xs font-medium text-gray-500 uppercase tracking-wider">Email</td>
                                        <td class="py-3 text-gray-800">
                                            {{ $user->email }}
                                            @if($user->email_verified_at)
                                                <span class="ml-2 text-xs text-green-600 font-medium">Verified</span>
                                            @else
                                                <span class="ml-2 text-xs text-yellow-600 font-medium">Not verified</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr class="border-b border-gray-100">
                                        <td class="py-3 pr-6 text-xs font-medium text-gray-500 uppercase tracking-wider">Phone</td>
                                        <td class="py-3 text-gray-800 font-mono">
                                            {{ $user->phone ?? '—' }}
                                            @if($user->phone_verified_at)
                                                <span class="ml-2 text-xs text-green-600 font-medium">Verified</span>
                                            @else
                                                <span class="ml-2 text-xs text-yellow-600 font-medium">Unverified</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr class="border-b border-gray-100">
                                        <td class="py-3 pr-6 text-xs font-medium text-gray-500 uppercase tracking-wider">ID Number</td>
                                        <td class="py-3 text-gray-800 font-mono">{{ $user->id_number ?? '—' }}</td>
                                    </tr>
                                    <tr class="border-b border-gray-100">
                                        <td class="py-3 pr-6 text-xs font-medium text-gray-500 uppercase tracking-wider">Birth Year</td>
                                        <td class="py-3 text-gray-800">{{ $user->birth_year ?? '—' }}</td>
                                    </tr>
                                    <tr class="border-b border-gray-100">
                                        <td class="py-3 pr-6 text-xs font-medium text-gray-500 uppercase tracking-wider">Address</td>
                                        <td class="py-3 text-gray-800">{{ $user->address ?? '—' }}</td>
                                    </tr>
                                    <tr class="border-b border-gray-100">
                                        <td class="py-3 pr-6 text-xs font-medium text-gray-500 uppercase tracking-wider">Roles</td>
                                        <td class="py-3">
                                            @forelse($user->roles as $role)
                                                <span class="inline-flex items-center px-2 py-0.5 text-xs font-medium rounded bg-[#1B2A4A]/10 text-[#1B2A4A] mr-1">{{ $role->name }}</span>
                                            @empty
                                                <span class="text-gray-400">—</span>
                                            @endforelse
                                        </td>
                                    </tr>
                                    <tr class="border-b border-gray-100">
                                        <td class="py-3 pr-6 text-xs font-medium text-gray-500 uppercase tracking-wider">Registered</td>
                                        <td class="py-3 text-gray-800">{{ $user->created_at->format('M j, Y H:i') }}</td>
                                    </tr>
                                    <tr>
                                        <td class="py-3 pr-6 text-xs font-medium text-gray-500 uppercase tracking-wider">Last Updated</td>
                                        <td class="py-3 text-gray-800">{{ $user->updated_at->format('M j, Y H:i') }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- Tab: KYC Documents --}}
                    <div x-show="tab === 'kyc'" class="p-6">
                        <h4 class="font-semibold text-gray-800 mb-4">KYC Information</h4>
                        <div class="grid grid-cols-2 gap-4 text-sm mb-6">
                            <div><p class="text-xs font-medium text-gray-500 uppercase">ID Number</p><p class="text-gray-800 font-mono mt-0.5">{{ $user->id_number ?? '—' }}</p></div>
                            <div><p class="text-xs font-medium text-gray-500 uppercase">Birth Year</p><p class="text-gray-800 mt-0.5">{{ $user->birth_year ?? '—' }}</p></div>
                            <div class="col-span-2"><p class="text-xs font-medium text-gray-500 uppercase">Address</p><p class="text-gray-800 mt-0.5">{{ $user->address ?? '—' }}</p></div>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div class="border border-gray-200 overflow-hidden">
                                <p class="text-xs font-medium text-gray-500 uppercase tracking-wider px-4 py-2 bg-gray-50 border-b border-gray-200">ID Front</p>
                                @if($user->id_front_path)
                                    <a href="/storage/{{ $user->id_front_path }}" target="_blank">
                                        <img src="/storage/{{ $user->id_front_path }}" alt="ID Front" class="w-full h-44 object-cover hover:opacity-90 transition">
                                    </a>
                                @else
                                    <div class="p-4 text-gray-400 text-xs text-center">Not uploaded</div>
                                @endif
                            </div>
                            <div class="border border-gray-200 overflow-hidden">
                                <p class="text-xs font-medium text-gray-500 uppercase tracking-wider px-4 py-2 bg-gray-50 border-b border-gray-200">ID Back</p>
                                @if($user->id_back_path)
                                    <a href="/storage/{{ $user->id_back_path }}" target="_blank">
                                        <img src="/storage/{{ $user->id_back_path }}" alt="ID Back" class="w-full h-44 object-cover hover:opacity-90 transition">
                                    </a>
                                @else
                                    <div class="p-4 text-gray-400 text-xs text-center">Not uploaded</div>
                                @endif
                            </div>
                            <div class="border border-gray-200 overflow-hidden">
                                <p class="text-xs font-medium text-gray-500 uppercase tracking-wider px-4 py-2 bg-gray-50 border-b border-gray-200">Address Proof</p>
                                @if($user->address_proof_path)
                                    <a href="/storage/{{ $user->address_proof_path }}" target="_blank">
                                        <img src="/storage/{{ $user->address_proof_path }}" alt="Address Proof" class="w-full h-44 object-cover hover:opacity-90 transition">
                                    </a>
                                @else
                                    <div class="p-4 text-gray-400 text-xs text-center">Not uploaded</div>
                                @endif
                            </div>
                            <div class="border border-gray-200 overflow-hidden">
                                <p class="text-xs font-medium text-gray-500 uppercase tracking-wider px-4 py-2 bg-gray-50 border-b border-gray-200">Profile Photo</p>
                                @if($user->profile_photo_path)
                                    <a href="/storage/{{ $user->profile_photo_path }}" target="_blank">
                                        <img src="/storage/{{ $user->profile_photo_path }}" alt="Profile Photo" class="w-full h-44 object-cover hover:opacity-90 transition">
                                    </a>
                                @else
                                    <div class="p-4 text-gray-400 text-xs text-center">Not uploaded</div>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- Tab: Payout Info --}}
                    <div x-show="tab === 'payout'" class="p-6">
                        <h4 class="font-semibold text-gray-800 mb-4">Payout Details</h4>
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm border-collapse">
                                <tbody>
                                    <tr class="border-b border-gray-100">
                                        <td class="py-3 pr-6 text-xs font-medium text-gray-500 uppercase tracking-wider w-40">Method</td>
                                        <td class="py-3 text-gray-800 font-medium">{{ strtoupper($user->withdrawal_method ?? '—') }}</td>
                                    </tr>
                                    @if($user->withdrawal_method === 'mpesa')
                                        <tr class="border-b border-gray-100">
                                            <td class="py-3 pr-6 text-xs font-medium text-gray-500 uppercase tracking-wider">MPESA Phone</td>
                                            <td class="py-3 text-gray-800 font-mono">{{ $user->mpesa_phone ?? '—' }}</td>
                                        </tr>
                                    @elseif($user->withdrawal_method === 'bank')
                                        <tr class="border-b border-gray-100">
                                            <td class="py-3 pr-6 text-xs font-medium text-gray-500 uppercase tracking-wider">Bank Name</td>
                                            <td class="py-3 text-gray-800">{{ $user->bank_name ?? '—' }}</td>
                                        </tr>
                                        <tr class="border-b border-gray-100">
                                            <td class="py-3 pr-6 text-xs font-medium text-gray-500 uppercase tracking-wider">Account Name</td>
                                            <td class="py-3 text-gray-800">{{ $user->bank_account_name ?? '—' }}</td>
                                        </tr>
                                        <tr>
                                            <td class="py-3 pr-6 text-xs font-medium text-gray-500 uppercase tracking-wider">Account Number</td>
                                            <td class="py-3 text-gray-800 font-mono">{{ $user->bank_account_number ?? '—' }}</td>
                                        </tr>
                                    @else
                                        <tr>
                                            <td colspan="2" class="py-3 text-gray-400">No payout details set.</td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- Tab: Campaigns --}}
                    <div x-show="tab === 'campaigns'" class="p-6">
                        <h4 class="font-semibold text-gray-800 mb-4">Campaigns by {{ $user->name }}</h4>
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm border-collapse">
                                <thead>
                                    <tr class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider bg-gray-50/50">
                                        <th class="px-3 py-3 text-center border border-gray-200 w-10">No.</th>
                                        <th class="px-3 py-3 border border-gray-200">Title</th>
                                        <th class="px-3 py-3 border border-gray-200">Category</th>
                                        <th class="px-3 py-3 text-right border border-gray-200">Target</th>
                                        <th class="px-3 py-3 text-right border border-gray-200">Raised</th>
                                        <th class="px-3 py-3 border border-gray-200">Status</th>
                                        <th class="px-3 py-3 border border-gray-200">Created</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($campaigns as $campaign)
                                        <tr class="hover:bg-gray-50/50 transition">
                                            <td class="px-3 py-3 text-center text-gray-500 text-xs border border-gray-200">{{ $campaigns->firstItem() + $loop->index }}</td>
                                            <td class="px-3 py-3 font-medium text-gray-800 text-sm border border-gray-200">
                                                <a href="{{ route('admin.campaigns.show', $campaign) }}" class="text-[#CE5F26] hover:underline">{{ $campaign->title }}</a>
                                            </td>
                                            <td class="px-3 py-3 text-gray-500 text-xs border border-gray-200">{{ $campaign->category?->name ?? '—' }}</td>
                                            <td class="px-3 py-3 text-right font-semibold text-gray-900 border border-gray-200">KES {{ number_format($campaign->target_amount, 0) }}</td>
                                            <td class="px-3 py-3 text-right font-semibold text-gray-900 border border-gray-200">KES {{ number_format($campaign->raised_amount, 0) }}</td>
                                            <td class="px-3 py-3 border border-gray-200">
                                                <span class="px-2 py-0.5 text-xs font-semibold
                                                    @if($campaign->status->value === 'active') bg-green-50 text-green-700
                                                    @elseif($campaign->status->value === 'pending_verification') bg-yellow-50 text-yellow-700
                                                    @elseif($campaign->status->value === 'draft') bg-gray-100 text-gray-600
                                                    @else bg-gray-100 text-gray-500 @endif">
                                                    {{ str_replace('_', ' ', ucfirst($campaign->status->value)) }}
                                                </span>
                                            </td>
                                            <td class="px-3 py-3 text-gray-500 text-xs border border-gray-200">{{ $campaign->created_at->format('M j, Y') }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="p-6 text-center text-gray-500 border border-gray-200">No campaigns yet.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-3">{{ $campaigns->links() }}</div>
                    </div>
                </div>
            </div>

            {{-- Sidebar --}}
            <div class="space-y-6">
                <div class="bg-white border border-gray-200 p-6">
                    <h3 class="font-semibold text-lg mb-4" style="color: #1B2A4A;">Actions</h3>
                    <div class="space-y-3">
                        @if(!$user->is_approved)
                            <form action="{{ route('admin.users.activate', $user) }}" method="POST" onsubmit="return confirm('Activate this user? They will get dashboard access and fundraiser role.')">
                                @csrf
                                <button type="submit" class="w-full px-4 py-2.5 text-sm font-semibold text-white transition" style="background-color: #7AA36A;">Activate Account</button>
                            </form>
                        @else
                            <button @click="$dispatch('open-deactivate')" class="w-full px-4 py-2.5 text-sm font-semibold text-white bg-red-600 hover:bg-red-700 transition">Deactivate Account</button>
                        @endif
                        @if($user->kyc_status === 'pending')
                            <form action="{{ route('admin.users.approve', $user) }}" method="POST" onsubmit="return confirm('Approve KYC for this user?')">
                                @csrf
                                <button type="submit" class="w-full px-4 py-2.5 bg-green-600 text-white text-sm font-semibold hover:bg-green-700 transition">Approve KYC</button>
                            </form>
                            <button @click="$dispatch('open-reject-kyc')" class="w-full px-4 py-2.5 bg-red-600 text-white text-sm font-semibold hover:bg-red-700 transition">Reject KYC</button>
                        @elseif($user->kyc_status === 'rejected')
                            <div class="w-full px-4 py-2.5 text-sm text-red-700 bg-red-50 text-center">KYC Rejected — awaiting user resubmission</div>
                        @elseif($user->kyc_status === 'verified')
                            <div class="w-full px-4 py-2.5 text-sm text-green-700 bg-green-50 text-center">KYC Verified</div>
                        @endif
                        <a href="{{ route('admin.users.edit', $user) }}" class="block w-full text-center px-4 py-2.5 text-sm font-semibold text-white transition" style="background-color: #6B7280;">Edit User</a>
                        <button @click="$dispatch('open-delete')" class="w-full px-4 py-2.5 bg-red-600 text-white text-sm font-semibold hover:bg-red-700 transition">Delete User</button>
                    </div>
                </div>

                <div class="bg-white border border-gray-200 p-6">
                    <h3 class="font-semibold text-lg mb-4" style="color: #1B2A4A;">Status</h3>
                    <div class="space-y-4 text-sm">
                        <div class="flex items-center justify-between">
                            <span class="text-gray-600">Account</span>
                            @if($user->is_approved)
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 text-xs font-semibold bg-green-50 text-green-700 rounded">Activated</span>
                            @else
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 text-xs font-semibold bg-gray-100 text-gray-500 rounded">Pending</span>
                            @endif
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-gray-600">KYC</span>
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 text-xs font-semibold rounded-full
                                @if($user->kyc_status === 'verified') bg-green-50 text-green-700
                                @elseif($user->kyc_status === 'rejected') bg-red-50 text-red-700
                                @elseif($user->kyc_status === 'pending') bg-yellow-50 text-yellow-700
                                @else bg-gray-100 text-gray-500 @endif">
                                @if($user->kyc_status === 'pending') KYC Pending Review
                                @else {{ ucfirst($user->kyc_status) }}
                                @endif
                            </span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-gray-600">Email</span>
                            @if($user->email_verified_at)
                                <span class="text-xs text-green-600 font-medium">Verified</span>
                            @else
                                <span class="text-xs text-yellow-600 font-medium">Unverified</span>
                            @endif
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-gray-600">Phone</span>
                            @if($user->phone_verified_at)
                                <span class="text-xs text-green-600 font-medium">Verified</span>
                            @else
                                <span class="text-xs text-yellow-600 font-medium">Unverified</span>
                            @endif
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-gray-600">Roles</span>
                            <div class="text-right">
                                @forelse($user->roles as $role)
                                    <span class="inline-flex items-center px-2 py-0.5 text-xs font-medium rounded bg-[#1B2A4A]/10 text-[#1B2A4A]">{{ $role->name }}</span>
                                @empty
                                    <span class="text-gray-400">—</span>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Deactivate Modal --}}
    <div x-data="{ open: false }"
         @open-deactivate.window="open = true"
         x-show="open"
         x-cloak
         class="fixed inset-0 z-50 flex items-center justify-center bg-black/50"
         @click.outside="open = false">
        <div class="bg-white w-full max-w-lg p-6">
            <h3 class="text-lg font-semibold text-[#1B2A4A] mb-4">Deactivate Account</h3>
            <form action="{{ route('admin.users.deactivate', $user) }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Reason for Deactivation</label>
                    <textarea name="reason" rows="4" required minlength="5"
                              class="w-full border border-gray-200 px-3 py-2 text-sm outline-none focus:border-[#CE5F26] focus:ring-1 focus:ring-[#CE5F26]"
                              placeholder="Explain why the account is being deactivated..."></textarea>
                    <p class="text-xs text-gray-400 mt-1">The user will be notified via email with this reason.</p>
                </div>
                <div class="flex items-center justify-end gap-3">
                    <button type="button" @click="open = false" class="px-4 py-2 text-sm font-medium text-gray-600 hover:text-gray-800 transition">Cancel</button>
                    <button type="submit" class="px-4 py-2 text-sm font-medium text-white transition bg-red-600 hover:bg-red-700">Deactivate</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Reject KYC Modal --}}
    <div x-data="{ open: false }"
         @open-reject-kyc.window="open = true"
         x-show="open"
         x-cloak
         class="fixed inset-0 z-50 flex items-center justify-center bg-black/50"
         @click.outside="open = false">
        <div class="bg-white w-full max-w-lg p-6">
            <h3 class="text-lg font-semibold text-[#1B2A4A] mb-4">Reject KYC</h3>
            <form action="{{ route('admin.users.reject', $user) }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Reason for Rejection</label>
                    <textarea name="reason" rows="4" required minlength="5"
                              class="w-full border border-gray-200 px-3 py-2 text-sm outline-none focus:border-[#CE5F26] focus:ring-1 focus:ring-[#CE5F26]"
                              placeholder="Explain why the KYC documents were rejected..."></textarea>
                    <p class="text-xs text-gray-400 mt-1">The user will be notified via email with this reason.</p>
                </div>
                <div class="flex items-center justify-end gap-3">
                    <button type="button" @click="open = false" class="px-4 py-2 text-sm font-medium text-gray-600 hover:text-gray-800 transition">Cancel</button>
                    <button type="submit" class="px-4 py-2 text-sm font-medium text-white transition bg-red-600 hover:bg-red-700">Reject KYC</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Delete Account Modal --}}
    <div x-data="{ open: false }"
         @open-delete.window="open = true"
         x-show="open"
         x-cloak
         class="fixed inset-0 z-50 flex items-center justify-center bg-black/50"
         @click.outside="open = false">
        <div class="bg-white w-full max-w-lg p-6">
            <h3 class="text-lg font-semibold text-[#1B2A4A] mb-4">Delete Account</h3>
            <form action="{{ route('admin.users.delete', $user) }}" method="POST"
                  onsubmit="return confirm('Are you sure? This action cannot be undone.')">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Reason for Deletion</label>
                    <textarea name="reason" rows="4" required minlength="5"
                              class="w-full border border-gray-200 px-3 py-2 text-sm outline-none focus:border-[#CE5F26] focus:ring-1 focus:ring-[#CE5F26]"
                              placeholder="Explain why the account is being deleted..."></textarea>
                    <p class="text-xs text-gray-400 mt-1">The user will be notified via email with this reason before the account is removed.</p>
                </div>
                <div class="flex items-center justify-end gap-3">
                    <button type="button" @click="open = false" class="px-4 py-2 text-sm font-medium text-gray-600 hover:text-gray-800 transition">Cancel</button>
                    <button type="submit" class="px-4 py-2 text-sm font-medium text-white transition bg-red-600 hover:bg-red-700">Delete Permanently</button>
                </div>
            </form>
        </div>
    </div>
</x-admin-layout>
