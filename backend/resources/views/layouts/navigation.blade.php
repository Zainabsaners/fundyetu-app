<nav x-data="{ open: false }" id="navbar" class="fixed top-0 left-0 right-0 z-50 nav-blur transition-all duration-300">
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
                    @if($isAdmin)
                        <a href="{{ route('admin.dashboard') }}" class="text-gray-600 hover:text-maroon-dark font-medium transition {{ request()->routeIs('admin.dashboard') ? 'text-maroon' : '' }}">Dashboard</a>
                    @else
                        <a href="{{ route('dashboard') }}" class="text-gray-600 hover:text-maroon-dark font-medium transition {{ request()->routeIs('dashboard') ? 'text-maroon' : '' }}">Dashboard</a>
                    @endif
                    @if($isAdmin)
                        <a href="{{ route('admin.dashboard') }}" class="text-gray-600 hover:text-maroon-dark font-medium transition {{ request()->routeIs('admin.*') ? 'text-maroon' : '' }}">Admin</a>
                    @endif
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

            <button type="button" class="md:hidden p-2 rounded-full text-gray-500 hover:text-gray-700 hover:bg-gray-100 transition" @click="open = ! open">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    </div>

    <div :class="{'block': open, 'hidden': ! open}" class="hidden md:hidden border-t border-gray-100 bg-white/95 backdrop-blur-lg">
        <div class="px-4 py-4 space-y-2">
            <a href="/#trending" class="block px-4 py-3 rounded-xl bg-maroon/[0.03] text-maroon font-medium">Explore</a>
            <a href="{{ route('home') }}#how-it-works" class="block px-4 py-3 rounded-xl text-gray-600 hover:bg-maroon/[0.03] hover:text-maroon-dark font-medium transition">How It Works</a>
            <a href="{{ route('home') }}#features" class="block px-4 py-3 rounded-xl text-gray-600 hover:bg-maroon/[0.03] hover:text-maroon-dark font-medium transition">Features</a>
            @auth
                @php $isAdmin = auth()->user()->roles->pluck('name')->intersect(['admin', 'super_admin'])->isNotEmpty(); @endphp
                @if($isAdmin)
                    <a href="{{ route('admin.dashboard') }}" class="block px-4 py-3 rounded-xl text-gray-600 hover:bg-maroon/[0.03] hover:text-maroon-dark font-medium transition">Dashboard</a>
                @else
                    <a href="{{ route('dashboard') }}" class="block px-4 py-3 rounded-xl text-gray-600 hover:bg-maroon/[0.03] hover:text-maroon-dark font-medium transition">Dashboard</a>
                @endif
                @if($isAdmin)
                    <a href="{{ route('admin.dashboard') }}" class="block px-4 py-3 rounded-xl text-gray-600 hover:bg-maroon/[0.03] hover:text-maroon-dark font-medium transition">Admin</a>
                @endif
                <a href="{{ route('campaigns.create') }}" class="block px-4 py-3 text-center bg-[#CE5F26] text-white font-semibold rounded-full hover:bg-[#B04E1E] transition">Start a Fundraiser</a>
            @else
                <a href="{{ route('login') }}" class="block px-4 py-3 rounded-xl text-gray-600 hover:bg-maroon/[0.03] hover:text-maroon-dark font-medium transition">Log in</a>
                <a href="{{ route('register') }}" class="block px-4 py-3 text-center bg-[#CE5F26] text-white font-semibold rounded-full hover:bg-[#B04E1E] transition">Start a Fundraiser</a>
            @endauth
        </div>

        @auth
            <div class="border-t border-gray-100 px-4 py-4">
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-10 h-10 rounded-full bg-maroon/[0.08] flex items-center justify-center text-maroon font-semibold">
                        {{ substr(Auth::user()->name, 0, 1) }}
                    </div>
                    <div>
                        <div class="font-medium text-sm text-gray-800">{{ Auth::user()->name }}</div>
                        <div class="text-sm text-gray-500">{{ Auth::user()->email }}</div>
                    </div>
                </div>
                <div class="space-y-1">
                    <a href="{{ route('profile.edit') }}" class="block px-4 py-3 rounded-xl text-gray-600 hover:bg-maroon/[0.03] hover:text-maroon-dark font-medium transition">Profile</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <a href="{{ route('logout') }}" class="block px-4 py-3 rounded-xl text-gray-600 hover:bg-maroon/[0.03] hover:text-maroon-dark font-medium transition"
                           onclick="event.preventDefault(); this.closest('form').submit();">Log Out</a>
                    </form>
                </div>
            </div>
        @endauth
    </div>
</nav>
