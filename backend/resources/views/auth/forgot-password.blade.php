<x-guest-layout>
    @section('title', 'Forgot Password - ' . config('app.name'))

    <div class="space-y-6">
        <div class="text-center">
            <h1 class="text-3xl font-black text-gray-900 tracking-tight">Forgot Password?</h1>
            <p class="mt-2 text-gray-500">No problem. Enter your email and we'll send you a reset link.</p>
        </div>

        <x-auth-session-status class="mb-4" :status="session('status')" />

        <form method="POST" action="{{ route('password.email') }}" class="space-y-5">
            @csrf

            <div>
                <label for="email" class="block text-sm font-semibold text-gray-700 mb-1.5">Email</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                    class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-white text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#CE5F26]/20 focus:border-[#CE5F26] transition @error('email') border-red-300 @enderror"
                    placeholder="you@example.com">
                <x-input-error :messages="$errors->get('email')" class="mt-1.5" />
            </div>

            <button type="submit"
                class="w-full py-3.5 bg-[#CE5F26] text-white font-bold rounded-full hover:bg-[#B04E1E] transition shadow-lg shadow-[#CE5F26]/30">
                Email Password Reset Link
            </button>
        </form>

        <p class="text-center text-sm text-gray-500">
            Remember your password?
            <a href="{{ route('login') }}" class="font-semibold text-[#CE5F26] hover:text-navy-dark transition">Sign in</a>
        </p>

        <p class="text-center text-sm text-gray-500">
            <a href="{{ route('home') }}" class="text-gray-400 hover:text-gray-600 transition">&larr; Back to home</a>
        </p>
    </div>
</x-guest-layout>
