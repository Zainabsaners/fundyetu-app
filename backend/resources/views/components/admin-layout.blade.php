<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@isset($title) {{ $title }} | @endisset Admin - {{ config('app.name', 'Support Sphere') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800,900&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        [x-cloak] { display: none !important; }
        .sidebar-scrollbar::-webkit-scrollbar { width: 4px; }
        .sidebar-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .sidebar-scrollbar::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.1); border-radius: 2px; }
    </style>
</head>
<body class="font-sans antialiased text-gray-900 bg-gray-50">

    <div x-data="{ sidebarOpen: window.innerWidth >= 1024 }" class="flex h-screen overflow-hidden">
        {{-- Sidebar Overlay (mobile) --}}
        <div x-show="sidebarOpen" x-cloak x-transition.opacity
             class="fixed inset-0 z-30 bg-black/50 lg:hidden"
             @click="sidebarOpen = false"></div>

        {{-- Sidebar --}}
        <aside class="fixed inset-y-0 left-0 z-40 w-64 flex flex-col transition-transform duration-300 lg:static lg:translate-x-0" style="background-color: #1b2a4a;"
               :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'">

            {{-- Logo --}}
            <div class="flex items-center justify-between h-16 px-6 border-b border-white/10 shrink-0">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2.5">
                    <img src="{{ asset('assets/images/logo/logo-white.png') }}" alt="Support Sphere" class="h-7 w-auto">
                    <span class="text-white font-bold text-sm">Admin</span>
                </a>
                <button @click="sidebarOpen = false" class="lg:hidden text-white/60 hover:text-white transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            {{-- Navigation --}}
            <nav class="flex-1 overflow-y-auto py-4 px-3 sidebar-scrollbar">
                <div class="space-y-1">
                    <p class="px-3 text-xs font-semibold text-white/40 uppercase tracking-wider mb-2">Main</p>
                    <a href="{{ route('admin.dashboard') }}"
                       class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition
                              {{ request()->routeIs('admin.dashboard') ? 'bg-white/10 text-white' : 'text-white/60 hover:text-white hover:bg-white/5' }}">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                        </svg>
                        Dashboard
                    </a>
                    @php $pendingCampaigns = \App\Models\Campaign::where('status', \App\Enums\CampaignStatus::PendingVerification)->count(); @endphp
                    <a href="{{ route('admin.campaigns.index') }}"
                       class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition
                              {{ request()->routeIs('admin.campaigns.*') ? 'bg-white/10 text-white' : 'text-white/60 hover:text-white hover:bg-white/5' }}">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                        </svg>
                        Campaigns
                        @if($pendingCampaigns > 0)
                            <span class="ml-auto bg-red-500 text-white text-[10px] font-bold px-1.5 py-0.5 min-w-[18px] text-center leading-tight">{{ $pendingCampaigns }}</span>
                        @endif
                    </a>
                    <a href="{{ route('admin.users.index') }}"
                       class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition
                              {{ request()->routeIs('admin.users.*') ? 'bg-white/10 text-white' : 'text-white/60 hover:text-white hover:bg-white/5' }}">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z"/>
                        </svg>
                        Users
                    </a>
                    <a href="{{ route('admin.donors.index') }}"
                       class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition
                              {{ request()->routeIs('admin.donors.*') ? 'bg-white/10 text-white' : 'text-white/60 hover:text-white hover:bg-white/5' }}">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                        Donors
                    </a>
                    <a href="{{ route('admin.donations.index') }}"
                       class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition
                              {{ request()->routeIs('admin.donations.*') ? 'bg-white/10 text-white' : 'text-white/60 hover:text-white hover:bg-white/5' }}">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Donations
                    </a>
                    @php $pendingWithdrawals = \App\Models\Withdrawal::whereIn('status', ['pending', 'treasurer_approved', 'admin_approved'])->count(); @endphp
                    <a href="{{ route('admin.withdrawals.index') }}"
                       class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition
                              {{ request()->routeIs('admin.withdrawals.*') ? 'bg-white/10 text-white' : 'text-white/60 hover:text-white hover:bg-white/5' }}">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                        Withdrawals
                        @if($pendingWithdrawals > 0)
                            <span class="ml-auto bg-red-500 text-white text-[10px] font-bold px-1.5 py-0.5 min-w-[18px] text-center leading-tight">{{ $pendingWithdrawals }}</span>
                        @endif
                    </a>
                </div>

                <div class="mt-8 space-y-1">
                    <p class="px-3 text-xs font-semibold text-white/40 uppercase tracking-wider mb-2">Community</p>
                    <a href="{{ route('admin.feedbacks.index') }}"
                       class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition
                              {{ request()->routeIs('admin.feedbacks.*') ? 'bg-white/10 text-white' : 'text-white/60 hover:text-white hover:bg-white/5' }}">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                        </svg>
                        Feedback
                    </a>
                </div>

                <div class="mt-8 space-y-1">
                    <p class="px-3 text-xs font-semibold text-white/40 uppercase tracking-wider mb-2">System</p>
                    <a href="{{ route('admin.settings.index') }}"
                       class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition
                              {{ request()->routeIs('admin.settings.*') ? 'bg-white/10 text-white' : 'text-white/60 hover:text-white hover:bg-white/5' }}">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.573-1.066z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        Settings
                    </a>
                </div>

                <div class="mt-8 space-y-1">
                    <p class="px-3 text-xs font-semibold text-white/40 uppercase tracking-wider mb-2">Support</p>
                    <a href="{{ route('admin.tickets.index') }}"
                       class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition
                              {{ request()->routeIs('admin.tickets.*') ? 'bg-white/10 text-white' : 'text-white/60 hover:text-white hover:bg-white/5' }}">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                        </svg>
                        Tickets
                    </a>
                </div>

                <div class="mt-8 space-y-1">
                    <p class="px-3 text-xs font-semibold text-white/40 uppercase tracking-wider mb-2">Others</p>
                    <a href="{{ route('admin.reports') }}"
                       class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition
                              {{ request()->routeIs('admin.reports') ? 'bg-white/10 text-white' : 'text-white/60 hover:text-white hover:bg-white/5' }}">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                        Platform Earnings
                    </a>
                </div>
            </nav>

            {{-- User Footer --}}
            <div class="border-t border-white/10 p-4 shrink-0">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-full bg-[#CE5F26] flex items-center justify-center text-white font-bold text-sm shrink-0">
                        {{ substr(Auth::user()->name, 0, 1) }}
                    </div>
                    <div class="min-w-0 flex-1">
                        <p class="text-sm font-medium text-white truncate">{{ Auth::user()->name }}</p>
                        <p class="text-xs text-white/50 truncate">{{ Auth::user()->email }}</p>
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-white/40 hover:text-white transition" title="Log out">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        {{-- Main Content --}}
        <div class="flex-1 flex flex-col min-w-0 overflow-y-auto">
            {{-- Top Bar --}}
            <header class="bg-white border-b border-gray-200 h-16 flex items-center px-4 lg:px-6 shrink-0 sticky top-0 z-20">
                <button @click="sidebarOpen = !sidebarOpen" class="mr-4 text-gray-500 hover:text-gray-700 transition lg:mr-6">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
                <div class="flex-1 flex items-center justify-between">
                    <h1 class="text-lg font-bold text-gray-800">{{ $header ?? 'Dashboard' }}</h1>
                    <div class="flex items-center gap-4">
                        <div x-data="{ open: false }" class="relative">
                            <button @click="open = !open; if(open) fetch('{{ route('admin.notifications.read') }}', { method: 'POST', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' } })" class="relative text-gray-500 hover:text-[#CE5F26] transition">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                                </svg>
                                @if(Auth::user()->unreadNotifications->count())
                                    <span class="absolute -top-1 -right-1 w-4 h-4 bg-[#CE5F26] text-white text-[9px] font-bold flex items-center justify-center">{{ Auth::user()->unreadNotifications->count() }}</span>
                                @endif
                            </button>
                            <div x-show="open" @click.outside="open = false" x-cloak
                                 class="absolute right-0 mt-2 w-80 bg-white border border-gray-200 shadow-lg z-50">
                                <div class="px-4 py-3 border-b border-gray-100">
                                    <p class="text-sm font-semibold text-gray-800">Notifications</p>
                                </div>
                                <div class="max-h-64 overflow-y-auto divide-y divide-gray-50">
                                    @forelse(Auth::user()->notifications->take(10) as $notification)
                                        <div class="px-4 py-3 text-sm {{ $notification->read_at ? 'text-gray-500' : 'bg-blue-50/30 text-gray-800 font-medium' }}">
                                            <p>{{ $notification->data['message'] ?? 'Notification' }}</p>
                                            <p class="text-xs text-gray-400 mt-0.5">{{ $notification->created_at->diffForHumans() }}</p>
                                        </div>
                                    @empty
                                        <div class="px-4 py-8 text-center text-sm text-gray-400">No notifications</div>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                        <a href="{{ route('home') }}" class="text-sm text-gray-500 hover:text-[#CE5F26] transition flex items-center gap-1.5">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                            </svg>
                            View Site
                        </a>
                    </div>
                </div>
            </header>

            {{-- Page Content --}}
            <main class="flex-1 p-4 lg:p-6">
                {{ $slot }}
            </main>

            {{-- Footer --}}
            <footer class="border-t border-gray-200 px-4 lg:px-6 py-4 shrink-0">
                <p class="text-xs text-gray-400">&copy; {{ date('Y') }} {{ config('app.name', 'Support Sphere') }}. All rights reserved.</p>
            </footer>
        </div>
    </div>

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
</body>
</html>
