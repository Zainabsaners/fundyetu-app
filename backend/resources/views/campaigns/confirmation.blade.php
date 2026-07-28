<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Donation Initiated</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-lg shadow p-6 text-center">
                <div class="text-4xl mb-4">&#10004;</div>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">Thank You!</h3>
                <p class="text-gray-600 mb-6">
                    Your donation of <strong>KES {{ number_format($donation->amount, 0) }}</strong>
                    to "{{ $donation->campaign->title }}" has been initiated.
                </p>

                @if($donation->payment_method === 'mpesa')
                    <div class="bg-blue-50 text-blue-700 p-4 rounded mb-6">
                        Check your phone for the M-Pesa STK Push prompt and enter your PIN to complete payment.
                    </div>
                    <div id="polling-status" class="text-sm text-gray-500 mb-4">
                        Waiting for payment confirmation...
                        <span class="inline-block ml-1" id="polling-dots">.</span>
                    </div>
                @else
                    <p class="text-gray-600 mb-6">You will be redirected to complete your payment.</p>
                @endif

                <a href="{{ route('campaigns.show', $donation->campaign) }}"
                   class="inline-flex items-center px-6 py-2 bg-gray-800 text-white font-semibold rounded-full hover:bg-gray-700">
                    Back to Campaign
                </a>
            </div>
        </div>
    </div>

    @if($donation->payment_method === 'mpesa')
    <script>
        (function () {
            var dots = document.getElementById('polling-dots');
            var dotCount = 1;
            setInterval(function () {
                if (dots) {
                    dotCount = dotCount >= 3 ? 1 : dotCount + 1;
                    dots.textContent = '.'.repeat(dotCount);
                }
            }, 600);

            var pollId = 'donation-poll-' + {{ $donation->id }};
            var maxAttempts = 60;
            var attempt = 0;

            function checkStatus() {
                if (attempt >= maxAttempts) {
                    document.getElementById('polling-status').textContent = 'Payment is taking longer than expected. You can close this page and check your donation history later.';
                    return;
                }
                attempt++;

                fetch('{{ route('donations.status', $donation) }}')
                    .then(function (r) { return r.json(); })
                    .then(function (data) {
                        if (data.status === 'completed') {
                            window.location.href = '{{ route('donations.thank-you', $donation) }}';
                        } else if (data.status === 'failed') {
                            document.getElementById('polling-status').innerHTML =
                                '<span class="text-red-600">Payment failed. Please try again.</span>';
                        }
                    })
                    .catch(function () {});
            }

            setInterval(checkStatus, 3000);
            setTimeout(checkStatus, 1000);
        })();
    </script>
    @endif
</x-app-layout>
