<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Payment Processing</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-lg shadow p-6 text-center">
                <div class="animate-spin text-4xl mb-4 inline-block">&#8635;</div>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">Processing Your Payment</h3>
                <p class="text-gray-600 mb-6">Please wait while we confirm your donation...</p>
                <a href="{{ route('campaigns.show', $donation->campaign) }}"
                   class="inline-flex items-center px-6 py-2 bg-gray-800 text-white font-semibold rounded-full hover:bg-gray-700">
                    Back to Campaign
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
