<x-admin-layout title="Edit User">
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <span>Edit: {{ $user->name }}</span>
            <a href="{{ route('admin.users.index') }}" class="text-sm font-medium text-[#CE5F26] hover:text-[#B04E1E] transition">&larr; Back to Users</a>
        </div>
    </x-slot>

    <div class="max-w-2xl mx-auto">
        <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100">
                <h3 class="font-semibold text-gray-800">User Information</h3>
            </div>
            <form action="{{ route('admin.users.update', $user) }}" method="POST" class="p-6 space-y-5">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
                        <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required
                               class="w-full border border-gray-200 px-3 py-2 text-sm outline-none focus:border-[#CE5F26] focus:ring-1 focus:ring-[#CE5F26]">
                        @error('name') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                        <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required
                               class="w-full border border-gray-200 px-3 py-2 text-sm outline-none focus:border-[#CE5F26] focus:ring-1 focus:ring-[#CE5F26]">
                        @error('email') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Phone</label>
                        <input type="text" name="phone" id="phone" value="{{ old('phone', $user->phone) }}"
                               class="w-full border border-gray-200 px-3 py-2 text-sm outline-none focus:border-[#CE5F26] focus:ring-1 focus:ring-[#CE5F26]">
                        @error('phone') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="id_number" class="block text-sm font-medium text-gray-700 mb-1">ID Number</label>
                        <input type="text" name="id_number" id="id_number" value="{{ old('id_number', $user->id_number) }}"
                               class="w-full border border-gray-200 px-3 py-2 text-sm outline-none focus:border-[#CE5F26] focus:ring-1 focus:ring-[#CE5F26]">
                        @error('id_number') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="birth_year" class="block text-sm font-medium text-gray-700 mb-1">Birth Year</label>
                        <input type="number" name="birth_year" id="birth_year" value="{{ old('birth_year', $user->birth_year) }}"
                               class="w-full border border-gray-200 px-3 py-2 text-sm outline-none focus:border-[#CE5F26] focus:ring-1 focus:ring-[#CE5F26]">
                        @error('birth_year') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="kyc_status" class="block text-sm font-medium text-gray-700 mb-1">KYC Status</label>
                        <select name="kyc_status" id="kyc_status"
                                class="w-full border border-gray-200 px-3 py-2 text-sm outline-none focus:border-[#CE5F26] focus:ring-1 focus:ring-[#CE5F26]">
                            <option value="unverified" @if($user->kyc_status === 'unverified') selected @endif>Not Submitted</option>
                            <option value="pending" @if($user->kyc_status === 'pending') selected @endif>Pending Review</option>
                            <option value="verified" @if($user->kyc_status === 'verified') selected @endif>Verified</option>
                            <option value="rejected" @if($user->kyc_status === 'rejected') selected @endif>Rejected</option>
                        </select>
                        @error('kyc_status') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div>
                    <label for="address" class="block text-sm font-medium text-gray-700 mb-1">Address</label>
                    <textarea name="address" id="address" rows="2"
                              class="w-full border border-gray-200 px-3 py-2 text-sm outline-none focus:border-[#CE5F26] focus:ring-1 focus:ring-[#CE5F26]">{{ old('address', $user->address) }}</textarea>
                    @error('address') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="flex items-center justify-end gap-3 pt-2 border-t border-gray-100">
                    <a href="{{ route('admin.users.index') }}" class="px-4 py-2 text-sm font-medium text-gray-600 hover:text-gray-800 transition">Cancel</a>
                    <button type="submit" class="px-5 py-2 text-sm font-medium text-white rounded-lg transition" style="background-color: #CE5F26;">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</x-admin-layout>