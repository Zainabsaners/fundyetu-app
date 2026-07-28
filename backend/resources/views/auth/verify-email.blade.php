<x-guest-layout>
    @section('title', 'Verify Email - ' . config('app.name'))

    <div class="space-y-6">
        <div class="text-center">
            <h1 class="text-3xl font-black text-gray-900 tracking-tight">Verify Your Email</h1>
            <p class="mt-2 text-gray-500">A verification link was sent to <strong>{{ auth()->user()->email }}</strong>.</p>
        </div>

        @if (session('status') == 'verification-link-sent')
            <div class="bg-green-50 border border-green-200 text-green-700 px-5 py-4 rounded-xl text-sm font-medium">
                A new verification link has been sent.
            </div>
        @endif

        <p class="text-sm text-gray-500 leading-relaxed">
            Didn't get the email? Check your spam folder or click below to resend.
        </p>

        <div class="flex flex-col sm:flex-row gap-3">
            <form method="POST" action="{{ route('verification.send') }}" class="flex-1">
                @csrf
                <button type="submit"
                    class="w-full py-3.5 bg-[#CE5F26] text-white font-bold rounded-full hover:bg-[#B04E1E] transition shadow-lg shadow-[#CE5F26]/30">
                    Resend Verification Email
                </button>
            </form>

            <form method="POST" action="{{ route('logout') }}" class="flex-1">
                @csrf
                <button type="submit"
                    class="w-full py-3.5 bg-gray-100 text-gray-700 font-semibold rounded-full hover:bg-gray-200 transition">
                    Log Out
                </button>
            </form>
        </div>

        <p class="text-center text-sm text-gray-500">
            <a href="{{ route('phone.verification.notice') }}" class="text-[#CE5F26] hover:text-[#B04E1E] font-medium transition underline">
                Verify phone instead &rarr;
            </a>
        </p>

        <p class="text-center text-sm text-gray-500">
            <a href="{{ route('pending.approval') }}" class="text-gray-400 hover:text-gray-600 transition">&larr; Back to account setup</a>
        </p>
    </div>
</x-guest-layout>
