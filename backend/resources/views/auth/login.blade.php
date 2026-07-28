<x-guest-layout>
    @section('title', 'Log in - ' . config('app.name'))

    <div class="space-y-6">
        <div class="text-center">
            <h1 class="text-3xl font-black text-gray-900 tracking-tight">Welcome Back</h1>
            <p class="mt-2 text-gray-500">Sign in to manage your fundraisers and donations.</p>
        </div>

        <x-auth-session-status class="mb-4" :status="session('status')" />

        <form method="POST" action="{{ route('login') }}" class="space-y-5">
            @csrf

            <div>
                <label for="email" class="block text-sm font-semibold text-gray-700 mb-1.5">Email</label>
                <input id="email" type="email" name="email" :value="old('email')" required autofocus autocomplete="username"
                    class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-white text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#CE5F26]/20 focus:border-[#CE5F26] transition @error('email') border-red-300 @enderror"
                    placeholder="you@example.com">
                <x-input-error :messages="$errors->get('email')" class="mt-1.5" />
            </div>

            <div>
                <label for="password" class="block text-sm font-semibold text-gray-700 mb-1.5">Password</label>
                <input id="password" type="password" name="password" required autocomplete="current-password"
                    class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-white text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#CE5F26]/20 focus:border-[#CE5F26] transition @error('password') border-red-300 @enderror"
                    placeholder="&#8226;&#8226;&#8226;&#8226;&#8226;&#8226;&#8226;&#8226;">
                <x-input-error :messages="$errors->get('password')" class="mt-1.5" />
            </div>

            <div class="flex items-center justify-between">
                <label for="remember_me" class="inline-flex items-center gap-2.5">
                    <input id="remember_me" type="checkbox" name="remember"
                        class="w-4 h-4 rounded border-gray-300 text-[#CE5F26] focus:ring-[#CE5F26]/30">
                    <span class="text-sm text-gray-600">Remember me</span>
                </label>

                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}"
                       class="text-sm font-medium text-[#CE5F26] hover:text-navy-dark transition">
                        Forgot password?
                    </a>
                @endif
            </div>

            <button type="submit"
                class="w-full py-3.5 bg-[#CE5F26] text-white font-bold rounded-full hover:bg-[#B04E1E] transition shadow-lg shadow-[#CE5F26]/30 hover:shadow-[#CE5F26]/40">
                Sign In
            </button>
        </form>

        <p class="text-center text-sm text-gray-500">
            Don't have an account?
            <a href="{{ route('register') }}" class="font-semibold text-[#CE5F26] hover:text-navy-dark transition">Create one</a>
        </p>

        <p class="text-center text-sm text-gray-500">
            <a href="{{ route('home') }}" class="text-gray-400 hover:text-gray-600 transition">&larr; Back to home</a>
        </p>
    </div>
</x-guest-layout>
