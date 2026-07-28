<x-guest-layout>
    @section('title', 'Confirm Password - ' . config('app.name'))

    <div class="space-y-6">
        <div class="text-center">
            <h1 class="text-3xl font-black text-gray-900 tracking-tight">Confirm Password</h1>
            <p class="mt-2 text-gray-500">This is a secure area. Please confirm your password before continuing.</p>
        </div>

        <form method="POST" action="{{ route('password.confirm') }}" class="space-y-5">
            @csrf

            <div>
                <label for="password" class="block text-sm font-semibold text-gray-700 mb-1.5">Password</label>
                <input id="password" type="password" name="password" required autocomplete="current-password"
                    class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-white text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-maroon/20 focus:border-maroon transition @error('password') border-red-300 @enderror"
                    placeholder="&#8226;&#8226;&#8226;&#8226;&#8226;&#8226;&#8226;&#8226;">
                <x-input-error :messages="$errors->get('password')" class="mt-1.5" />
            </div>

            <button type="submit"
                class="w-full py-3.5 bg-maroon text-white font-bold rounded-full hover:bg-maroon-dark transition shadow-lg shadow-maroon/20">
                Confirm
            </button>
        </form>

        <p class="text-center text-sm text-gray-500">
            <a href="{{ route('login') }}" class="font-semibold text-maroon hover:text-navy-dark transition">Back to sign in</a>
        </p>
    </div>
</x-guest-layout>
