<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/png" href="{{ asset('assets/images/logo/favicon.png') }}">
    <title>{{ config('app.name', 'Support Sphere') }} - Community Fundraising Made Simple</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800,900&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        html { scroll-behavior: smooth; }

        .reveal { opacity: 0; transform: translateY(40px); transition: all 0.8s cubic-bezier(0.16, 1, 0.3, 1); }
        .reveal.visible { opacity: 1; transform: translateY(0); }
        .reveal-delay-1 { transition-delay: 0.1s; }
        .reveal-delay-2 { transition-delay: 0.2s; }
        .reveal-delay-3 { transition-delay: 0.3s; }
        .reveal-delay-4 { transition-delay: 0.4s; }

        .float { animation: float 6s ease-in-out infinite; }
        .float-delay { animation: float 6s ease-in-out 3s infinite; }
        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-20px); }
        }

        .pulse-glow { animation: pulseGlow 3s ease-in-out infinite; }
        @keyframes pulseGlow {
            0%, 100% { opacity: 0.4; transform: scale(1); }
            50% { opacity: 0.7; transform: scale(1.05); }
        }



        .stat-number { display: inline-block; }

        .counter-wrapper {
            position: relative;
        }

        .gradient-border {
            position: relative;
            background: rgba(27, 42, 74, 0.1);
        }

        .hero-gradient {
            background: #1B2A4A;
        }

        .hero-ornament {
            position: absolute;
            border-radius: 50%;
            filter: blur(80px);
            opacity: 0.15;
            animation: ornamentFloat 20s ease-in-out infinite;
        }
        @keyframes ornamentFloat {
            0%, 100% { transform: translate(0, 0) scale(1); }
            25% { transform: translate(30px, -30px) scale(1.1); }
            50% { transform: translate(-20px, 20px) scale(0.9); }
            75% { transform: translate(20px, 30px) scale(1.05); }
        }



        .nav-blur {
            backdrop-filter: blur(12px);
            background: rgba(255,255,255,0.85);
        }
        .nav-scrolled { box-shadow: 0 1px 3px rgba(0,0,0,0.08); }

        .testimonial-card { transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1); }
        .testimonial-card:hover { transform: translateY(-6px) scale(1.01); }

        .feature-icon-wrapper { transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1); }
        .feature-card:hover .feature-icon-wrapper { transform: scale(1.1) rotate(-5deg); }

        .campaign-card { transition: all 0.5s cubic-bezier(0.16, 1, 0.3, 1); }
        .campaign-card:hover { transform: translateY(-8px); }

        .progress-fill { transition: width 1.5s cubic-bezier(0.16, 1, 0.3, 1); }

        .cta-glow {
            box-shadow: 0 0 40px rgba(21, 128, 61, 0.3), 0 0 80px rgba(21, 128, 61, 0.1);
        }

        @keyframes countUp {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .counting { animation: countUp 0.5s ease-out forwards; }

        .accessibility-btn {
            position: fixed;
            bottom: 24px;
            right: 24px;
            z-index: 9999;
            width: 52px;
            height: 52px;
            border-radius: 50%;
            background-color: #7aa36a;
            color: white;
            border: none;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 16px rgba(0,0,0,0.2);
            transition: all 0.3s ease;
        }
        .accessibility-btn:hover {
            transform: scale(1.1);
            box-shadow: 0 6px 24px rgba(0,0,0,0.3);
        }
        .accessibility-panel {
            position: fixed;
            bottom: 90px;
            right: 24px;
            z-index: 9998;
            background: white;
            border-radius: 16px;
            padding: 20px;
            width: 260px;
            box-shadow: 0 8px 32px rgba(0,0,0,0.15);
            border: 1px solid #e5e7eb;
            transform: scale(0.95);
            opacity: 0;
            pointer-events: none;
            transition: all 0.3s ease;
            transform-origin: bottom right;
        }
        .accessibility-panel.open {
            transform: scale(1);
            opacity: 1;
            pointer-events: auto;
        }
        .accessibility-panel h3 {
            font-size: 14px;
            font-weight: 700;
            color: #1b2a4a;
            margin-bottom: 16px;
            text-align: center;
        }
        .a11y-option {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid #f3f4f6;
        }
        .a11y-option:last-child { border-bottom: none; }
        .a11y-option span {
            font-size: 13px;
            font-weight: 500;
            color: #374151;
        }
        .a11y-option button {
            width: 36px;
            height: 32px;
            border-radius: 8px;
            border: 1px solid #d1d5db;
            background: white;
            cursor: pointer;
            font-size: 14px;
            font-weight: 600;
            color: #374151;
            transition: all 0.2s;
        }
        .a11y-option button:hover {
            background: #f3f4f6;
            border-color: #9ca3af;
        }
        .a11y-toggle {
            position: relative;
            width: 44px;
            height: 24px;
            border-radius: 12px;
            background: #d1d5db;
            cursor: pointer;
            transition: background 0.3s;
            border: none;
        }
        .a11y-toggle.active { background: #7aa36a; }
        .a11y-toggle::after {
            content: '';
            position: absolute;
            top: 2px;
            left: 2px;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            background: white;
            transition: transform 0.3s;
            box-shadow: 0 1px 3px rgba(0,0,0,0.15);
        }
        .a11y-toggle.active::after { transform: translateX(20px); }

        body.a11y-contrast { filter: contrast(1.4); }
        body.a11y-grayscale { filter: grayscale(1); }
        body.a11y-large-text p, body.a11y-large-text li, body.a11y-large-text span:not(.campaign-dot):not(.testimonial-dot):not(.hero-dot):not(.slider-dot) { font-size: 1.2em !important; }
        body.a11y-large-text h1, body.a11y-large-text h2, body.a11y-large-text h3, body.a11y-large-text h4 { font-size: 1.4em !important; }
        body.a11y-large-text a, body.a11y-large-text button { font-size: 1.15em !important; }
    </style>
</head>
<body class="font-sans antialiased text-gray-900 bg-white">

    {{-- Floating Share Bar --}}
    <div class="hidden lg:flex fixed left-0 top-1/2 -translate-y-1/2 z-[60] flex-col gap-0.5">
        <a href="https://wa.me/?text={{ urlencode(config('app.name', 'Support Sphere') . ' - Community Fundraising Made Simple') }}%20{{ urlencode(url('/')) }}"
           target="_blank"
           class="w-12 h-12 bg-green-500 rounded-r-lg flex items-center justify-center shadow-lg hover:w-14 hover:bg-green-600 transition-all text-white"
           title="Share on WhatsApp">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
        </a>
        <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url('/')) }}"
           target="_blank"
           class="w-12 h-12 bg-blue-600 rounded-r-lg flex items-center justify-center shadow-lg hover:w-14 hover:bg-blue-700 transition-all text-white"
           title="Share on Facebook">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" viewBox="0 0 24 24" fill="currentColor"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
        </a>
        <a href="https://twitter.com/intent/tweet?text={{ urlencode(config('app.name', 'Support Sphere') . ' - Community Fundraising Made Simple') }}&url={{ urlencode(url('/')) }}"
           target="_blank"
           class="w-12 h-12 bg-gray-900 rounded-r-lg flex items-center justify-center shadow-lg hover:w-14 hover:bg-gray-800 transition-all text-white"
           title="Share on X (Twitter)">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
        </a>
        <a href="mailto:?subject={{ urlencode(config('app.name', 'Support Sphere')) }}&body={{ urlencode('Check out Support Sphere - Community Fundraising Made Simple: ' . url('/')) }}"
           class="w-12 h-12 bg-gray-600 rounded-r-lg flex items-center justify-center shadow-lg hover:w-14 hover:bg-gray-700 transition-all text-white"
           title="Share via Email">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
        </a>
    </div>

    {{-- Navigation --}}
    <nav id="navbar" class="fixed top-0 left-0 right-0 z-50 nav-blur transition-all duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 lg:h-20 items-center">
                <a href="/" class="flex items-center shrink-0">
                    <img src="{{ asset('assets/images/logo/logo.png') }}" alt="Support Sphere" class="h-8 lg:h-9 w-auto">
                </a>

                <div class="hidden md:flex items-center gap-8">
                    <div class="hidden lg:flex items-center gap-3 mr-4 pr-4 border-r border-gray-200">
                        <a href="tel:+254732447447" class="flex items-center gap-2 text-sm text-gray-600 hover:text-[#CE5F26] font-medium transition">
                            <svg class="w-4 h-4 text-[#CE5F26]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                            </svg>
                            +254 732447447
                        </a>
                        <a href="mailto:info@supportsphere.co.ke" class="flex items-center gap-2 text-sm text-gray-600 hover:text-[#CE5F26] font-medium transition">
                            <svg class="w-4 h-4 text-[#CE5F26]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            info@supportsphere.co.ke
                        </a>
                    </div>
                    <a href="/#trending" class="text-gray-600 hover:text-maroon-dark font-medium transition relative after:absolute after:bottom-0 after:left-0 after:h-0.5 after:w-0 after:bg-maroon after:transition-all hover:after:w-full">Explore</a>
                    <a href="#how-it-works" class="text-gray-600 hover:text-maroon-dark font-medium transition relative after:absolute after:bottom-0 after:left-0 after:h-0.5 after:w-0 after:bg-maroon after:transition-all hover:after:w-full">How It Works</a>
                    <a href="#features" class="text-gray-600 hover:text-maroon-dark font-medium transition relative after:absolute after:bottom-0 after:left-0 after:h-0.5 after:w-0 after:bg-maroon after:transition-all hover:after:w-full">Features</a>
                    @auth
                        @php $isAdmin = auth()->user()->roles->pluck('name')->intersect(['admin', 'super_admin'])->isNotEmpty(); @endphp
                        <a href="{{ $isAdmin ? route('admin.dashboard') : route('dashboard') }}" class="text-gray-600 hover:text-maroon-dark font-medium transition">Dashboard</a>
                        <a href="{{ route('campaigns.create') }}" class="inline-flex items-center px-5 py-2.5 bg-[#CE5F26] text-white font-semibold rounded-full hover:bg-[#B04E1E] transition-all shadow-lg shadow-[#CE5F26]/30 hover:shadow-[#CE5F26]/40 hover:-translate-y-0.5">
                            Start a Fundraiser
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="text-gray-600 hover:text-maroon-dark font-medium transition">Log in</a>
                        <a href="{{ route('register') }}" class="inline-flex items-center px-5 py-2.5 bg-[#CE5F26] text-white font-semibold rounded-full hover:bg-[#B04E1E] transition-all shadow-lg shadow-[#CE5F26]/30 hover:shadow-[#CE5F26]/40 hover:-translate-y-0.5">
                            Start a Fundraiser
                        </a>
                    @endauth
                </div>

                <button type="button" id="mobile-menu-btn" class="md:hidden p-2.5 rounded-xl text-maroon bg-white/90 hover:text-white hover:bg-maroon-dark transition" onclick="document.getElementById('mobile-menu').classList.toggle('hidden')">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
            </div>
        </div>

        <div id="mobile-menu" class="hidden md:hidden border-t border-gray-100 bg-white/95 backdrop-blur-lg">
            <div class="px-4 py-4 space-y-2">
                <a href="/#trending" class="block px-4 py-3 rounded-xl text-gray-600 hover:bg-maroon/[0.03] hover:text-maroon-dark font-medium transition">Explore</a>
                <a href="#how-it-works" class="block px-4 py-3 rounded-xl text-gray-600 hover:bg-maroon/[0.03] hover:text-maroon-dark font-medium transition">How It Works</a>
                <a href="#features" class="block px-4 py-3 rounded-xl text-gray-600 hover:bg-maroon/[0.03] hover:text-maroon-dark font-medium transition">Features</a>
                @auth
                    @php $isAdmin = auth()->user()->roles->pluck('name')->intersect(['admin', 'super_admin'])->isNotEmpty(); @endphp
                    <a href="{{ $isAdmin ? route('admin.dashboard') : route('dashboard') }}" class="block px-4 py-3 rounded-xl text-gray-600 hover:bg-maroon/[0.03] hover:text-maroon-dark font-medium transition">Dashboard</a>
                    <a href="{{ route('campaigns.create') }}" class="block px-4 py-3 text-center bg-[#CE5F26] text-white font-semibold rounded-full hover:bg-[#B04E1E] transition">Start a Fundraiser</a>
                @else
                    <a href="{{ route('login') }}" class="block px-4 py-3 rounded-xl text-gray-600 hover:bg-maroon/[0.03] hover:text-maroon-dark font-medium transition">Log in</a>
                    <a href="{{ route('register') }}" class="block px-4 py-3 text-center bg-[#CE5F26] text-white font-semibold rounded-full hover:bg-[#B04E1E] transition">Start a Fundraiser</a>
                @endauth
            </div>
            <div class="px-4 py-4 border-t border-gray-100 mt-2 flex flex-col gap-3">
                <a href="tel:+254732447447" class="flex items-center gap-3 text-sm font-medium text-gray-700 hover:text-[#CE5F26] transition bg-gray-50 rounded-xl px-4 py-3">
                    <svg class="w-5 h-5 text-[#CE5F26]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                    </svg>
                    +254 732447447
                </a>
                <a href="mailto:info@supportsphere.co.ke" class="flex items-center gap-3 text-sm font-medium text-gray-700 hover:text-[#CE5F26] transition bg-gray-50 rounded-xl px-4 py-3">
                    <svg class="w-5 h-5 text-[#CE5F26]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                    info@supportsphere.co.ke
                </a>
            </div>
        </div>
    </nav>

    {{-- Hero Section --}}
    <section class="relative min-h-screen flex items-center overflow-hidden hero-gradient">
        <div class="hero-ornament w-[500px] h-[500px] bg-terracotta hidden lg:block" style="top: -10%; right: -5%;"></div>
        <div class="hero-ornament w-[400px] h-[400px] bg-navy/40 hidden lg:block" style="bottom: -15%; left: -8%; animation-delay: -7s;"></div>
        <div class="hero-ornament w-[300px] h-[300px] bg-terracotta/80 hidden lg:block" style="top: 40%; left: 30%; animation-delay: -12s;"></div>

        <div class="relative w-full">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 lg:py-40">
                <div class="grid lg:grid-cols-[1fr_1.3fr] gap-10 lg:gap-16 items-center max-w-full overflow-hidden">
                    <div>
                        <div class="reveal visible">

                        </div>

                        <h1 class="text-4xl sm:text-5xl lg:text-7xl font-black text-white leading-[1.1] tracking-tight break-words">
                            <span class="reveal visible">Fundraising Made</span>
                            <br>
                            <span class="reveal visible reveal-delay-1 inline-block mt-2 text-terracotta">Simple</span>
                            <br>
                            <span class="reveal visible reveal-delay-2">For Everyone</span>
                        </h1>

                        <p class="reveal visible reveal-delay-3 mt-8 text-base sm:text-lg lg:text-xl text-white/70 max-w-xl leading-relaxed">
                            Start a fundraiser for medical bills, education, community projects, or any cause close to your heart. Secure, transparent, and free to start.
                        </p>

                        <div class="reveal visible reveal-delay-4 mt-10 flex flex-col sm:flex-row gap-4">
                            <a href="{{ route('register') }}" class="group inline-flex items-center justify-center px-6 py-3 lg:px-8 lg:py-4 bg-terracotta text-gray-900 font-bold rounded-full hover:bg-terracotta-dark transition-all text-base lg:text-lg shadow-xl shadow-terracotta/25 hover:shadow-terracotta/40 hover:-translate-y-0.5">
                                Start a Fundraiser
                                <svg class="w-5 h-5 ml-2 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                                </svg>
                            </a>
                            <a href="/#trending" class="group inline-flex items-center justify-center px-6 py-3 lg:px-8 lg:py-4 text-white font-semibold rounded-full transition-all border border-white/15 backdrop-blur-sm hover:border-white/30" style="background-color: #CE5F26;">
                                Explore Fundraisers
                                <svg class="w-5 h-5 ml-2 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                </svg>
                            </a>
                        </div>

                        <div class="reveal visible reveal-delay-4 mt-12 flex items-center gap-8 text-white/50 text-sm">
                            <div class="flex items-center gap-2">
                                 <span>Free to start</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <span>Transparent</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <span>Instant withdrawals</span>
                            </div>
                        </div>
                    </div>

                    <div class="hidden lg:flex justify-center items-center">
                        <div class="relative w-full">
                            <div class="w-[600px] h-[600px] bg-terracotta/10 rounded-full blur-3xl absolute -top-20 -right-20 pulse-glow"></div>
                            <div class="relative bg-white/5 backdrop-blur-xl border border-white/10 rounded-3xl overflow-hidden">
                                <div id="hero-slider" class="flex transition-transform duration-500 ease-in-out">
                                    <div class="min-w-full aspect-[4/3] bg-gray-900">
                                        <img src="{{ asset('assets/images/sliders/slide-1.jpg') }}" alt="Fundraising" class="w-full h-full object-cover">
                                    </div>
                                    <div class="min-w-full aspect-[4/3] bg-gray-900">
                                        <img src="{{ asset('assets/images/sliders/slide-2.jpg') }}" alt="Withdrawals" class="w-full h-full object-cover">
                                    </div>
                                    <div class="min-w-full aspect-[4/3] bg-gray-900">
                                        <img src="{{ asset('assets/images/sliders/slide-3.jpg') }}" alt="Secure" class="w-full h-full object-cover">
                                    </div>
                                </div>
                                <div class="absolute bottom-6 left-1/2 -translate-x-1/2 flex gap-3">
                                    <button onclick="slideHero(0)" class="w-3 h-3 rounded-full bg-terracotta border-0 cursor-pointer hero-dot active-hero-dot"></button>
                                    <button onclick="slideHero(1)" class="w-3 h-3 rounded-full bg-white/30 border-0 cursor-pointer hero-dot"></button>
                                    <button onclick="slideHero(2)" class="w-3 h-3 rounded-full bg-white/30 border-0 cursor-pointer hero-dot"></button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </section>

    {{-- Stats Section --}}
    <section class="relative z-10 -mt-10 lg:-mt-16 py-8 lg:py-14">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-3xl shadow-2xl shadow-maroon/5 border border-maroon/10 p-6 lg:py-12 lg:px-8 reveal">
                <div class="grid grid-cols-2 lg:grid-cols-4 divide-x-0 lg:divide-x divide-maroon/10">
                    <div class="text-center group py-4 lg:py-0 px-4">
                        <div class="text-4xl lg:text-5xl font-black text-maroon counter-wrapper">
                            <span class="stat-number" data-target="{{ $totalCampaigns ?? 0 }}">0</span><span class="text-terracotta">+</span>
                        </div>
                        <p class="text-gray-500 font-medium mt-2 group-hover:text-maroon-dark transition">Fundraisers Started</p>
                        <div class="w-12 h-1 bg-forest rounded-full mx-auto mt-3 group-hover:w-20 transition-all duration-500"></div>
                    </div>
                    <div class="text-center group py-4 lg:py-0 px-4">
                        <div class="text-4xl lg:text-5xl font-black text-maroon counter-wrapper">
                            <span class="stat-number" data-target="{{ $totalRaised ?? 0 }}">0</span><span class="text-terracotta">+</span>
                        </div>
                        <p class="text-gray-500 font-medium mt-2 group-hover:text-maroon-dark transition">Total Raised</p>
                        <div class="w-12 h-1 bg-forest rounded-full mx-auto mt-3 group-hover:w-20 transition-all duration-500"></div>
                    </div>
                    <div class="text-center group py-4 lg:py-0 px-4">
                        <div class="text-4xl lg:text-5xl font-black text-maroon counter-wrapper">
                            <span class="stat-number" data-target="{{ $totalDonors ?? 0 }}">0</span><span class="text-terracotta">+</span>
                        </div>
                        <p class="text-gray-500 font-medium mt-2 group-hover:text-maroon-dark transition">Donors</p>
                        <div class="w-12 h-1 bg-forest rounded-full mx-auto mt-3 group-hover:w-20 transition-all duration-500"></div>
                    </div>
                    <div class="text-center group py-4 lg:py-0 px-4">
                        <div class="text-4xl lg:text-5xl font-black text-maroon counter-wrapper">
                            <span class="stat-number" data-target="3.25">0</span><span class="text-terracotta">%</span>
                        </div>
                        <p class="text-gray-500 font-medium mt-2 group-hover:text-maroon-dark transition">Platform Fee</p>
                        <div class="w-12 h-1 bg-terracotta rounded-full mx-auto mt-3 group-hover:w-20 transition-all duration-500"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- How It Works --}}
    <section id="how-it-works" class="pt-10 lg:pt-16 pb-20 lg:pb-28 bg-terracotta/[0.03] overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid lg:grid-cols-2 gap-8 lg:gap-20 items-start max-w-full overflow-hidden">

                {{-- Left Column: Steps --}}
                <div class="reveal">
                    <h2 class="text-2xl sm:text-4xl lg:text-5xl font-black text-navy tracking-tight leading-tight text-center lg:text-left break-words">3 Easy Steps To Fundraise</h2>
                    <div class="w-16 h-1 bg-terracotta rounded-full mx-auto lg:mx-0 mt-4"></div>

                    <div class="mt-8 lg:mt-12 space-y-0 relative">
                        <div class="absolute left-[17px] top-3 bottom-3 w-0.5 border-l-2 border-dotted border-navy/30"></div>

                        <div class="flex gap-4 relative pb-10 lg:pb-12">
                            <div class="w-9 h-9 shrink-0 rounded-full border-2 border-navy flex items-center justify-center bg-terracotta/[0.03] z-10">
                                <svg class="w-4 h-4 text-navy" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                    <circle cx="12" cy="12" r="10"/><polygon points="10 8 16 12 10 16 10 8" fill="currentColor" stroke="none"/>
                                </svg>
                            </div>
                            <div class="min-w-0 break-words">
                                <h3 class="text-base sm:text-lg font-bold text-navy">Create your fundraiser</h3>
                                <p class="text-sm sm:text-base text-gray-500 mt-1 leading-relaxed">It'll take only 2 minutes. Just fill out our registration form.</p>
                            </div>
                        </div>

                        <div class="flex gap-4 relative pb-10 lg:pb-12">
                            <div class="w-9 h-9 shrink-0 rounded-full border-2 border-navy flex items-center justify-center bg-terracotta/[0.03] z-10">
                                <svg class="w-4 h-4 text-navy" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M22 2L11 13"/><path d="M22 2L15 22L11 13L2 9L22 2Z"/>
                                </svg>
                            </div>
                            <div class="min-w-0 break-words">
                                <h3 class="text-base sm:text-lg font-bold text-navy">Share with your supporters</h3>
                                <p class="text-sm sm:text-base text-gray-500 mt-1 leading-relaxed">Share via WhatsApp, Facebook or SMS with your community.</p>
                            </div>
                        </div>

                        <div class="flex gap-4 relative">
                            <div class="w-9 h-9 shrink-0 rounded-full border-2 border-navy flex items-center justify-center bg-terracotta/[0.03] z-10">
                                <svg class="w-4 h-4 text-navy" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/>
                                </svg>
                            </div>
                            <div class="min-w-0 break-words">
                                <h3 class="text-base sm:text-lg font-bold text-navy">Manage your fundraiser</h3>
                                <p class="text-sm sm:text-base text-gray-500 mt-1 leading-relaxed">Track progress, add treasurers, accept mobile money and card donations.</p>
                            </div>
                        </div>
                    </div>

                    <div class="mt-8 lg:mt-10 flex flex-col sm:flex-row gap-3 lg:gap-4">
                        <a href="{{ route('register') }}"
                           class="w-full sm:w-auto inline-flex items-center justify-center px-6 py-3 lg:px-8 lg:py-4 bg-terracotta text-navy font-bold rounded-full hover:bg-terracotta-dark transition-all text-xs lg:text-sm shadow-lg shadow-terracotta/30 hover:shadow-terracotta/50">
                            START A FUNDRAISER
                        </a>
                        <a href="#"
                           class="w-full sm:w-auto inline-flex items-center justify-center px-6 py-3 lg:px-8 lg:py-4 bg-red-600 text-white font-bold rounded-full hover:bg-red-700 transition-all text-xs lg:text-sm shadow-lg shadow-red-600/30 hover:shadow-red-600/50 hover:-translate-y-0.5">
                            WATCH VIDEO
                        </a>
                    </div>
                </div>

                {{-- Right Column: Activate --}}
                <div class="reveal reveal-delay-1 max-w-full overflow-hidden">
                    <div class="bg-white rounded-3xl shadow-xl shadow-navy-darker/5 border border-gray-100 p-5 sm:p-6 lg:p-10">
                        <h3 class="text-lg sm:text-xl lg:text-3xl font-black text-navy tracking-tight text-center break-words">Activate Your Fundraiser!</h3>
                        <p class="text-sm lg:text-base text-gray-500 text-center mt-3 leading-relaxed break-words">Follow these simple steps and start raising money in minutes.</p>

                        <div class="mt-6 lg:mt-8">
                            <div class="relative overflow-hidden rounded-2xl bg-gray-100">
                                <div id="how-slider" class="flex transition-transform duration-500 ease-in-out">
                                    <div class="min-w-full bg-gray-100">
                                        <img src="{{ asset('assets/images/sliders/how-1.jpg') }}" alt="Create Account" class="w-full h-52 sm:h-64 lg:h-64 object-cover">
                                    </div>
                                    <div class="min-w-full bg-gray-100">
                                        <img src="{{ asset('assets/images/sliders/how-2.jpg') }}" alt="Set Your Goal" class="w-full h-52 sm:h-64 lg:h-64 object-cover">
                                    </div>
                                    <div class="min-w-full bg-gray-100">
                                        <img src="{{ asset('assets/images/sliders/how-3.jpg') }}" alt="Share & Raise" class="w-full h-52 sm:h-64 lg:h-64 object-cover">
                                    </div>
                                </div>
                                <div class="absolute bottom-4 left-1/2 -translate-x-1/2 flex gap-2">
                                    <button onclick="slideHowTo(0)" class="w-2.5 h-2.5 rounded-full bg-navy border-0 cursor-pointer slider-dot active-dot"></button>
                                    <button onclick="slideHowTo(1)" class="w-2.5 h-2.5 rounded-full bg-gray-300 border-0 cursor-pointer slider-dot"></button>
                                    <button onclick="slideHowTo(2)" class="w-2.5 h-2.5 rounded-full bg-gray-300 border-0 cursor-pointer slider-dot"></button>
                                </div>
                            </div>
                        </div>

                        <div class="mt-8">
                            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider text-center mb-5">Supported mobile money providers</p>
                            <div class="overflow-hidden">
                                <div class="flex gap-4 logo-scroll">
                                    <div class="flex gap-4 shrink-0">
                                        <div class="bg-gray-50 rounded-xl px-5 py-3 flex items-center justify-center border border-gray-100 h-12"><img src="{{ asset('assets/images/providers/mpesa.png') }}" alt="M-Pesa" class="max-h-6 w-auto object-contain"></div>
                                        <div class="bg-gray-50 rounded-xl px-5 py-3 flex items-center justify-center border border-gray-100 h-12"><img src="{{ asset('assets/images/providers/equitel.png') }}" alt="Equitel" class="max-h-6 w-auto object-contain"></div>
                                        <div class="bg-gray-50 rounded-xl px-5 py-3 flex items-center justify-center border border-gray-100 h-12"><img src="{{ asset('assets/images/providers/tkash.png') }}" alt="T-kash" class="max-h-6 w-auto object-contain"></div>
                                        <div class="bg-gray-50 rounded-xl px-5 py-3 flex items-center justify-center border border-gray-100 h-12"><img src="{{ asset('assets/images/providers/vodafone.png') }}" alt="Vodafone" class="max-h-6 w-auto object-contain"></div>
                                        <div class="bg-gray-50 rounded-xl px-5 py-3 flex items-center justify-center border border-gray-100 h-12"><img src="{{ asset('assets/images/providers/mtn.png') }}" alt="MTN" class="max-h-6 w-auto object-contain"></div>
                                        <div class="bg-gray-50 rounded-xl px-5 py-3 flex items-center justify-center border border-gray-100 h-12"><img src="{{ asset('assets/images/providers/tigo.png') }}" alt="Tigo" class="max-h-6 w-auto object-contain"></div>
                                        <div class="bg-gray-50 rounded-xl px-5 py-3 flex items-center justify-center border border-gray-100 h-12"><img src="{{ asset('assets/images/providers/airtel.png') }}" alt="Airtel" class="max-h-6 w-auto object-contain"></div>
                                        <div class="bg-gray-50 rounded-xl px-5 py-3 flex items-center justify-center border border-gray-100 h-12"><span class="text-sm font-black text-gray-700 whitespace-nowrap">+ more</span></div>
                                    </div>
                                    <div class="flex gap-4 shrink-0">
                                        <div class="bg-gray-50 rounded-xl px-5 py-3 flex items-center justify-center border border-gray-100 h-12"><img src="{{ asset('assets/images/providers/mpesa.png') }}" alt="M-Pesa" class="max-h-6 w-auto object-contain"></div>
                                        <div class="bg-gray-50 rounded-xl px-5 py-3 flex items-center justify-center border border-gray-100 h-12"><img src="{{ asset('assets/images/providers/equitel.png') }}" alt="Equitel" class="max-h-6 w-auto object-contain"></div>
                                        <div class="bg-gray-50 rounded-xl px-5 py-3 flex items-center justify-center border border-gray-100 h-12"><img src="{{ asset('assets/images/providers/tkash.png') }}" alt="T-kash" class="max-h-6 w-auto object-contain"></div>
                                        <div class="bg-gray-50 rounded-xl px-5 py-3 flex items-center justify-center border border-gray-100 h-12"><img src="{{ asset('assets/images/providers/vodafone.png') }}" alt="Vodafone" class="max-h-6 w-auto object-contain"></div>
                                        <div class="bg-gray-50 rounded-xl px-5 py-3 flex items-center justify-center border border-gray-100 h-12"><img src="{{ asset('assets/images/providers/mtn.png') }}" alt="MTN" class="max-h-6 w-auto object-contain"></div>
                                        <div class="bg-gray-50 rounded-xl px-5 py-3 flex items-center justify-center border border-gray-100 h-12"><img src="{{ asset('assets/images/providers/tigo.png') }}" alt="Tigo" class="max-h-6 w-auto object-contain"></div>
                                        <div class="bg-gray-50 rounded-xl px-5 py-3 flex items-center justify-center border border-gray-100 h-12"><img src="{{ asset('assets/images/providers/airtel.png') }}" alt="Airtel" class="max-h-6 w-auto object-contain"></div>
                                        <div class="bg-gray-50 rounded-xl px-5 py-3 flex items-center justify-center border border-gray-100 h-12"><span class="text-sm font-black text-gray-700 whitespace-nowrap">+ more</span></div>
                                    </div>
                                </div>
                            </div>
                            <style>
                                .logo-scroll { animation: scrollLogos 20s linear infinite; }
                                @keyframes scrollLogos { 0% { transform: translateX(0); } 100% { transform: translateX(-50%); } }
                            </style>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    {{-- Featured Campaigns --}}
    @if(isset($campaigns) && $campaigns->count() > 0)
    <section id="trending" class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col lg:flex-row lg:items-end justify-between mb-10 lg:mb-16">
                <div>
                    <h2 class="text-4xl lg:text-5xl font-black text-navy tracking-tight leading-tight reveal">Trending Fundraisers</h2>
                    <div class="w-16 h-1 bg-terracotta rounded-full mt-4"></div>
                </div>
                <a href="/#trending" class="group mt-6 lg:mt-0 inline-flex items-center gap-2 text-maroon font-semibold text-lg hover:text-maroon-dark transition reveal">
                    View All
                    <svg class="w-5 h-5 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                    </svg>
                </a>
            </div>

            <div class="relative">
                <div class="overflow-hidden rounded-2xl">
                    <div id="campaigns-slider" class="flex transition-transform duration-500 ease-in-out">
                        @php $chunks = $campaigns->chunk(3); @endphp
                        @foreach($chunks as $chunk)
                        <div class="min-w-full grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 px-1">
                            @foreach($chunk as $campaign)
                            <div class="campaign-card bg-white rounded-2xl border border-gray-100 shadow-sm hover:shadow-2xl hover:shadow-maroon/10 overflow-hidden group reveal">
                                <div class="h-56 bg-gray-100 relative overflow-hidden">
                                    @if($campaign->getFirstMediaUrl('cover_image'))
                                        <img src="{{ $campaign->getFirstMediaUrl('cover_image') }}" alt="{{ $campaign->title }}" class="w-full h-full object-cover group-hover:scale-110 transition duration-700">
                                    @else
                                        <img src="{{ asset('assets/images/default.png') }}" alt="{{ $campaign->title }}" class="w-full h-full object-contain p-4">
                                    @endif
                                    @if($campaign->category)
                                        <span class="absolute top-4 left-4 text-white text-xs font-bold px-3 py-1.5 rounded-full shadow-sm" style="background-color: #1b2a4a">{{ $campaign->category->name }}</span>
                                    @endif
                                    <div class="absolute inset-0 bg-black/20 opacity-0 group-hover:opacity-100 transition duration-500"></div>
                                </div>
                                <div class="p-6 flex flex-col flex-1">
                                    <a href="{{ route('campaigns.show', $campaign) }}">
                                        <h3 class="text-lg font-bold text-gray-900 group-hover:text-maroon-dark transition line-clamp-1">{{ $campaign->title }}</h3>
                                    </a>
                                    <div class="mt-5">
                                        <div class="flex justify-between text-sm mb-2">
                                            <span class="font-bold text-gray-900">KES {{ number_format($campaign->raised_amount) }}</span>
                                            <span class="text-gray-400">{{ $campaign->progressPercent() }}%</span>
                                        </div>
                                        <div class="bg-gray-100 rounded-full h-3 overflow-hidden">
                                            <div class="progress-fill rounded-full h-3 shadow-inner" style="width: {{ $campaign->progressPercent() }}%; background-color: #475b06"></div>
                                        </div>
                                        <p class="text-xs text-gray-500 font-semibold mt-2">of KES {{ number_format($campaign->target_amount) }} goal</p>
                                    </div>
                                    <a href="{{ route('campaigns.show', $campaign) }}"
                                       class="mt-5 w-full inline-flex items-center justify-center gap-2 text-white font-semibold py-3 px-6 rounded-xl transition-all shadow-lg" style="background-color: #CE5F26; box-shadow: 0 10px 15px -3px rgba(206, 95, 38, 0.25);" onmouseover="this.style.backgroundColor='#B04E1E'; this.style.boxShadow='0 10px 15px -3px rgba(206, 95, 38, 0.4)'" onmouseout="this.style.backgroundColor='#CE5F26'; this.style.boxShadow='0 10px 15px -3px rgba(206, 95, 38, 0.25)'">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                        </svg>
                                        Donate Now
                                    </a>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @endforeach
                    </div>
                </div>

                @if($chunks->count() > 1)
                <div class="flex justify-center gap-2 mt-8">
                    @foreach($chunks as $s => $chunk)
                    <button onclick="goToCampaignSlide({{ $s }})" class="w-3 h-3 rounded-full campaign-dot border-0 cursor-pointer transition-all {{ $s === 0 ? 'bg-maroon' : 'bg-gray-300' }}"></button>
                    @endforeach
                </div>
                @endif
            </div>
        </div>
    </section>
    @endif

    {{-- Features --}}
    <section id="features" class="py-16 lg:py-28" style="background-color: #fdf8f0;">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16 lg:mb-20">
                <h2 class="text-4xl lg:text-5xl font-black text-navy tracking-tight leading-tight reveal">Why Support Sphere</h2>
                <div class="w-16 h-1 bg-terracotta rounded-full mx-auto mt-4"></div>
            </div>

            <div class="grid grid-cols-2 lg:grid-cols-4 gap-x-12 gap-y-16">
                <div class="flex flex-col items-center text-center reveal">
                    <div class="w-16 h-16 flex items-center justify-center mb-5">
                        <svg class="w-10 h-10" style="color: #1b2a4a;" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </div>
                    <p class="text-sm font-semibold text-gray-800 leading-snug">Easy set up and management</p>
                </div>

                <div class="flex flex-col items-center text-center reveal reveal-delay-1">
                    <div class="w-16 h-16 flex items-center justify-center mb-5">
                        <svg class="w-10 h-10" style="color: #1b2a4a;" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 1.5H8.25A2.25 2.25 0 006 3.75v16.5a2.25 2.25 0 002.25 2.25h7.5A2.25 2.25 0 0018 20.25V3.75a2.25 2.25 0 00-2.25-2.25H13.5m-3 0V3h3V1.5m-3 0h3m-3 18.75h3" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 18.75a.75.75 0 100-1.5.75.75 0 000 1.5z" />
                        </svg>
                    </div>
                    <p class="text-sm font-semibold text-gray-800 leading-snug">Mobile money donations</p>
                </div>

                <div class="flex flex-col items-center text-center reveal reveal-delay-2">
                    <div class="w-16 h-16 flex items-center justify-center mb-5">
                        <svg class="w-10 h-10" style="color: #1b2a4a;" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M7.217 10.907a2.25 2.25 0 100 2.186m0-2.186c.18.324.283.696.283 1.093s-.103.77-.283 1.093m0-2.186l9.566-5.314m-9.566 7.5l9.566 5.314m0 0a2.25 2.25 0 103.935 2.186 2.25 2.25 0 00-3.935-2.186zm0-12.814a2.25 2.25 0 103.933-2.185 2.25 2.25 0 00-3.933 2.185z" />
                        </svg>
                    </div>
                    <p class="text-sm font-semibold text-gray-800 leading-snug">Share campaign with a single click</p>
                </div>

                <div class="flex flex-col items-center text-center reveal reveal-delay-3">
                    <div class="w-16 h-16 flex items-center justify-center mb-5">
                        <svg class="w-10 h-10" style="color: #1b2a4a;" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z" />
                        </svg>
                    </div>
                    <p class="text-sm font-semibold text-gray-800 leading-snug">Automated campaign reports</p>
                </div>

                <div class="flex flex-col items-center text-center reveal reveal-delay-4">
                    <div class="w-16 h-16 flex items-center justify-center mb-5">
                        <svg class="w-10 h-10" style="color: #1b2a4a;" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z" />
                        </svg>
                    </div>
                    <p class="text-sm font-semibold text-gray-800 leading-snug">Enhance donations security</p>
                </div>

                <div class="flex flex-col items-center text-center reveal reveal-delay-5">
                    <div class="w-16 h-16 flex items-center justify-center mb-5">
                        <svg class="w-10 h-10" style="color: #1b2a4a;" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125V9M6.75 21h10.5" />
                        </svg>
                    </div>
                    <p class="text-sm font-semibold text-gray-800 leading-snug">Flexible withdrawal options</p>
                </div>

                <div class="flex flex-col items-center text-center reveal reveal-delay-6">
                    <div class="w-16 h-16 flex items-center justify-center mb-5">
                        <svg class="w-10 h-10" style="color: #1b2a4a;" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 8.511c.884.284 1.5 1.128 1.5 2.097v4.286c0 1.136-.847 2.1-1.98 2.193-.34.027-.68.052-1.02.072v3.091l-3-3c-1.354 0-2.694-.055-4.02-.163a2.115 2.115 0 01-.825-.242m9.345-8.334a2.126 2.126 0 00-.476-.095 48.64 48.64 0 00-8.048 0c-1.131.094-1.976 1.057-1.976 2.192v4.286c0 .837.46 1.58 1.155 1.951m9.345-8.334V6.637c0-1.621-1.152-3.026-2.76-3.235A48.455 48.455 0 0011.25 3c-2.115 0-4.198.137-6.24.402-1.608.209-2.76 1.614-2.76 3.235v6.226c0 1.621 1.152 3.026 2.76 3.235.577.075 1.157.14 1.74.194V21l4.155-4.155" />
                        </svg>
                    </div>
                    <p class="text-sm font-semibold text-gray-800 leading-snug">Live customer care</p>
                </div>

                <div class="flex flex-col items-center text-center reveal reveal-delay-7">
                    <div class="w-16 h-16 flex items-center justify-center mb-5">
                        <svg class="w-10 h-10" style="color: #1b2a4a;" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9.004 9.004 0 008.716-6.747M12 21a9.004 9.004 0 01-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3m0 0a8.997 8.997 0 017.843 4.582M12 3a8.997 8.997 0 00-7.843 4.582m15.686 0A11.953 11.953 0 0112 10.5c-2.998 0-5.74-1.1-7.843-2.918m15.686 0A8.959 8.959 0 0121 12c0 .778-.099 1.533-.284 2.253m0 0A17.919 17.919 0 0112 16.5c-3.162 0-6.133-.815-8.716-2.247m0 0A9.015 9.015 0 013 12c0-1.605.42-3.113 1.157-4.418" />
                        </svg>
                    </div>
                    <p class="text-sm font-semibold text-gray-800 leading-snug">Global & Monthly donation options</p>
                </div>
            </div>
        </div>
    </section>

    {{-- Testimonials --}}
    <section class="py-10 lg:py-16 bg-white relative overflow-hidden">
        <div class="absolute top-0 right-0 w-96 h-96 bg-maroon/10 rounded-full blur-3xl opacity-30"></div>
        <div class="absolute bottom-0 left-0 w-96 h-96 bg-terracotta/10 rounded-full blur-3xl opacity-30"></div>
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-10 lg:mb-16">
                <h2 class="text-4xl lg:text-5xl font-black text-gray-900 reveal tracking-tight">What Our Users Say</h2>
                <p class="text-gray-500 text-lg lg:text-xl mt-4 reveal reveal-delay-2">Real stories from real people in our community.</p>
            </div>

            @if($testimonials->count() > 0)
            <div class="relative">
                <div class="overflow-hidden">
                    <div id="testimonials-slider" class="flex transition-transform duration-500 ease-in-out">
                        @php $testimonialChunks = $testimonials->chunk(3); @endphp
                        @foreach($testimonialChunks as $chunk)
                        <div class="min-w-full grid grid-cols-1 md:grid-cols-3 gap-8">
                            @foreach($chunk as $item)
                            <div class="testimonial-card bg-white p-6 lg:p-8 rounded-2xl border border-maroon/10 shadow-sm text-center flex flex-col">
                                <div class="flex justify-center items-center gap-1 mb-4">
                                    @for($s = 0; $s < ($item->rating ?? 5); $s++)
                                    <svg class="w-4 h-4 text-terracotta fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" /></svg>
                                    @endfor
                                </div>
                                <p class="text-gray-600 leading-relaxed mb-6 flex-1 text-base italic">&ldquo;{{ $item->content }}&rdquo;</p>
                                <div class="flex items-center justify-center gap-3">
                                    <div class="w-10 h-10 bg-maroon rounded-full flex items-center justify-center text-white font-bold text-sm shadow-lg shrink-0">{{ $item->initials }}</div>
                                    <div class="text-left">
                                        <p class="font-semibold text-gray-900 text-sm">{{ $item->name }}</p>
                                        @if($item->location)
                                        <p class="text-xs text-gray-500">{{ $item->location }}</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @endforeach
                    </div>
                </div>

                @if($testimonialChunks->count() > 1)
                <div class="flex justify-center gap-2 mt-8">
                    @foreach($testimonialChunks as $t => $chunk)
                    <button onclick="goToTestimonialSlide({{ $t }})" class="w-3 h-3 rounded-full testimonial-dot border-0 cursor-pointer transition-all {{ $t === 0 ? 'bg-maroon' : 'bg-gray-300' }}"></button>
                    @endforeach
                </div>
                @endif
            </div>
            @endif
        </div>
    </section>

    {{-- CTA Section --}}
    <section class="relative py-10 lg:py-20 overflow-hidden">
        <div class="absolute inset-0">
            <img src="{{ asset('assets/images/sliders/slide-1.jpg') }}" alt="" class="w-full h-full object-cover">
        </div>
        <div class="absolute inset-0" style="background-color: rgba(27, 42, 74, 0.85);"></div>
        <div class="relative max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-4xl lg:text-6xl font-black text-white leading-tight reveal tracking-tight">
                Ready to Make a<br>
                <span class="text-terracotta">Difference?</span>
            </h2>
            <p class="mt-6 text-lg lg:text-xl text-white/70 max-w-2xl mx-auto reveal reveal-delay-2">
                Start your fundraiser today and rally your community around a cause that matters. It's free, fast, and secure.
            </p>
            <div class="mt-10 lg:mt-12 flex flex-col sm:flex-row gap-4 justify-center reveal reveal-delay-3">
                <a href="{{ route('register') }}" class="group inline-flex items-center justify-center px-8 py-4 lg:px-10 lg:py-5 bg-terracotta text-gray-900 font-bold rounded-full hover:bg-terracotta-dark transition-all text-base lg:text-lg shadow-2xl shadow-terracotta/30 hover:shadow-terracotta/50 hover:-translate-y-0.5">
                    Start Your Fundraiser
                    <svg class="w-5 h-5 ml-2 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                    </svg>
                </a>
                <a href="/#trending" class="group inline-flex items-center justify-center px-8 py-4 lg:px-10 lg:py-5 text-white font-semibold rounded-full border border-white/15 backdrop-blur-sm hover:border-white/30" style="background-color: #CE5F26;">
                    Browse Campaigns
                </a>
            </div>
        </div>
    </section>

    {{-- Partner Logos --}}
    <section class="py-12 lg:py-16 bg-white overflow-hidden">
        <div class="px-4 sm:px-6 lg:px-8">
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-widest text-center mb-8 lg:mb-10">Trusted by leading organizations</p>
            <div class="relative">
                <div class="flex overflow-hidden">
                    <div class="flex gap-10 lg:gap-16 items-center logo-slide">
                        <img src="{{ asset('assets/images/providers/mpesa.png') }}" alt="M-Pesa" class="h-8 lg:h-10 w-auto transition">
                        <img src="{{ asset('assets/images/providers/equitel.png') }}" alt="Equitel" class="h-8 lg:h-10 w-auto transition">
                        <img src="{{ asset('assets/images/providers/tkash.png') }}" alt="T-kash" class="h-8 lg:h-10 w-auto transition">
                        <img src="{{ asset('assets/images/providers/vodafone.png') }}" alt="Vodafone" class="h-8 lg:h-10 w-auto transition">
                        <img src="{{ asset('assets/images/providers/mtn.png') }}" alt="MTN" class="h-8 lg:h-10 w-auto transition">
                        <img src="{{ asset('assets/images/providers/tigo.png') }}" alt="Tigo" class="h-8 lg:h-10 w-auto transition">
                        <img src="{{ asset('assets/images/providers/airtel.png') }}" alt="Airtel" class="h-8 lg:h-10 w-auto transition">
                    </div>
                    <div class="flex gap-10 lg:gap-16 items-center logo-slide" aria-hidden="true">
                        <img src="{{ asset('assets/images/providers/mpesa.png') }}" alt="M-Pesa" class="h-8 lg:h-10 w-auto transition">
                        <img src="{{ asset('assets/images/providers/equitel.png') }}" alt="Equitel" class="h-8 lg:h-10 w-auto transition">
                        <img src="{{ asset('assets/images/providers/tkash.png') }}" alt="T-kash" class="h-8 lg:h-10 w-auto transition">
                        <img src="{{ asset('assets/images/providers/vodafone.png') }}" alt="Vodafone" class="h-8 lg:h-10 w-auto transition">
                        <img src="{{ asset('assets/images/providers/mtn.png') }}" alt="MTN" class="h-8 lg:h-10 w-auto transition">
                        <img src="{{ asset('assets/images/providers/tigo.png') }}" alt="Tigo" class="h-8 lg:h-10 w-auto transition">
                        <img src="{{ asset('assets/images/providers/airtel.png') }}" alt="Airtel" class="h-8 lg:h-10 w-auto transition">
                    </div>
                </div>
            </div>
            <style>
                .logo-slide { animation: slideLogos 40s linear infinite; }
                .logo-slide:hover { animation-play-state: paused; }
                @keyframes slideLogos {
                    0% { transform: translateX(0); }
                    100% { transform: translateX(-100%); }
                }
            </style>
        </div>
    </section>

    @include('partials.footer')

    {{-- Accessibility Widget --}}
    <button class="accessibility-btn" onclick="toggleAccessibilityPanel()" aria-label="Accessibility options">
        <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <circle cx="12" cy="12" r="10"/><circle cx="12" cy="6" r="1.5" fill="currentColor" stroke="none"/>
            <path d="M8 12h2l1 6 1-6 1 6 1-6h2"/><path d="M11 10l-1-2h4l-1 2"/>
        </svg>
    </button>

    <div class="accessibility-panel" id="a11y-panel">
        <h3>Accessibility</h3>

        <div class="a11y-option">
            <span>Font Size</span>
            <div class="flex gap-1.5">
                <button onclick="changeFontSize(-1)" title="Decrease font size">A-</button>
                <button onclick="resetFontSize()" title="Reset font size" style="font-size:11px; width:32px;">R</button>
                <button onclick="changeFontSize(1)" title="Increase font size">A+</button>
            </div>
        </div>

        <div class="a11y-option">
            <span>High Contrast</span>
            <button class="a11y-toggle" id="contrast-toggle" onclick="toggleContrast()" aria-label="Toggle high contrast"></button>
        </div>

        <div class="a11y-option">
            <span>Grayscale</span>
            <button class="a11y-toggle" id="grayscale-toggle" onclick="toggleGrayscale()" aria-label="Toggle grayscale"></button>
        </div>

        <div class="a11y-option">
            <span>Reset All</span>
            <button onclick="resetAccessibility()" style="width:auto; padding:0 12px; font-size:12px; color:#dc2626; border-color:#fca5a5;">Reset</button>
        </div>
    </div>

    <script>
        function toggleAccessibilityPanel() {
            document.getElementById('a11y-panel').classList.toggle('open');
        }
        document.addEventListener('click', function(e) {
            const panel = document.getElementById('a11y-panel');
            const btn = document.querySelector('.accessibility-btn');
            if (!panel.contains(e.target) && !btn.contains(e.target)) {
                panel.classList.remove('open');
            }
        });

        let fontSizeLevel = 0;
        function changeFontSize(dir) {
            fontSizeLevel = Math.max(-2, Math.min(2, fontSizeLevel + dir));
            document.body.classList.remove('a11y-large-text');
            if (fontSizeLevel > 0) document.body.classList.add('a11y-large-text');
        }
        function resetFontSize() {
            fontSizeLevel = 0;
            document.body.classList.remove('a11y-large-text');
        }

        function toggleContrast() {
            document.body.classList.toggle('a11y-contrast');
            document.getElementById('contrast-toggle').classList.toggle('active');
        }
        function toggleGrayscale() {
            document.body.classList.toggle('a11y-grayscale');
            document.getElementById('grayscale-toggle').classList.toggle('active');
        }
        function resetAccessibility() {
            fontSizeLevel = 0;
            document.body.classList.remove('a11y-large-text', 'a11y-contrast', 'a11y-grayscale');
            document.querySelectorAll('.a11y-toggle').forEach(el => el.classList.remove('active'));
        }

        let slideIndex = 0;
        let slideInterval;

        function slideHowTo(index) {
            const slider = document.getElementById('how-slider');
            if (!slider) return;
            slideIndex = index;
            slider.style.transform = 'translateX(-' + (index * 100) + '%)';
            document.querySelectorAll('.slider-dot').forEach((dot, i) => {
                dot.classList.toggle('bg-navy', i === index);
                dot.classList.toggle('bg-gray-300', i !== index);
            });
            resetSlideInterval();
        }

        let heroSlideIndex = 0;
        let heroSlideInterval;

        function slideHero(index) {
            const slider = document.getElementById('hero-slider');
            if (!slider) return;
            heroSlideIndex = index;
            slider.style.transform = 'translateX(-' + (index * 100) + '%)';
            document.querySelectorAll('.hero-dot').forEach((dot, i) => {
                dot.classList.toggle('bg-terracotta', i === index);
                dot.classList.toggle('bg-white/30', i !== index);
            });
            resetHeroSlideInterval();
        }

        function resetHeroSlideInterval() {
            clearInterval(heroSlideInterval);
            heroSlideInterval = setInterval(function () {
                const total = document.querySelectorAll('#hero-slider > div').length;
                slideHero((heroSlideIndex + 1) % total);
            }, 4000);
        }

        let campaignSlideIndex = 0;
        let campaignSlideInterval;

        function slideCampaigns(dir) {
            const slider = document.getElementById('campaigns-slider');
            if (!slider) return;
            const total = slider.children.length;
            if (dir === 'prev') {
                campaignSlideIndex = (campaignSlideIndex - 1 + total) % total;
            } else {
                campaignSlideIndex = (campaignSlideIndex + 1) % total;
            }
            slider.style.transform = 'translateX(-' + (campaignSlideIndex * 100) + '%)';
            document.querySelectorAll('.campaign-dot').forEach((dot, i) => {
                dot.classList.toggle('bg-maroon', i === campaignSlideIndex);
                dot.classList.toggle('bg-gray-300', i !== campaignSlideIndex);
            });
            resetCampaignSlideInterval();
        }

        function goToCampaignSlide(index) {
            const slider = document.getElementById('campaigns-slider');
            if (!slider) return;
            campaignSlideIndex = index;
            slider.style.transform = 'translateX(-' + (index * 100) + '%)';
            document.querySelectorAll('.campaign-dot').forEach((dot, i) => {
                dot.classList.toggle('bg-maroon', i === index);
                dot.classList.toggle('bg-gray-300', i !== index);
            });
            resetCampaignSlideInterval();
        }

        let testimonialSlideIndex = 0;
        let testimonialSlideInterval;

        function goToTestimonialSlide(index) {
            const slider = document.getElementById('testimonials-slider');
            if (!slider) return;
            testimonialSlideIndex = index;
            slider.style.transform = 'translateX(-' + (index * 100) + '%)';
            document.querySelectorAll('.testimonial-dot').forEach((dot, i) => {
                dot.classList.toggle('bg-maroon', i === index);
                dot.classList.toggle('bg-gray-300', i !== index);
            });
            resetTestimonialSlideInterval();
        }

        function resetTestimonialSlideInterval() {
            clearInterval(testimonialSlideInterval);
            const slider = document.getElementById('testimonials-slider');
            if (!slider) return;
            const total = slider.children.length;
            if (total <= 1) return;
            testimonialSlideInterval = setInterval(function () {
                const next = (testimonialSlideIndex + 1) % total;
                goToTestimonialSlide(next);
            }, 6000);
        }

        function resetCampaignSlideInterval() {
            clearInterval(campaignSlideInterval);
            const slider = document.getElementById('campaigns-slider');
            if (!slider) return;
            const total = slider.children.length;
            if (total <= 1) return;
            campaignSlideInterval = setInterval(function () {
                slideCampaigns('next');
            }, 5000);
        }

        function resetSlideInterval() {
            clearInterval(slideInterval);
            slideInterval = setInterval(function () {
                const total = document.querySelectorAll('#how-slider > div').length;
                slideHowTo((slideIndex + 1) % total);
            }, 4000);
        }

        document.addEventListener('DOMContentLoaded', function () {
            // Auto-slide hero carousel
            if (document.getElementById('hero-slider')) {
                resetHeroSlideInterval();
            }

            // Auto-slide how it works carousel
            if (document.getElementById('how-slider')) {
                resetSlideInterval();
            }

            // Auto-slide campaigns carousel
            if (document.getElementById('campaigns-slider')) {
                resetCampaignSlideInterval();
            }

            // Auto-slide testimonials carousel
            if (document.getElementById('testimonials-slider')) {
                resetTestimonialSlideInterval();
            }

            // Scroll-triggered reveal animations
            const revealObserver = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('visible');
                        // Trigger progress bar fill for campaign cards
                        const progressBar = entry.target.querySelector('.progress-fill');
                        if (progressBar && progressBar.style.width === '0%') {
                            setTimeout(() => {
                                progressBar.style.width = progressBar.dataset.width || '0%';
                            }, 300);
                        }
                    }
                });
            }, { threshold: 0.1, rootMargin: '0px 0px -50px 0px' });

            document.querySelectorAll('.reveal').forEach(el => {
                // Store progress bar width
                const progressBar = el.querySelector('.progress-fill');
                if (progressBar) {
                    progressBar.dataset.width = progressBar.style.width;
                    progressBar.style.width = '0%';
                }
                revealObserver.observe(el);
            });

            // Counter animation
            function abbreviateNumber(num) {
                if (num >= 1000000) return (num / 1000000).toFixed(1).replace(/\.0$/, '') + 'M';
                if (num >= 1000) return (num / 1000).toFixed(1).replace(/\.0$/, '') + 'K';
                return num.toString();
            }

            function animateCounter(el) {
                const target = parseFloat(el.dataset.target);
                if (target === 0) { el.textContent = '0'; return; }
                if (target < 20) { el.textContent = target.toString(); return; }
                const duration = 2000;
                const steps = 60;
                const increment = target / steps;
                let current = 0;
                let step = 0;
                const timer = setInterval(() => {
                    step++;
                    current = Math.min(Math.round(increment * step), target);
                    el.textContent = abbreviateNumber(current);
                    if (step >= steps) { clearInterval(timer); el.textContent = abbreviateNumber(target); }
                }, duration / steps);
            }

            const counterObserver = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const num = entry.target;
                        animateCounter(num);
                        counterObserver.unobserve(num);
                    }
                });
            }, { threshold: 0.5 });

            document.querySelectorAll('.stat-number').forEach(el => counterObserver.observe(el));

            // Navbar background on scroll
            const navbar = document.getElementById('navbar');
            let lastScroll = 0;
            window.addEventListener('scroll', () => {
                const currentScroll = window.pageYOffset;
                if (currentScroll > 50) {
                    navbar.classList.add('nav-scrolled');
                    navbar.style.backgroundColor = currentScroll > lastScroll ? 'rgba(255,255,255,0.95)' : 'rgba(255,255,255,0.98)';
                } else {
                    navbar.classList.remove('nav-scrolled');
                    navbar.style.backgroundColor = 'rgba(255,255,255,0.85)';
                }
                lastScroll = currentScroll;
            });
        });
    </script>
</body>
</html>
