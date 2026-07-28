<section>
    <header>
        <h2 class="text-lg font-semibold text-gray-800">KYC Verification</h2>
        <p class="text-sm text-gray-500 mt-1">Complete your identity verification and payout details.</p>
    </header>

    @php $kyc = auth()->user()->kyc_status; @endphp

    {{-- Verified — read-only summary --}}
    @if($kyc === 'verified')
        <div class="mt-4 p-4 bg-green-50 border border-green-200">
            <div class="flex items-center gap-2 mb-2">
                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                <span class="font-semibold text-green-800">KYC Verified</span>
            </div>
            <p class="text-sm text-green-700">Your KYC has been approved. You can start fundraising.</p>
        </div>

        <div class="mt-6 space-y-4 text-sm">
            <div class="grid grid-cols-2 gap-4">
                <div><p class="text-xs font-medium text-gray-500 uppercase">ID Number</p><p class="text-gray-800">{{ auth()->user()->id_number }}</p></div>
                <div><p class="text-xs font-medium text-gray-500 uppercase">Birth Year</p><p class="text-gray-800">{{ auth()->user()->birth_year ?? '—' }}</p></div>
                <div class="col-span-2"><p class="text-xs font-medium text-gray-500 uppercase">Address</p><p class="text-gray-800">{{ auth()->user()->address ?? '—' }}</p></div>
                <div><p class="text-xs font-medium text-gray-500 uppercase">Withdrawal Method</p><p class="text-gray-800">{{ strtoupper(auth()->user()->withdrawal_method ?? '—') }}</p></div>
                @if(auth()->user()->withdrawal_method === 'mpesa')
                    <div><p class="text-xs font-medium text-gray-500 uppercase">MPESA Phone</p><p class="text-gray-800">{{ auth()->user()->mpesa_phone }}</p></div>
                @else
                    <div><p class="text-xs font-medium text-gray-500 uppercase">Bank</p><p class="text-gray-800">{{ auth()->user()->bank_name }} ({{ auth()->user()->bank_account_number }})</p></div>
                @endif
            </div>
        </div>

    {{-- Rejected — show form to re-apply --}}
    @elseif($kyc === 'rejected')
        <div class="mt-4 p-4 bg-red-50 border border-red-200">
            <div class="flex items-center gap-2 mb-2">
                <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                <span class="font-semibold text-red-800">KYC Rejected</span>
            </div>
            <p class="text-sm text-red-700">Your application was rejected. Update your information below to re-apply.</p>
        </div>
        @include('profile.partials.kyc-form-fields')

    {{-- Unverified — show form --}}
    @elseif($kyc === 'unverified')
        <p class="text-sm text-gray-500 mt-2">Complete your KYC to start fundraising.</p>
        @include('profile.partials.kyc-form-fields')

    {{-- Pending — read-only summary --}}
    @elseif($kyc === 'pending')
        <div class="mt-4 p-4 bg-yellow-50 border border-yellow-200">
            <div class="flex items-center gap-2 mb-2">
                <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span class="font-semibold text-yellow-800">Pending Verification</span>
            </div>
            <p class="text-sm text-yellow-700">Your KYC is under review. You'll be notified once approved.</p>
        </div>

        <div class="mt-6 space-y-4 text-sm">
            <div class="grid grid-cols-2 gap-4">
                <div><p class="text-xs font-medium text-gray-500 uppercase">ID Number</p><p class="text-gray-800">{{ auth()->user()->id_number }}</p></div>
                <div><p class="text-xs font-medium text-gray-500 uppercase">Birth Year</p><p class="text-gray-800">{{ auth()->user()->birth_year ?? '—' }}</p></div>
                <div class="col-span-2"><p class="text-xs font-medium text-gray-500 uppercase">Address</p><p class="text-gray-800">{{ auth()->user()->address ?? '—' }}</p></div>
                <div><p class="text-xs font-medium text-gray-500 uppercase">Withdrawal Method</p><p class="text-gray-800">{{ strtoupper(auth()->user()->withdrawal_method ?? '—') }}</p></div>
                @if(auth()->user()->withdrawal_method === 'mpesa')
                    <div><p class="text-xs font-medium text-gray-500 uppercase">MPESA Phone</p><p class="text-gray-800">{{ auth()->user()->mpesa_phone }}</p></div>
                @else
                    <div><p class="text-xs font-medium text-gray-500 uppercase">Bank</p><p class="text-gray-800">{{ auth()->user()->bank_name }} ({{ auth()->user()->bank_account_number }})</p></div>
                @endif
            </div>

            <div class="border-t border-gray-100 pt-4">
                <p class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-3">Uploaded Documents</p>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-xs text-gray-500 mb-1">ID Front</p>
                        @if(auth()->user()->id_front_path)
                            <a href="{{ asset('storage/' . auth()->user()->id_front_path) }}" target="_blank" class="block border border-gray-200 overflow-hidden hover:border-[#CE5F26] transition">
                                <img src="{{ asset('storage/' . auth()->user()->id_front_path) }}" alt="ID Front" class="w-full h-32 object-cover">
                            </a>
                        @else
                            <p class="text-xs text-gray-400 italic">Not uploaded</p>
                        @endif
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 mb-1">ID Back</p>
                        @if(auth()->user()->id_back_path)
                            <a href="{{ asset('storage/' . auth()->user()->id_back_path) }}" target="_blank" class="block border border-gray-200 overflow-hidden hover:border-[#CE5F26] transition">
                                <img src="{{ asset('storage/' . auth()->user()->id_back_path) }}" alt="ID Back" class="w-full h-32 object-cover">
                            </a>
                        @else
                            <p class="text-xs text-gray-400 italic">Not uploaded</p>
                        @endif
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 mb-1">Address Proof</p>
                        @if(auth()->user()->address_proof_path)
                            <a href="{{ asset('storage/' . auth()->user()->address_proof_path) }}" target="_blank" class="block border border-gray-200 overflow-hidden hover:border-[#CE5F26] transition">
                                <img src="{{ asset('storage/' . auth()->user()->address_proof_path) }}" alt="Address Proof" class="w-full h-32 object-cover">
                            </a>
                        @else
                            <p class="text-xs text-gray-400 italic">Not uploaded</p>
                        @endif
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 mb-1">Profile Photo</p>
                        @if(auth()->user()->profile_photo_path)
                            <a href="{{ asset('storage/' . auth()->user()->profile_photo_path) }}" target="_blank" class="block border border-gray-200 overflow-hidden hover:border-[#CE5F26] transition">
                                <img src="{{ asset('storage/' . auth()->user()->profile_photo_path) }}" alt="Profile Photo" class="w-full h-32 object-cover">
                            </a>
                        @else
                            <p class="text-xs text-gray-400 italic">Not uploaded</p>
                        @endif
                    </div>
                </div>
            </div>

            <p class="text-xs text-gray-400">Editing is disabled until the admin completes the review.</p>
        </div>

    {{-- Incomplete (null / no submission) — show form --}}
    @else
        <p class="text-sm text-gray-500 mt-2">Complete your KYC to start fundraising.</p>
        @include('profile.partials.kyc-form-fields')
    @endif
</section>
