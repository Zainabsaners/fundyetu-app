<div class="bg-white border border-gray-200 overflow-hidden">
    <div class="p-5 border-b border-gray-100" style="background-color: #1B2A4A;">
        <h2 class="font-semibold text-white">Profile Information</h2>
    </div>

    <div class="p-6 sm:p-8">
        <form method="post" action="{{ route('profile.update') }}" class="space-y-5">
            @csrf
            @method('patch')

            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-1.5">Name</label>
                <input id="name" name="name" type="text" value="{{ old('name', $user->name) }}" required autofocus autocomplete="name"
                       class="w-full border border-gray-200 px-4 py-3 text-sm focus:border-[#CE5F26] focus:ring-1 focus:ring-[#CE5F26] outline-none">
                @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="phone" class="block text-sm font-medium text-gray-700 mb-1.5">Phone</label>
                <input id="phone" name="phone" type="text" value="{{ old('phone', $user->phone) }}" readonly
                       class="w-full border border-gray-200 px-4 py-3 text-sm bg-gray-50 text-gray-500 outline-none cursor-not-allowed">
                <p class="text-xs text-gray-400 mt-1">Phone cannot be changed here. Contact support for assistance.</p>
                @error('phone') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-1.5">Email</label>
                <input id="email" name="email" type="email" value="{{ old('email', $user->email) }}" required autocomplete="username"
                       class="w-full border border-gray-200 px-4 py-3 text-sm focus:border-[#CE5F26] focus:ring-1 focus:ring-[#CE5F26] outline-none">
                @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror

                @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                    <div class="mt-2">
                        <p class="text-sm text-gray-600">
                            Your email address is unverified.
                            <a href="{{ route('verification.send') }}" class="text-[#CE5F26] hover:text-[#B04E1E] underline text-sm">
                                Resend verification email.
                            </a>
                        </p>
                        @if (session('status') === 'verification-link-sent')
                            <p class="mt-1 text-sm text-green-600">A new verification link has been sent.</p>
                        @endif
                    </div>
                @endif
            </div>

            <div class="flex items-center gap-4 pt-2">
                <button type="submit" class="px-6 py-2.5 bg-[#CE5F26] text-white text-sm font-semibold hover:bg-[#B04E1E] transition">
                    Save
                </button>
                @if (session('status') === 'profile-updated')
                    <span class="text-sm text-green-600 font-medium">Saved.</span>
                @endif
            </div>
        </form>
    </div>
</div>