<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', config('app.name', 'Support Sphere'))</title>
    <link rel="icon" type="image/png" href="{{ asset('assets/images/logo/favicon.png') }}">

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800,900&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        [x-cloak] { display: none !important; }
        html { scroll-behavior: smooth; }

        .auth-gradient {
            background: #1B2A4A;
        }
    </style>
</head>
<body class="font-sans antialiased text-gray-900 bg-gray-50/30">
    <div class="min-h-screen flex">
        {{-- Left: Decorative --}}
        <div class="hidden lg:flex flex-1 items-center justify-center relative overflow-hidden">
            <img src="{{ asset('assets/images/sliders/slide-1.jpg') }}" alt="" class="absolute inset-0 w-full h-full object-cover">
            <div class="absolute inset-0" style="background: linear-gradient(135deg, rgba(27,42,74,0.6) 0%, rgba(42,59,89,0.5) 50%, rgba(27,42,74,0.6) 100%);"></div>
            <div style="position:absolute;width:500px;height:500px;background:rgba(196,98,45,0.08);border-radius:50%;filter:blur(100px);top:-10%;left:-10%;"></div>
            <div style="position:absolute;width:400px;height:400px;background:rgba(74,124,63,0.06);border-radius:50%;filter:blur(100px);bottom:-20%;right:-10%;"></div>
            <div class="relative text-center max-w-md px-12">
                <h2 class="text-3xl font-black text-white tracking-tight">Community Fundraising Made Simple</h2>
                <p class="mt-4 text-white/60 leading-relaxed">Join thousands of Kenyans raising money for what matters most. Secure, transparent, and trusted.</p>
            </div>
        </div>

        {{-- Right: Form --}}
        <div class="flex-1 flex flex-col justify-center items-center px-6 lg:px-16 py-12">
            <div class="w-full max-w-lg">
                <a href="{{ route('home') }}" class="flex justify-center mb-10">
                    <img src="{{ asset('assets/images/logo/logo.png') }}" alt="Support Sphere" class="h-8 lg:h-9 w-auto">
                </a>

                {{ $slot }}
            </div>
        </div>
        </div>
    </div>

    @php
        $toastMsg = session('toast') ?? session('success');
        if (!$toastMsg && session('kyc-status') === 'updated') {
            $toastMsg = 'KYC information saved successfully.';
        }
    @endphp
    @if($toastMsg)
        <div x-data="{ show: false }" x-init="setTimeout(() => show = true, 100); setTimeout(() => show = false, 8000)"
             x-show="show" x-cloak
             x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-2"
             x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 translate-y-2"
             class="fixed bottom-6 right-6 z-50 bg-[#1B2A4A] text-white px-5 py-3 shadow-lg flex items-center gap-3">
            <svg class="w-5 h-5 text-[#7AA36A] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span class="text-sm font-medium">{{ $toastMsg }}</span>
            <button @click="show = false" class="text-white/60 hover:text-white ml-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
    @endif
</body>
</html>
