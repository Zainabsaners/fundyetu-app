<x-dashboard-layout title="Profile">
    <x-slot name="header">Profile</x-slot>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Left Column: Forms --}}
        <div class="lg:col-span-2">
            <div x-data="{ tab: new URLSearchParams(window.location.search).get('tab') || 'info' }">
                {{-- Tabs --}}
                <div class="flex border-b border-gray-200 mb-6">
                    <button @click="tab = 'info'"
                            :class="tab === 'info' ? 'border-b-2 border-[#1B2A4A] text-[#1B2A4A] font-semibold' : 'text-gray-500 hover:text-gray-700'"
                            class="px-5 py-3 text-sm font-medium transition">
                        Profile Info
                    </button>
                    <button @click="tab = 'password'"
                            :class="tab === 'password' ? 'border-b-2 border-[#1B2A4A] text-[#1B2A4A] font-semibold' : 'text-gray-500 hover:text-gray-700'"
                            class="px-5 py-3 text-sm font-medium transition">
                        Password
                    </button>
                    <button @click="tab = 'kyc'"
                            :class="tab === 'kyc' ? 'border-b-2 border-[#1B2A4A] text-[#1B2A4A] font-semibold' : 'text-gray-500 hover:text-gray-700'"
                            class="px-5 py-3 text-sm font-medium transition">
                        KYC
                    </button>
                    <button @click="tab = 'account'"
                            :class="tab === 'account' ? 'border-b-2 border-[#1B2A4A] text-[#1B2A4A] font-semibold' : 'text-gray-500 hover:text-gray-700'"
                            class="px-5 py-3 text-sm font-medium transition">
                        Account
                    </button>
                </div>

                <div x-show="tab === 'info'" x-cloak>
                    @include('profile.partials.update-profile-information-form')
                </div>
                <div x-show="tab === 'password'" x-cloak>
                    @include('profile.partials.update-password-form')
                </div>
                <div x-show="tab === 'kyc'" x-cloak>
                    <div class="bg-white border border-gray-200 p-6 sm:p-8">
                        @include('profile.partials.update-kyc-form')
                    </div>
                </div>
                <div x-show="tab === 'account'" x-cloak>
                    <div class="bg-white border border-gray-200 p-6 sm:p-8">
                        @include('profile.partials.delete-user-form')
                    </div>
                </div>
            </div>
        </div>

        {{-- Right Column: Activity & Info --}}
        <div class="space-y-6">
            {{-- Account Overview --}}
            <div class="bg-white border border-gray-200 overflow-hidden">
                <div class="px-5 py-4" style="background-color: #1B2A4A;">
                    <h3 class="font-semibold text-white text-sm">Account Overview</h3>
                </div>
                <div class="p-5 space-y-4 text-sm">
                    <div class="flex items-center justify-between">
                        <span class="text-gray-500">Member Since</span>
                        <span class="font-medium text-gray-800">{{ $user->created_at->format('M Y') }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-gray-500">Campaigns</span>
                        <span class="font-medium text-gray-800">{{ $campaignsCount }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-gray-500">Active Campaigns</span>
                        <span class="font-medium text-gray-800">{{ $activeCampaigns }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-gray-500">Donations Made</span>
                        <span class="font-medium text-gray-800">{{ $donationsCount }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-gray-500">KYC Status</span>
                        <span class="font-medium capitalize
                            @if($user->kyc_status === 'verified') text-green-600
                            @elseif($user->kyc_status === 'pending') text-yellow-600
                            @elseif($user->kyc_status === 'rejected') text-red-600
                            @else text-gray-400 @endif">
                            @if($user->kyc_status === 'pending') Pending Review
                            @elseif($user->kyc_status === 'unverified') Unverified
                            @else {{ $user->kyc_status ?? 'incomplete' }}
                            @endif
                        </span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-gray-500">SMS Credits</span>
                        <span class="font-medium text-gray-800">{{ $user->sms_credits }}</span>
                    </div>
                </div>
            </div>

            {{-- Recent Notifications --}}
            <div class="bg-white border border-gray-200 overflow-hidden">
                <div class="px-5 py-4" style="background-color: #1B2A4A;">
                    <h3 class="font-semibold text-white text-sm">Recent Notifications</h3>
                </div>
                <div class="divide-y divide-gray-100 max-h-80 overflow-y-auto">
                    @forelse($notifications as $notification)
                        <div class="px-5 py-3 text-sm {{ $notification->read_at ? 'text-gray-500' : 'text-gray-800 font-medium' }}">
                            <p class="text-xs text-gray-400">{{ $notification->created_at->diffForHumans() }}</p>
                            <p class="mt-0.5">{{ $notification->data['message'] ?? 'Notification' }}</p>
                        </div>
                    @empty
                        <div class="px-5 py-8 text-center text-sm text-gray-400">No notifications yet.</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-dashboard-layout>
