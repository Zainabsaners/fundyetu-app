<div class="bg-white border border-gray-200 overflow-hidden">
    <div class="p-5" style="background-color: #1B2A4A;">
        <h2 class="font-semibold text-white">Update Password</h2>
    </div>

    <div class="p-6 sm:p-8">
        <p class="text-sm text-gray-500 mb-6">A verification code will be sent to your phone to confirm the change.</p>

        <form method="post" action="{{ route('password.update') }}" class="space-y-5">
            @csrf
            @method('put')

            <div>
                <label for="update_password_current_password" class="block text-sm font-medium text-gray-700 mb-1.5">Current Password</label>
                <input id="update_password_current_password" name="current_password" type="password" autocomplete="current-password"
                       class="w-full border border-gray-200 px-4 py-3 text-sm focus:border-[#CE5F26] focus:ring-1 focus:ring-[#CE5F26] outline-none">
                @error('current_password', 'updatePassword') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="update_password_password" class="block text-sm font-medium text-gray-700 mb-1.5">New Password</label>
                <input id="update_password_password" name="password" type="password" autocomplete="new-password"
                       class="w-full border border-gray-200 px-4 py-3 text-sm focus:border-[#CE5F26] focus:ring-1 focus:ring-[#CE5F26] outline-none">
                @error('password', 'updatePassword') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="update_password_password_confirmation" class="block text-sm font-medium text-gray-700 mb-1.5">Confirm New Password</label>
                <input id="update_password_password_confirmation" name="password_confirmation" type="password" autocomplete="new-password"
                       class="w-full border border-gray-200 px-4 py-3 text-sm focus:border-[#CE5F26] focus:ring-1 focus:ring-[#CE5F26] outline-none">
                @error('password_confirmation', 'updatePassword') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="flex items-center gap-4 pt-2">
                <button type="submit" class="px-6 py-2.5 bg-[#CE5F26] text-white text-sm font-semibold hover:bg-[#B04E1E] transition">
                    Update Password
                </button>
                @if (session('status') === 'password-updated')
                    <span class="text-sm text-green-600 font-medium">Saved.</span>
                @endif
            </div>
        </form>
    </div>
</div>