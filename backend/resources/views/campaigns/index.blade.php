<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/png" href="{{ asset('assets/images/logo/favicon.png') }}">
    <title>Explore Fundraisers - {{ config('app.name', 'Support Sphere') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800,900&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        html { scroll-behavior: smooth; }

        .reveal { opacity: 0; transform: translateY(30px); transition: all 0.7s cubic-bezier(0.16, 1, 0.3, 1); }
        .reveal.visible { opacity: 1; transform: translateY(0); }
        .reveal-delay-1 { transition-delay: 0.1s; }
        .reveal-delay-2 { transition-delay: 0.2s; }

        .campaign-card { transition: all 0.5s cubic-bezier(0.16, 1, 0.3, 1); }
        .campaign-card:hover { transform: translateY(-6px); }

        .progress-fill { transition: width 1.2s cubic-bezier(0.16, 1, 0.3, 1); }

        .nav-blur {
            backdrop-filter: blur(12px);
            background: rgba(255,255,255,0.85);
        }
        .nav-scrolled { box-shadow: 0 1px 3px rgba(0,0,0,0.08); }

        .filter-btn {
            transition: all 0.2s ease;
        }
        .filter-btn.active {
            background: #7B2D2D;
            color: white;
            border-color: #7B2D2D;
        }

        .banner-gradient {
            background: #1B2A4A;
        }

        .hero-ornament {
            position: absolute;
            border-radius: 50%;
            filter: blur(80px);
            opacity: 0.12;
            animation: ornamentFloat 20s ease-in-out infinite;
        }
        @keyframes ornamentFloat {
            0%, 100% { transform: translate(0, 0) scale(1); }
            25% { transform: translate(30px, -30px) scale(1.1); }
            50% { transform: translate(-20px, 20px) scale(0.9); }
            75% { transform: translate(20px, 30px) scale(1.05); }
        }
    </style>
</head>
<body class="font-sans antialiased text-gray-900 bg-gray-50/30">

    {{-- Navigation --}}
    <nav id="navbar" class="fixed top-0 left-0 right-0 z-50 nav-blur transition-all duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 lg:h-20 items-center">
                <a href="{{ route('home') }}" class="flex items-center shrink-0">
                    <img src="{{ asset('assets/images/logo/logo.png') }}" alt="Support Sphere" class="h-8 lg:h-9 w-auto">
                </a>

                <div class="hidden md:flex items-center gap-8">
                    <a href="/#trending" class="text-maroon font-medium relative after:absolute after:bottom-0 after:left-0 after:h-0.5 after:w-full after:bg-maroon">Explore</a>
                    <a href="{{ route('home') }}#how-it-works" class="text-gray-600 hover:text-maroon-dark font-medium transition relative after:absolute after:bottom-0 after:left-0 after:h-0.5 after:w-0 after:bg-maroon after:transition-all hover:after:w-full">How It Works</a>
                    <a href="{{ route('home') }}#features" class="text-gray-600 hover:text-maroon-dark font-medium transition relative after:absolute after:bottom-0 after:left-0 after:h-0.5 after:w-0 after:bg-maroon after:transition-all hover:after:w-full">Features</a>
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

                <button type="button" id="mobile-menu-btn" class="md:hidden p-2 rounded-full text-gray-500 hover:text-gray-700 hover:bg-gray-100 transition" onclick="document.getElementById('mobile-menu').classList.toggle('hidden')">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
            </div>
        </div>

        <div id="mobile-menu" class="hidden md:hidden border-t border-gray-100 bg-white/95 backdrop-blur-lg">
            <div class="px-4 py-4 space-y-2">
                <a href="/#trending" class="block px-4 py-3 rounded-xl bg-maroon/[0.03] text-maroon font-medium">Explore</a>
                <a href="{{ route('home') }}#how-it-works" class="block px-4 py-3 rounded-xl text-gray-600 hover:bg-maroon/[0.03] hover:text-maroon-dark font-medium transition">How It Works</a>
                <a href="{{ route('home') }}#features" class="block px-4 py-3 rounded-xl text-gray-600 hover:bg-maroon/[0.03] hover:text-maroon-dark font-medium transition">Features</a>
                @auth
                    @php $isAdmin = auth()->user()->roles->pluck('name')->intersect(['admin', 'super_admin'])->isNotEmpty(); @endphp
                    <a href="{{ $isAdmin ? route('admin.dashboard') : route('dashboard') }}" class="block px-4 py-3 rounded-xl text-gray-600 hover:bg-maroon/[0.03] hover:text-maroon-dark font-medium transition">Dashboard</a>
                    <a href="{{ route('campaigns.create') }}" class="block px-4 py-3 text-center bg-[#CE5F26] text-white font-semibold rounded-full hover:bg-[#B04E1E] transition">Start a Fundraiser</a>
                @else
                    <a href="{{ route('login') }}" class="block px-4 py-3 rounded-xl text-gray-600 hover:bg-maroon/[0.03] hover:text-maroon-dark font-medium transition">Log in</a>
                    <a href="{{ route('register') }}" class="block px-4 py-3 text-center bg-[#CE5F26] text-white font-semibold rounded-full hover:bg-[#B04E1E] transition">Start a Fundraiser</a>
                @endauth
            </div>
        </div>
    </nav>

    {{-- Banner --}}
    <section class="relative pt-28 lg:pt-32 pb-16 lg:pb-20 overflow-hidden banner-gradient">
        <div class="hero-ornament w-[400px] h-[400px] bg-terracotta" style="top: -20%; right: -5%;"></div>
        <div class="hero-ornament w-[300px] h-[300px] bg-forest" style="bottom: -30%; left: -8%; animation-delay: -7s;"></div>
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto">
                <div class="inline-flex items-center gap-2 bg-white/10 backdrop-blur-md text-white/90 text-sm font-medium px-4 py-2 rounded-full mb-6 border border-white/10">
                    <span class="relative flex h-2 w-2">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-terracotta opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-terracotta"></span>
                    </span>
                    {{ $campaigns->total() }} active fundraisers
                </div>
                <h1 class="text-4xl lg:text-5xl font-black text-white leading-tight tracking-tight">Explore Fundraisers</h1>
                <p class="mt-4 text-lg text-white/70 max-w-xl mx-auto">Discover campaigns and support causes that matter to you.</p>
            </div>

            {{-- Search & Filters --}}
            <div class="mt-10 max-w-4xl mx-auto">
                <form method="GET" action="{{ route('campaigns.index') }}" class="space-y-4">
                    <div class="flex flex-col sm:flex-row gap-3">
                        <div class="relative flex-1">
                            <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search campaigns..." class="w-full pl-12 pr-4 py-3.5 bg-white/10 backdrop-blur-md border border-white/15 rounded-xl text-white placeholder-white/50 focus:outline-none focus:ring-2 focus:ring-terracotta/50 focus:border-terracotta/50 transition">
                        </div>
                        <button type="submit" class="px-6 py-3.5 bg-terracotta text-gray-900 font-bold rounded-full hover:bg-terracotta-dark transition shadow-lg">
                            Search
                        </button>
                    </div>

                    <div class="flex flex-wrap items-center gap-2">
                        <span class="text-white/60 text-sm font-medium mr-1">Categories:</span>
                        <a href="{{ route('campaigns.index', array_merge(request()->except('category', 'page'), ['category' => ''])) }}"
                           class="filter-btn px-3.5 py-1.5 rounded-full text-sm font-medium border border-white/20 text-white/70 hover:bg-white/10 {{ !request('category') ? 'active' : '' }}">
                            All
                        </a>
                        @foreach($categories as $category)
                            <a href="{{ route('campaigns.index', array_merge(request()->except('category', 'page'), ['category' => $category->id])) }}"
                               class="filter-btn px-3.5 py-1.5 rounded-full text-sm font-medium border border-white/20 text-white/70 hover:bg-white/10 {{ request('category') == $category->id ? 'active' : '' }}">
                                {{ $category->name }}
                            </a>
                        @endforeach
                    </div>

                    <div class="flex items-center gap-2">
                        <span class="text-white/60 text-sm font-medium mr-1">Sort by:</span>
                        <select name="sort" onchange="this.form.submit()" class="bg-white/10 backdrop-blur-md border border-white/15 rounded-lg text-white text-sm px-3 py-1.5 focus:outline-none focus:ring-2 focus:ring-terracotta/50">
                            <option value="newest" {{ request('sort', 'newest') == 'newest' ? 'selected' : '' }} class="text-gray-900">Newest</option>
                            <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }} class="text-gray-900">Oldest</option>
                            <option value="most_raised" {{ request('sort') == 'most_raised' ? 'selected' : '' }} class="text-gray-900">Most Raised</option>
                            <option value="expiring" {{ request('sort') == 'expiring' ? 'selected' : '' }} class="text-gray-900">Expiring Soon</option>
                        </select>
                    </div>
                </form>
            </div>
        </div>
        <div class="absolute bottom-0 left-0 right-0 h-16 bg-gray-50/10"></div>
    </section>

    {{-- Campaigns Grid --}}
    <section class="py-12 lg:py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @if(request()->has('search') || request()->has('category'))
                <div class="mb-8 reveal">
                    <a href="{{ route('campaigns.index') }}" class="inline-flex items-center gap-2 text-sm text-gray-500 hover:text-maroon-dark font-medium transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16l-4-4m0 0l4-4m-4 4h18" />
                        </svg>
                        Clear all filters
                    </a>
                </div>
            @endif

            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6 lg:gap-8">
                @forelse($campaigns as $i => $campaign)
                    <div class="campaign-card bg-white rounded-2xl border border-gray-100 shadow-sm hover:shadow-2xl hover:shadow-navy-darker/10 overflow-hidden group reveal" style="transition-delay: {{ $i * 0.05 }}s">
                        <div class="h-52 bg-gray-100 relative overflow-hidden">
                            @if($campaign->getFirstMediaUrl('cover_image'))
                                <img src="{{ $campaign->getFirstMediaUrl('cover_image') }}" alt="{{ $campaign->title }}" class="w-full h-full object-cover group-hover:scale-110 transition duration-700">
                            @else
                                <div class="flex items-center justify-center h-full bg-gray-100">
                                    <svg class="w-16 h-16 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                            @endif
                            @if($campaign->category)
                                <span class="absolute top-4 left-4 bg-white/90 backdrop-blur-md text-gray-700 text-xs font-bold px-3 py-1.5 rounded-full shadow-sm border border-white/50">{{ $campaign->category->name }}</span>
                            @endif
                            <div class="absolute inset-0 bg-black/20 opacity-0 group-hover:opacity-100 transition duration-500"></div>
                        </div>
                        <div class="p-5 lg:p-6">
                            <a href="{{ route('campaigns.show', $campaign) }}">
                                <h3 class="text-lg font-bold text-gray-900 group-hover:text-maroon-dark transition line-clamp-1">{{ $campaign->title }}</h3>
                            </a>
                            <p class="text-sm text-gray-400 mt-1.5">by {{ $campaign->user->name }}</p>
                            <div class="mt-5">
                                <div class="flex justify-between text-sm mb-2">
                                    <span class="font-bold text-gray-900">KES {{ number_format($campaign->raised_amount) }}</span>
                                    <span class="text-gray-400">{{ $campaign->progressPercent() }}%</span>
                                </div>
                                <div class="bg-gray-100 rounded-full h-3 overflow-hidden">
                                    <div class="progress-fill bg-maroon rounded-full h-3 shadow-inner" style="width: 0%"></div>
                                </div>
                                <p class="text-xs text-gray-400 mt-2">of KES {{ number_format($campaign->target_amount) }} goal</p>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full text-center py-16">
                        <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                        </svg>
                        <p class="text-xl font-semibold text-gray-500">No fundraisers found</p>
                        <p class="text-gray-400 mt-2">Try adjusting your search or filters.</p>
                        <a href="{{ route('register') }}" class="inline-flex items-center mt-6 px-6 py-3 bg-maroon text-white font-semibold rounded-full hover:bg-maroon-dark transition shadow-lg">
                            Start a Fundraiser
                            <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                            </svg>
                        </a>
                    </div>
                @endforelse
            </div>

            @if($campaigns->hasPages())
                <div class="mt-12 reveal">
                    <div class="overflow-x-auto -mx-4 px-4">
                        {{ $campaigns->onEachSide(1)->links() }}
                    </div>
                </div>
            @endif
        </div>
    </section>

    @include('partials.footer')

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const revealObserver = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('visible');
                        const progressBar = entry.target.querySelector('.progress-fill');
                        if (progressBar && progressBar.style.width === '0%') {
                            setTimeout(() => {
                                progressBar.style.width = progressBar.dataset.width || '0%';
                            }, 200);
                        }
                    }
                });
            }, { threshold: 0.1, rootMargin: '0px 0px -50px 0px' });

            document.querySelectorAll('.reveal').forEach(el => {
                const progressBar = el.querySelector('.progress-fill');
                if (progressBar) {
                    progressBar.dataset.width = progressBar.style.width;
                    progressBar.style.width = '0%';
                }
                revealObserver.observe(el);
            });

            const navbar = document.getElementById('navbar');
            window.addEventListener('scroll', () => {
                if (window.pageYOffset > 50) {
                    navbar.classList.add('nav-scrolled');
                    navbar.style.backgroundColor = 'rgba(255,255,255,0.95)';
                } else {
                    navbar.classList.remove('nav-scrolled');
                    navbar.style.backgroundColor = 'rgba(255,255,255,0.85)';
                }
            });
        });
    </script>
</body>
</html>
