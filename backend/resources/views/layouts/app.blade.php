<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>
        <link rel="icon" type="image/png" href="{{ asset('assets/images/logo/favicon.png') }}">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800,900&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            [x-cloak] { display: none !important; }
            .nav-blur {
                backdrop-filter: blur(12px);
                background: rgba(255,255,255,0.85);
            }
            .nav-scrolled { box-shadow: 0 1px 3px rgba(0,0,0,0.08); }
        </style>
    </head>
    <body class="font-sans antialiased text-gray-900">
        <div class="min-h-screen bg-gray-100/50">
            @include('layouts.navigation')

            <div class="pt-16 lg:pt-20">

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
            </div>
        </div>

        @auth
            <x-chat-widget />
        @endauth

        @php $toastMsg = session('toast') ?? session('success'); @endphp
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

        <script>
            const navbar = document.getElementById('navbar');
            if (navbar) {
                window.addEventListener('scroll', () => {
                    if (window.scrollY > 20) {
                        navbar.classList.add('nav-scrolled');
                        navbar.style.backgroundColor = 'rgba(255,255,255,0.95)';
                    } else {
                        navbar.classList.remove('nav-scrolled');
                        navbar.style.backgroundColor = 'rgba(255,255,255,0.85)';
                    }
                });
            }
        </script>
    </body>
</html>
