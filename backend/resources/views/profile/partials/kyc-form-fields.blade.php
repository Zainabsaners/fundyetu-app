<form id="kycForm" method="post" action="{{ route('profile.kyc') }}" enctype="multipart/form-data" class="mt-6 space-y-6">
    @csrf
    @method('patch')

    {{-- Personal Info --}}
    <div>
        <h3 class="text-sm font-semibold text-gray-700 uppercase tracking-wider mb-3">Personal Information</h3>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label for="id_number" class="block text-sm font-medium text-gray-700">National ID Number *</label>
                <input id="id_number" name="id_number" type="text" value="{{ old('id_number', auth()->user()->id_number) }}"
                       class="mt-1 block w-full border border-gray-200 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#1B2A4A]/20 focus:border-[#1B2A4A]" required>
                @error('id_number') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label for="birth_year" class="block text-sm font-medium text-gray-700">Year of Birth</label>
                <select id="birth_year" name="birth_year"
                        class="mt-1 block w-full border border-gray-200 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#1B2A4A]/20 focus:border-[#1B2A4A]">
                    <option value="">Select year</option>
                    @foreach(range(date('Y'), 1950) as $year)
                        <option value="{{ $year }}" {{ old('birth_year', auth()->user()->birth_year) == $year ? 'selected' : '' }}>{{ $year }}</option>
                    @endforeach
                </select>
                @error('birth_year') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>
        </div>
    </div>

    {{-- Address --}}
    <div>
        <h3 class="text-sm font-semibold text-gray-700 uppercase tracking-wider mb-3">Address & Location</h3>
        <div>
            <label for="address" class="block text-sm font-medium text-gray-700">Physical Address *</label>
            <textarea id="address" name="address" rows="2"
                      class="mt-1 block w-full border border-gray-200 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#1B2A4A]/20 focus:border-[#1B2A4A]" required>{{ old('address', auth()->user()->address) }}</textarea>
            @error('address') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>
        <div class="mt-3">
            <label class="block text-sm font-medium text-gray-700">Address Proof (utility bill / bank statement)</label>
            <input type="file" name="address_proof" accept="image/*,.pdf"
                   class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:border-0 file:text-sm file:font-medium file:bg-[#1B2A4A]/10 file:text-[#1B2A4A] hover:file:bg-[#1B2A4A]/20">
            @error('address_proof') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
            @if(auth()->user()->address_proof_path)
                <p class="text-xs text-green-600 mt-1">✓ Uploaded</p>
            @endif
        </div>
    </div>

    {{-- ID Uploads --}}
    <div>
        <h3 class="text-sm font-semibold text-gray-700 uppercase tracking-wider mb-3">Identification Documents</h3>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">ID Front</label>
                <input type="file" name="id_front" accept="image/*,.pdf"
                       class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:border-0 file:text-sm file:font-medium file:bg-[#1B2A4A]/10 file:text-[#1B2A4A] hover:file:bg-[#1B2A4A]/20">
                @error('id_front') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                @if(auth()->user()->id_front_path)
                    <p class="text-xs text-green-600 mt-1">✓ Uploaded</p>
                @endif
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">ID Back</label>
                <input type="file" name="id_back" accept="image/*,.pdf"
                       class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:border-0 file:text-sm file:font-medium file:bg-[#1B2A4A]/10 file:text-[#1B2A4A] hover:file:bg-[#1B2A4A]/20">
                @error('id_back') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                @if(auth()->user()->id_back_path)
                    <p class="text-xs text-green-600 mt-1">✓ Uploaded</p>
                @endif
            </div>
        </div>
    </div>

    {{-- Profile Photo --}}
    <div>
        <h3 class="text-sm font-semibold text-gray-700 uppercase tracking-wider mb-3">Profile Photo</h3>
        <input type="file" name="profile_photo" accept="image/*"
               class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:border-0 file:text-sm file:font-medium file:bg-[#1B2A4A]/10 file:text-[#1B2A4A] hover:file:bg-[#1B2A4A]/20">
        @error('profile_photo') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
        @if(auth()->user()->profile_photo_path)
            <p class="text-xs text-green-600 mt-1">✓ Uploaded</p>
        @endif
    </div>

    {{-- Payout Details --}}
    <div>
        <h3 class="text-sm font-semibold text-gray-700 uppercase tracking-wider mb-3">Payout Details</h3>

        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-2">Withdrawal Method *</label>
            <div class="flex gap-4">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="radio" name="withdrawal_method" value="mpesa"
                           {{ old('withdrawal_method', auth()->user()->withdrawal_method) === 'mpesa' ? 'checked' : '' }}
                           onchange="togglePayoutFields()"
                           class="text-[#1B2A4A] focus:ring-[#1B2A4A]">
                    <span class="text-sm text-gray-700">MPESA</span>
                </label>
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="radio" name="withdrawal_method" value="bank"
                           {{ old('withdrawal_method', auth()->user()->withdrawal_method) === 'bank' ? 'checked' : '' }}
                           onchange="togglePayoutFields()"
                           class="text-[#1B2A4A] focus:ring-[#1B2A4A]">
                    <span class="text-sm text-gray-700">Bank Account</span>
                </label>
            </div>
            @error('withdrawal_method') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>

        <div id="mpesaFields" class="space-y-3">
            <div>
                <label for="mpesa_phone" class="block text-sm font-medium text-gray-700">MPESA Phone Number</label>
                <input id="mpesa_phone" name="mpesa_phone" type="text" value="{{ old('mpesa_phone', auth()->user()->mpesa_phone) }}"
                       class="mt-1 block w-full border border-gray-200 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#1B2A4A]/20 focus:border-[#1B2A4A]">
                @error('mpesa_phone') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>
        </div>

        <div id="bankFields" class="space-y-3">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="bank_name" class="block text-sm font-medium text-gray-700">Bank Name</label>
                    <input id="bank_name" name="bank_name" type="text" value="{{ old('bank_name', auth()->user()->bank_name) }}"
                           class="mt-1 block w-full border border-gray-200 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#1B2A4A]/20 focus:border-[#1B2A4A]">
                    @error('bank_name') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="bank_account_name" class="block text-sm font-medium text-gray-700">Account Holder Name</label>
                    <input id="bank_account_name" name="bank_account_name" type="text" value="{{ old('bank_account_name', auth()->user()->bank_account_name) }}"
                           class="mt-1 block w-full border border-gray-200 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#1B2A4A]/20 focus:border-[#1B2A4A]">
                    @error('bank_account_name') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>
            </div>
            <div>
                <label for="bank_account_number" class="block text-sm font-medium text-gray-700">Account Number</label>
                <input id="bank_account_number" name="bank_account_number" type="text" value="{{ old('bank_account_number', auth()->user()->bank_account_number) }}"
                       class="mt-1 block w-full border border-gray-200 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#1B2A4A]/20 focus:border-[#1B2A4A]">
                @error('bank_account_number') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>
        </div>
    </div>

    <div class="flex items-center gap-4 pt-2">
        <button type="button" @click="$dispatch('open-kyc-confirm')"
                class="px-6 py-2.5 bg-[#1B2A4A] text-white text-sm font-medium hover:bg-[#243554] transition">
            Submit KYC
        </button>
        <button type="submit" id="kycFormSubmit" class="hidden"></button>
        @if(session('kyc-status') === 'updated')
            <p class="text-sm text-green-600 font-medium">KYC information saved successfully.</p>
        @endif
    </div>
</form>

{{-- Confirmation Modal --}}
<script>
    document.addEventListener('alpine:init', () => {
        if (typeof Alpine !== 'undefined' && typeof Alpine.data('kycConfirm') === 'undefined') {
            Alpine.data('kycConfirm', () => ({
                open: false,
                idNumber: '',
                birthYear: '',
                address: '',
                method: '',
                mpesaPhone: '',
                bankName: '',
                accountName: '',
                accountNumber: '',
                hasIdFront: {{ auth()->user()->id_front_path ? 'true' : 'false' }},
                hasIdBack: {{ auth()->user()->id_back_path ? 'true' : 'false' }},
                hasAddressProof: {{ auth()->user()->address_proof_path ? 'true' : 'false' }},
                hasProfilePhoto: {{ auth()->user()->profile_photo_path ? 'true' : 'false' }},
                newIdFront: false,
                newIdBack: false,
                newAddressProof: false,
                newProfilePhoto: false,
                capture() {
                    this.idNumber = document.getElementById('id_number')?.value || '';
                    const sel = document.getElementById('birth_year');
                    this.birthYear = sel ? sel.options[sel.selectedIndex]?.text || '' : '';
                    this.address = document.getElementById('address')?.value || '';
                    const checked = document.querySelector('input[name="withdrawal_method"]:checked');
                    this.method = checked?.value || '';
                    this.mpesaPhone = document.getElementById('mpesa_phone')?.value || '';
                    this.bankName = document.getElementById('bank_name')?.value || '';
                    this.accountName = document.getElementById('bank_account_name')?.value || '';
                    this.accountNumber = document.getElementById('bank_account_number')?.value || '';
                    this.newIdFront = document.querySelector('[name=id_front]')?.files?.length > 0;
                    this.newIdBack = document.querySelector('[name=id_back]')?.files?.length > 0;
                    this.newAddressProof = document.querySelector('[name=address_proof]')?.files?.length > 0;
                    this.newProfilePhoto = document.querySelector('[name=profile_photo]')?.files?.length > 0;
                }
            }));
        }
    });
</script>

<div x-data="kycConfirm()"
     @open-kyc-confirm.window="capture(); open = true"
     x-show="open"
     x-cloak
     class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4"
     @click.outside="open = false">
    <div class="bg-white w-full max-w-lg max-h-[90vh] overflow-y-auto">
        <div class="px-6 py-4 border-b border-gray-200" style="background-color: #1B2A4A;">
            <h3 class="text-lg font-semibold text-white">Confirm KYC Details</h3>
        </div>
        <div class="p-6 space-y-4 text-sm">
            <p class="text-gray-500">Please review your details before submitting. You will not be able to edit after submission.</p>

            <div class="border border-gray-200 divide-y divide-gray-100">
                <div class="flex items-center justify-between px-4 py-2.5">
                    <span class="text-gray-500 font-medium">ID Number</span>
                    <span class="text-gray-800 font-mono" x-text="idNumber || '—'"></span>
                </div>
                <div class="flex items-center justify-between px-4 py-2.5">
                    <span class="text-gray-500 font-medium">Birth Year</span>
                    <span class="text-gray-800" x-text="birthYear || '—'"></span>
                </div>
                <div class="flex items-center justify-between px-4 py-2.5">
                    <span class="text-gray-500 font-medium">Address</span>
                    <span class="text-gray-800 text-right max-w-[200px]" x-text="address || '—'"></span>
                </div>
                <div class="flex items-center justify-between px-4 py-2.5">
                    <span class="text-gray-500 font-medium">Withdrawal Method</span>
                    <span class="text-gray-800 font-semibold uppercase" x-text="method.toUpperCase() || '—'"></span>
                </div>
                <div x-show="method === 'mpesa'" class="flex items-center justify-between px-4 py-2.5">
                    <span class="text-gray-500 font-medium">MPESA Phone</span>
                    <span class="text-gray-800 font-mono" x-text="mpesaPhone || '—'"></span>
                </div>
                <div x-show="method === 'bank'" class="flex items-center justify-between px-4 py-2.5">
                    <span class="text-gray-500 font-medium">Bank Name</span>
                    <span class="text-gray-800" x-text="bankName || '—'"></span>
                </div>
                <div x-show="method === 'bank'" class="flex items-center justify-between px-4 py-2.5">
                    <span class="text-gray-500 font-medium">Account Name</span>
                    <span class="text-gray-800" x-text="accountName || '—'"></span>
                </div>
                <div x-show="method === 'bank'" class="flex items-center justify-between px-4 py-2.5">
                    <span class="text-gray-500 font-medium">Account Number</span>
                    <span class="text-gray-800 font-mono" x-text="accountNumber || '—'"></span>
                </div>
                <div class="flex items-center justify-between px-4 py-2.5">
                    <span class="text-gray-500 font-medium">Uploaded Files</span>
                    <span class="text-gray-800 text-right">
                        <template x-if="hasIdFront || newIdFront || hasIdBack || newIdBack || hasAddressProof || newAddressProof || hasProfilePhoto || newProfilePhoto">
                            <span>
                                <span x-show="hasIdFront" class="block text-xs text-green-600">ID Front</span>
                                <span x-show="newIdFront && !hasIdFront" class="block text-xs text-blue-600">ID Front (new)</span>
                                <span x-show="newIdFront && hasIdFront" class="block text-xs text-blue-600">&#x21BB; ID Front (new)</span>
                                <span x-show="hasIdBack" class="block text-xs text-green-600">ID Back</span>
                                <span x-show="newIdBack && !hasIdBack" class="block text-xs text-blue-600">ID Back (new)</span>
                                <span x-show="newIdBack && hasIdBack" class="block text-xs text-blue-600">&#x21BB; ID Back (new)</span>
                                <span x-show="hasAddressProof" class="block text-xs text-green-600">Address Proof</span>
                                <span x-show="newAddressProof && !hasAddressProof" class="block text-xs text-blue-600">Address Proof (new)</span>
                                <span x-show="newAddressProof && hasAddressProof" class="block text-xs text-blue-600">&#x21BB; Address Proof (new)</span>
                                <span x-show="hasProfilePhoto" class="block text-xs text-green-600">Profile Photo</span>
                                <span x-show="newProfilePhoto && !hasProfilePhoto" class="block text-xs text-blue-600">Profile Photo (new)</span>
                                <span x-show="newProfilePhoto && hasProfilePhoto" class="block text-xs text-blue-600">&#x21BB; Profile Photo (new)</span>
                            </span>
                        </template>
                        <span x-show="!hasIdFront && !newIdFront && !hasIdBack && !newIdBack && !hasAddressProof && !newAddressProof && !hasProfilePhoto && !newProfilePhoto" class="text-gray-400">No files uploaded</span>
                    </span>
                </div>
            </div>
        </div>
        <div class="px-6 py-4 border-t border-gray-200 flex items-center justify-end gap-3">
            <button type="button" @click="open = false" class="px-4 py-2 text-sm font-medium text-gray-600 hover:text-gray-800 transition">Cancel</button>
            <button type="button" @click="open = false; document.getElementById('kycFormSubmit')?.click()" class="px-6 py-2 bg-[#1B2A4A] text-white text-sm font-medium hover:bg-[#243554] transition">Confirm & Submit</button>
        </div>
    </div>
</div>

<script>
    function togglePayoutFields() {
        const mpesa = document.getElementById('mpesaFields');
        const bank = document.getElementById('bankFields');
        const selected = document.querySelector('input[name="withdrawal_method"]:checked');
        if (selected) {
            mpesa.style.display = selected.value === 'mpesa' ? 'block' : 'none';
            bank.style.display = selected.value === 'bank' ? 'block' : 'none';
        }
    }
    togglePayoutFields();
</script>
