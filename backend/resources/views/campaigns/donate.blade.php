<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Donate to {{ $campaign->title }}</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-lg shadow p-6">
                <div class="mb-6 p-4 bg-gray-50 rounded">
                    <p class="text-sm text-gray-600">Progress: KES {{ number_format($campaign->raised_amount, 0) }} raised of KES {{ number_format($campaign->target_amount, 0) }}</p>
                    <div class="bg-gray-200 rounded-full h-2 mt-2">
                        <div class="bg-terracotta rounded-full h-2" style="width: {{ $campaign->progressPercent() }}%"></div>
                    </div>
                </div>

                <form action="{{ route('donations.store', $campaign) }}" method="POST">
                    @csrf

                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Select Amount</label>
                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 mb-3">
                            @foreach([500, 1000, 2000, 5000, 10000, 20000] as $preset)
                                <button type="button" onclick="document.getElementById('amount').value='{{ $preset }}'"
                                        class="preset-amount px-4 py-3 border border-gray-300 rounded-full text-center hover:bg-gray-50 focus:ring-2 focus:ring-maroon/30">
                                    KES {{ number_format($preset) }}
                                </button>
                            @endforeach
                        </div>
                        <input type="number" name="amount" id="amount" value="{{ old('amount') }}"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                               placeholder="Or enter custom amount" min="10" required>
                        @error('amount') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    @guest
                        <div class="mb-4">
                            <label for="donor_name" class="block text-sm font-medium text-gray-700">Your Name</label>
                            <input type="text" name="donor_name" id="donor_name" value="{{ old('donor_name') }}"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>

                        <div class="mb-4">
                            <label for="donor_email" class="block text-sm font-medium text-gray-700">Email</label>
                            <input type="email" name="donor_email" id="donor_email" value="{{ old('donor_email') }}"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                    @endguest

                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Payment Method</label>
                        <div class="space-y-2">
                            @if($enabledMethods['mpesa'])
                            <label class="flex items-center gap-3 p-3 border rounded-md cursor-pointer hover:bg-gray-50">
                                <input type="radio" name="payment_method" value="mpesa" checked>
                                <span class="font-medium">M-Pesa</span>
                                <span class="text-sm text-gray-500">Pay with M-Pesa</span>
                            </label>
                            @endif
                            @if($enabledMethods['airtel'])
                            <label class="flex items-center gap-3 p-3 border rounded-md cursor-pointer hover:bg-gray-50">
                                <input type="radio" name="payment_method" value="airtel">
                                <span class="font-medium">Airtel Money</span>
                                <span class="text-sm text-gray-500">Pay with Airtel</span>
                            </label>
                            @endif
                            @if($enabledMethods['card'])
                            <label class="flex items-center gap-3 p-3 border rounded-md cursor-pointer hover:bg-gray-50">
                                <input type="radio" name="payment_method" value="card">
                                <span class="font-medium">Card</span>
                                <span class="text-sm text-gray-500">Visa, MasterCard</span>
                            </label>
                            @endif
                        </div>
                        @error('payment_method') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div id="phone-field" class="mb-6">
                        <label for="donor_phone" class="block text-sm font-medium text-gray-700">Phone Number (e.g., 254712345678)</label>
                        <input type="text" name="donor_phone" id="donor_phone" value="{{ old('donor_phone', auth()->user()?->phone) }}"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                               placeholder="254712345678">
                        @error('donor_phone') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="mb-6">
                        <label for="message" class="block text-sm font-medium text-gray-700">Message (optional)</label>
                        <textarea name="message" id="message" rows="2"
                                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                  placeholder="Leave a message of support...">{{ old('message') }}</textarea>
                    </div>

                    <button type="submit"
                            class="w-full px-6 py-3 bg-maroon text-white font-semibold rounded-full hover:bg-maroon-dark">
                        Donate Now
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.querySelectorAll('input[name="payment_method"]').forEach(el => {
            el.addEventListener('change', function() {
                document.getElementById('phone-field').style.display = this.value === 'card' ? 'none' : 'block';
            });
        });
        if (document.querySelector('input[name="payment_method"]:checked')?.value === 'card') {
            document.getElementById('phone-field').style.display = 'none';
        }
    </script>
</x-app-layout>
