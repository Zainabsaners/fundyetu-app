<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/png" href="{{ asset('assets/images/logo/favicon.png') }}">
    <title>Thank You - Support Sphere</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800,900&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        .nav-blur { backdrop-filter: blur(16px); -webkit-backdrop-filter: blur(16px); }
        .love-bubbles { position: absolute; inset: 0; pointer-events: none; overflow: hidden; }
        .love-bubbles span {
            position: absolute; bottom: -20px; font-size: 18px;
            animation: floatUp 6s ease-in-out infinite;
            animation-delay: calc(var(--i) * 0.4s);
            left: calc(var(--i) * 8%); opacity: 0;
        }
        .love-bubbles span::before { content: '\2764'; color: rgba(206, 95, 38, 0.25); }
        @keyframes floatUp {
            0% { transform: translateY(0) scale(0.5); opacity: 0; }
            20% { opacity: 1; }
            80% { opacity: 1; }
            100% { transform: translateY(-350px) scale(1.2); opacity: 0; }
        }
    </style>
</head>
<body class="font-sans antialiased bg-gray-50">
    <nav id="navbar" class="fixed top-0 left-0 right-0 z-50 nav-blur transition-all duration-300" style="background-color: rgba(255,255,255,0.85);">
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
                    <a href="/#how-it-works" class="text-gray-600 hover:text-maroon-dark font-medium transition relative after:absolute after:bottom-0 after:left-0 after:h-0.5 after:w-0 after:bg-maroon after:transition-all hover:after:w-full">How It Works</a>
                    <a href="/#features" class="text-gray-600 hover:text-maroon-dark font-medium transition relative after:absolute after:bottom-0 after:left-0 after:h-0.5 after:w-0 after:bg-maroon after:transition-all hover:after:w-full">Features</a>
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
                <a href="/#how-it-works" class="block px-4 py-3 rounded-xl text-gray-600 hover:bg-maroon/[0.03] hover:text-maroon-dark font-medium transition">How It Works</a>
                <a href="/#features" class="block px-4 py-3 rounded-xl text-gray-600 hover:bg-maroon/[0.03] hover:text-maroon-dark font-medium transition">Features</a>
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

    <div class="pt-28 lg:pt-36 pb-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8 py-8">
            <div class="bg-white rounded-2xl shadow-2xl border border-gray-100 p-10 sm:p-12 text-center relative overflow-hidden">
                <div class="relative z-10">
                    <div class="flex items-center justify-center mx-auto mb-5">
                        <svg class="w-12 h-12 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                    <h3 class="text-3xl font-extrabold text-gray-900 mb-3">Thank You, {{ $donation->donor_name ?? 'Friend' }}!</h3>
                    <p class="text-gray-600 text-lg mb-4">
                        Your donation of <strong>KES {{ number_format($donation->amount, 0) }}</strong>
                        to "{{ $donation->campaign->title }}" has been received successfully.
                    </p>

                    @if($donation->payment_ref)
                        <p class="text-sm text-gray-500 mb-6">
                            M-Pesa Receipt: <span class="font-mono font-semibold">{{ $donation->payment_ref }}</span>
                        </p>
                    @endif

                    <p class="text-gray-500 text-base mb-10">
                        Your support makes a real difference. Together, we're changing lives.
                    </p>

                    <a href="/#trending"
                       class="inline-flex items-center px-10 py-4 bg-[#CE5F26] text-white font-bold rounded-full hover:bg-[#b85422] transition shadow-lg shadow-[#CE5F26]/30 mb-4">
                        Back to Campaigns
                    </a>
                </div>

                <div class="love-bubbles" aria-hidden="true">
                    <span style="--i:1"></span>
                    <span style="--i:2"></span>
                    <span style="--i:3"></span>
                    <span style="--i:4"></span>
                    <span style="--i:5"></span>
                    <span style="--i:6"></span>
                    <span style="--i:7"></span>
                    <span style="--i:8"></span>
                    <span style="--i:9"></span>
                    <span style="--i:10"></span>
                    <span style="--i:11"></span>
                    <span style="--i:12"></span>
                </div>
            </div>
        </div>

        @if(isset($campaigns) && $campaigns->count() > 0)
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-16">
            <h2 class="text-2xl font-bold text-gray-900 text-center mb-2">More campaigns you can support</h2>
            <p class="text-gray-500 text-center mb-10">Your generosity inspires others. Here are more fundraisers making a difference.</p>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($campaigns as $campaign)
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden group hover:shadow-lg transition">
                    <div class="h-48 bg-gray-100 relative overflow-hidden">
                        @if($campaign->getFirstMediaUrl('cover_image'))
                            <img src="{{ $campaign->getFirstMediaUrl('cover_image') }}" alt="{{ $campaign->title }}" class="w-full h-full object-cover group-hover:scale-110 transition duration-700">
                        @else
                            <img src="{{ asset('assets/images/default.png') }}" alt="{{ $campaign->title }}" class="w-full h-full object-contain p-4">
                        @endif
                        @if($campaign->category)
                            <span class="absolute top-4 left-4 text-white text-xs font-bold px-3 py-1.5 rounded-full shadow-sm" style="background-color: #1b2a4a">{{ $campaign->category->name }}</span>
                        @endif
                    </div>
                    <div class="p-5">
                        <a href="{{ route('campaigns.show', $campaign) }}">
                            <h3 class="text-base font-bold text-gray-900 group-hover:text-[#CE5F26] transition line-clamp-1">{{ $campaign->title }}</h3>
                        </a>
                        <div class="mt-4">
                            <div class="flex justify-between text-sm mb-1">
                                <span class="font-bold text-gray-900">KES {{ number_format($campaign->raised_amount) }}</span>
                                <span class="text-gray-400">{{ $campaign->progressPercent() }}%</span>
                            </div>
                            <div class="bg-gray-100 rounded-full h-2 overflow-hidden">
                                <div class="rounded-full h-2" style="width: {{ $campaign->progressPercent() }}%; background-color: #475b06"></div>
                            </div>
                            <p class="text-xs text-gray-500 mt-1">of KES {{ number_format($campaign->target_amount) }} goal</p>
                        </div>
                        <a href="{{ route('campaigns.show', $campaign) }}"
                           class="mt-4 w-full inline-flex items-center justify-center gap-2 text-white font-semibold py-2.5 px-6 rounded-xl transition shadow-md hover:shadow-lg" style="background-color: #CE5F26;">
                            Donate Now
                        </a>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>

    @include('partials.footer')

    <script>
        document.addEventListener('DOMContentLoaded', function () {
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
