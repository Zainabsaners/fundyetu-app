<x-guest-layout>
    @section('title', 'Verify Phone - ' . config('app.name'))

    <div class="space-y-6">
        <div class="text-center">
            <h1 class="text-3xl font-black text-gray-900 tracking-tight">Verify Your Phone</h1>
            <p class="mt-2 text-gray-500">Enter the 6-digit code sent to <strong>{{ auth()->user()->phone }}</strong>.</p>
        </div>

        @if (session('status') === 'verification-code-sent')
            <div class="bg-green-50 border border-green-200 text-green-700 px-5 py-4 rounded-xl text-sm font-medium">
                A verification code has been sent to your phone.
            </div>
        @endif

        <form method="POST" action="{{ route('phone.verify') }}" class="space-y-5">
            @csrf

            <div>
                <label for="code" class="block text-sm font-semibold text-gray-700 mb-1.5">Verification Code</label>
                <input id="code" type="text" name="code" inputmode="numeric" maxlength="6" autocomplete="one-time-code"
                    class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-white text-gray-900 text-center text-2xl tracking-[0.5em] placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#CE5F26]/20 focus:border-[#CE5F26] transition @error('code') border-red-300 @enderror"
                    placeholder="000000">
                <x-input-error :messages="$errors->get('code')" class="mt-1.5" />
            </div>

            <button type="submit"
                class="w-full py-3.5 bg-[#CE5F26] text-white font-bold rounded-full hover:bg-[#B04E1E] transition shadow-lg shadow-[#CE5F26]/30">
                Verify Phone
            </button>
        </form>

        <form method="POST" action="{{ route('phone.resend') }}" class="text-center">
            @csrf
            <button type="submit" class="text-sm text-[#CE5F26] hover:text-[#B04E1E] font-medium transition underline">
                Resend code
            </button>
        </form>

        <form method="POST" action="{{ route('logout') }}" class="text-center">
            @csrf
            <button type="submit" class="text-sm text-gray-400 hover:text-gray-600 transition underline">
                Sign out
            </button>
        </form>

        <p class="text-center text-sm text-gray-500">
            <a href="{{ route('pending.approval') }}" class="text-gray-400 hover:text-gray-600 transition">&larr; Back to account setup</a>
        </p>
    </div>
</x-guest-layout>
