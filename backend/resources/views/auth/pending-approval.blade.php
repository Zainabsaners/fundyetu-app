<x-guest-layout>
    @section('title', 'Pending Approval - ' . config('app.name'))

    <div class="text-center space-y-6">
        <h1 class="text-2xl font-black text-gray-900 tracking-tight">Account Setup</h1>

        <p class="text-gray-500 leading-relaxed max-w-sm mx-auto">
            Complete the steps below to activate your account.
        </p>

        <div class="bg-gray-50 rounded-xl p-6 text-left">
            <div class="space-y-0">
                {{-- Step 1: Email Verification --}}
                <div class="flex gap-4" x-data="{ editing: false }">
                    <div class="flex flex-col items-center">
                        <div class="relative z-10 w-10 h-10 rounded-full flex items-center justify-center shrink-0
                            @if (auth()->user()->hasVerifiedEmail()) bg-green-100
                            @else bg-gray-100 @endif">
                            @if (auth()->user()->hasVerifiedEmail())
                                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                            @else
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                            @endif
                        </div>
                        <div class="w-0.5 flex-1 min-h-[2rem]
                            @if (auth()->user()->hasVerifiedEmail()) bg-green-300
                            @else bg-gray-200 @endif">
                        </div>
                    </div>
                    <div class="pb-6 flex-1 min-w-0">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-semibold
                                    @if (auth()->user()->hasVerifiedEmail()) text-green-700
                                    @else text-gray-700 @endif">
                                    Verify Email
                                </p>
                                @if (auth()->user()->hasVerifiedEmail())
                                    <p class="text-xs text-gray-400">{{ auth()->user()->email }}</p>
                                @else
                                    <form method="POST" action="{{ route('pending.contact.update') }}" class="inline">
                                        @csrf
                                        <div x-show="!editing" class="flex items-center gap-2">
                                            <span class="text-xs text-gray-400">{{ auth()->user()->email }}</span>
                                            <button type="button" @click="editing = true" class="text-[10px] font-medium text-[#CE5F26] hover:text-[#B04E1E]">Change</button>
                                        </div>
                                        <div x-show="editing" x-cloak class="flex items-center gap-2 mt-1">
                                            <input type="email" name="email" value="{{ auth()->user()->email }}" required
                                                   class="text-xs px-2 py-1 border border-gray-300 rounded w-48 focus:outline-none focus:border-[#CE5F26]">
                                            <input type="hidden" name="phone" value="{{ auth()->user()->phone }}">
                                            <button type="submit" class="text-[10px] font-medium text-white bg-[#CE5F26] hover:bg-[#B04E1E] px-2 py-1 rounded">Save</button>
                                            <button type="button" @click="editing = false" class="text-[10px] font-medium text-gray-500 hover:text-gray-700">Cancel</button>
                                        </div>
                                    </form>
                                @endif
                            </div>
                            @unless (auth()->user()->hasVerifiedEmail())
                                <a href="{{ route('verification.notice') }}" class="text-xs font-semibold text-[#CE5F26] hover:text-[#B04E1E] shrink-0 ml-3">
                                    Verify
                                </a>
                            @endunless
                        </div>
                    </div>
                </div>

                {{-- Step 2: Phone Verification --}}
                <div class="flex gap-4" x-data="{ editing: false }">
                    <div class="flex flex-col items-center">
                        <div class="relative z-10 w-10 h-10 rounded-full flex items-center justify-center shrink-0
                            @if (auth()->user()->hasVerifiedPhone()) bg-green-100
                            @else bg-gray-100 @endif">
                            @if (auth()->user()->hasVerifiedPhone())
                                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                            @else
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            @endif
                        </div>
                        <div class="w-0.5 flex-1 min-h-[2rem]
                            @if (auth()->user()->hasVerifiedPhone()) bg-green-300
                            @else bg-gray-200 @endif">
                        </div>
                    </div>
                    <div class="pb-6 flex-1 min-w-0">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-semibold
                                    @if (auth()->user()->hasVerifiedPhone()) text-green-700
                                    @else text-gray-700 @endif">
                                    Verify Phone
                                </p>
                                @if (auth()->user()->hasVerifiedPhone())
                                    <p class="text-xs text-gray-400">{{ auth()->user()->phone }}</p>
                                @else
                                    <form method="POST" action="{{ route('pending.contact.update') }}" class="inline">
                                        @csrf
                                        <div x-show="!editing" class="flex items-center gap-2">
                                            <span class="text-xs text-gray-400">{{ auth()->user()->phone }}</span>
                                            <button type="button" @click="editing = true" class="text-[10px] font-medium text-[#CE5F26] hover:text-[#B04E1E]">Change</button>
                                        </div>
                                        <div x-show="editing" x-cloak class="flex items-center gap-2 mt-1">
                                            <input type="hidden" name="email" value="{{ auth()->user()->email }}">
                                            <input type="text" name="phone" value="{{ auth()->user()->phone }}" required
                                                   class="text-xs px-2 py-1 border border-gray-300 rounded w-48 focus:outline-none focus:border-[#CE5F26]">
                                            <button type="submit" class="text-[10px] font-medium text-white bg-[#CE5F26] hover:bg-[#B04E1E] px-2 py-1 rounded">Save</button>
                                            <button type="button" @click="editing = false" class="text-[10px] font-medium text-gray-500 hover:text-gray-700">Cancel</button>
                                        </div>
                                    </form>
                                @endif
                            </div>
                            @unless (auth()->user()->hasVerifiedPhone())
                                <a href="{{ route('phone.verification.notice') }}" class="text-xs font-semibold text-[#CE5F26] hover:text-[#B04E1E] shrink-0 ml-3">
                                    Verify
                                </a>
                            @endunless
                        </div>
                    </div>
                </div>

                {{-- Step 3: Admin Approval --}}
                <div class="flex gap-4">
                    <div class="flex flex-col items-center">
                        <div class="relative z-10 w-10 h-10 rounded-full flex items-center justify-center shrink-0
                            @if (auth()->user()->is_approved) bg-green-100
                            @elseif (auth()->user()->hasVerifiedEmail() && auth()->user()->hasVerifiedPhone()) bg-gray-100
                            @else bg-gray-100 @endif">
                            @if (auth()->user()->is_approved)
                                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                            @else
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            @endif
                        </div>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-semibold
                                    @if (auth()->user()->is_approved) text-green-700
                                    @elseif (auth()->user()->hasVerifiedEmail() && auth()->user()->hasVerifiedPhone()) text-gray-700
                                    @else text-gray-500 @endif">
                                    @if (auth()->user()->is_approved)
                                        Account Approved
                                    @elseif (auth()->user()->hasVerifiedEmail() && auth()->user()->hasVerifiedPhone())
                                        Pending Admin Approval
                                    @else
                                        Admin Approval
                                    @endif
                                </p>
                                <p class="text-xs text-gray-400">
                                    @if (auth()->user()->is_approved)
                                        You can now access your dashboard
                                    @elseif (auth()->user()->hasVerifiedEmail() && auth()->user()->hasVerifiedPhone())
                                        An administrator will review your account shortly. You may be contacted for more information. Please check your email and phone for any messages from us.   
                                    @else
                                        Complete email &amp; phone verification first
                                    @endif
                                </p>
                            </div>
                            @if (auth()->user()->is_approved)
                                @php $isAdmin = auth()->user()->roles->pluck('name')->intersect(['admin', 'super_admin'])->isNotEmpty(); @endphp
                                <a href="{{ $isAdmin ? route('admin.dashboard') : route('dashboard') }}" class="text-xs font-semibold text-[#CE5F26] hover:text-[#B04E1E] shrink-0 ml-3">
                                    Dashboard
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="text-sm text-gray-400 hover:text-gray-600 transition underline">
                Sign out
            </button>
        </form>

        <p class="text-center text-sm text-gray-500">
            <a href="{{ route('home') }}" class="text-gray-400 hover:text-gray-600 transition">&larr; Back to home</a>
        </p>
    </div>
</x-guest-layout>
