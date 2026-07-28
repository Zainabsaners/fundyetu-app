<x-dashboard-layout title="Verify Email Change">
    <x-slot name="header">Verify Email Change</x-slot>

    <div class="max-w-lg mx-auto">
        <div class="bg-white border border-gray-200 p-6 text-center">
            <div class="w-14 h-14 flex items-center justify-center mx-auto mb-4">
                <svg class="w-7 h-7 text-[#CE5F26]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
            </div>

            <h3 class="text-lg font-semibold text-gray-800 mb-2">Enter Verification Code</h3>
            <p class="text-sm text-gray-500 mb-6">A 6-digit code has been sent to your current email address. Enter it to confirm the change.</p>

            @if($errors->any())
                <div class="mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 text-sm">
                    {{ $errors->first() }}
                </div>
            @endif

            <form action="{{ route('profile.verify-email-otp.post') }}" method="POST">
                @csrf
                <div class="flex justify-center gap-2 mb-6">
                    <input type="text" name="code" id="code" maxlength="6"
                           class="w-48 text-center text-2xl font-bold tracking-[0.5em] border border-gray-300 px-4 py-3 outline-none focus:border-[#CE5F26] focus:ring-1 focus:ring-[#CE5F26]"
                           placeholder="000000" inputmode="numeric" autocomplete="one-time-code" autofocus>
                </div>

                <button type="submit" class="w-full px-6 py-2.5 bg-[#CE5F26] text-white font-semibold hover:bg-[#B04E1E] transition">
                    Confirm Change
                </button>
            </form>

            <a href="{{ route('profile.edit') }}" class="mt-3 inline-block text-sm text-gray-500 hover:text-gray-700 transition">
                Cancel
            </a>
        </div>
    </div>
</x-dashboard-layout>