<x-guest-layout>
    @section('title', 'Reset Password - ' . config('app.name'))

    <div class="space-y-6">
        <div class="text-center">
            <h1 class="text-3xl font-black text-gray-900 tracking-tight">Reset Password</h1>
            <p class="mt-2 text-gray-500">Choose a new password for your account.</p>
        </div>

        <form method="POST" action="{{ route('password.store') }}" class="space-y-5">
            @csrf

            <input type="hidden" name="token" value="{{ $request->route('token') }}">

            <div>
                <label for="email" class="block text-sm font-semibold text-gray-700 mb-1.5">Email</label>
                <input id="email" type="email" name="email" value="{{ old('email', $request->email) }}" required autofocus autocomplete="username"
                    class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-white text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-maroon/20 focus:border-maroon transition @error('email') border-red-300 @enderror"
                    placeholder="you@example.com">
                <x-input-error :messages="$errors->get('email')" class="mt-1.5" />
            </div>

            <div>
                <label for="password" class="block text-sm font-semibold text-gray-700 mb-1.5">Password</label>
                <input id="password" type="password" name="password" required autocomplete="new-password"
                    class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-white text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-maroon/20 focus:border-maroon transition @error('password') border-red-300 @enderror"
                    placeholder="&#8226;&#8226;&#8226;&#8226;&#8226;&#8226;&#8226;&#8226;">
                <x-input-error :messages="$errors->get('password')" class="mt-1.5" />
            </div>

            <div>
                <label for="password_confirmation" class="block text-sm font-semibold text-gray-700 mb-1.5">Confirm Password</label>
                <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password"
                    class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-white text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-maroon/20 focus:border-maroon transition"
                    placeholder="&#8226;&#8226;&#8226;&#8226;&#8226;&#8226;&#8226;&#8226;">
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1.5" />
            </div>

            <button type="submit"
                class="w-full py-3.5 bg-maroon text-white font-bold rounded-full hover:bg-maroon-dark transition shadow-lg shadow-maroon/20">
                Reset Password
            </button>
        </form>

        <p class="text-center text-sm text-gray-500">
            <a href="{{ route('login') }}" class="font-semibold text-maroon hover:text-navy-dark transition">Back to sign in</a>
        </p>
    </div>
</x-guest-layout>
