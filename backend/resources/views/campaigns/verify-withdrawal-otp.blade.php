<x-dashboard-layout title="Confirm Withdrawal">
    <x-slot name="header">Confirm Withdrawal</x-slot>

    <div class="max-w-lg mx-auto">
        <div class="bg-white border border-gray-200 p-6 text-center">
            <div class="w-14 h-14 flex items-center justify-center mx-auto mb-4">
                <svg class="w-7 h-7 text-[#CE5F26]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                </svg>
            </div>

            <h3 class="text-lg font-semibold text-gray-800 mb-2">Enter Verification Code</h3>
            @isset($treasurer)
                <p class="text-sm text-gray-500 mb-1">A 6-digit code has been sent to the treasurer at <strong>{{ $treasurer->phone }}</strong></p>
                <p class="text-xs text-gray-400 mb-6">Treasurer approval required for withdrawal from <strong>{{ $campaign->title }}</strong></p>
            @else
                <p class="text-sm text-gray-500 mb-1">A 6-digit code has been sent to <strong>{{ $campaign->user->phone }}</strong></p>
                <p class="text-xs text-gray-400 mb-6">Enter the code to confirm your withdrawal request for <strong>{{ $campaign->title }}</strong></p>
            @endisset

            @if($errors->any())
                <div class="mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 text-sm">
                    {{ $errors->first() }}
                </div>
            @endif

            @if(session('status') === 'otp-resent')
                <div class="mb-4 bg-green-50 border border-green-200 text-green-700 px-4 py-3 text-sm">
                    A new code has been sent to your phone.
                </div>
            @endif

            @isset($treasurer)
                <form action="{{ route('campaigns.withdrawals.treasurer-otp.post', $campaign) }}" method="POST">
            @else
                <form action="{{ route('campaigns.withdrawals.verify-otp.post', $campaign) }}" method="POST">
            @endisset
                @csrf
                <div class="flex justify-center gap-2 mb-6">
                    <input type="text" name="code" id="code" maxlength="6"
                           class="w-48 text-center text-2xl font-bold tracking-[0.5em] border border-gray-300 px-4 py-3 outline-none focus:border-[#CE5F26] focus:ring-1 focus:ring-[#CE5F26]"
                           placeholder="000000" inputmode="numeric" autocomplete="one-time-code" autofocus>
                </div>

                <button type="submit" class="w-full px-6 py-2.5 bg-[#CE5F26] text-white font-semibold hover:bg-[#B04E1E] transition">
                    Confirm Withdrawal
                </button>
            </form>

            <form action="{{ route('campaigns.withdrawals.resend-otp', $campaign) }}" method="POST" class="mt-3">
                @csrf
                <button type="submit" class="text-sm text-[#CE5F26] hover:text-[#B04E1E] transition">
                    Resend code
                </button>
            </form>

            <form action="{{ route('campaigns.withdrawals', $campaign) }}" method="GET" class="mt-1">
                <button type="submit" class="text-sm text-gray-500 hover:text-gray-700 transition">
                    Cancel
                </button>
            </form>
        </div>
    </div>
</x-dashboard-layout>