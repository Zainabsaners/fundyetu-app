<footer class="bg-gray-950 text-gray-400">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 lg:py-20">
        <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-8 lg:gap-12">
            <div>
                <div class="flex items-center gap-2.5 mb-5">
                    <img src="{{ asset('assets/images/logo/logo-white.png') }}" alt="Support Sphere" class="h-8 lg:h-9 w-auto">
                </div>
                <p class="leading-relaxed">Africa's trusted community fundraising platform. Raise money for what matters most.</p>
            </div>

            <div>
                <h4 class="text-white font-bold text-sm uppercase tracking-wider mb-6">Quick Links</h4>
                <ul class="space-y-4">
                    <li><a href="/#trending" class="hover:text-white transition flex items-center gap-2"><span class="w-1 h-1 bg-[#CE5F26] rounded-full"></span>Explore Fundraisers</a></li>
                    <li><a href="#how-it-works" class="hover:text-white transition flex items-center gap-2"><span class="w-1 h-1 bg-[#CE5F26] rounded-full"></span>How It Works</a></li>
                    <li><a href="#features" class="hover:text-white transition flex items-center gap-2"><span class="w-1 h-1 bg-[#CE5F26] rounded-full"></span>Features</a></li>
                    @auth
                        <li><a href="{{ route('campaigns.create') }}" class="hover:text-white transition flex items-center gap-2"><span class="w-1 h-1 bg-[#CE5F26] rounded-full"></span>Start a Fundraiser</a></li>
                    @else
                        <li><a href="{{ route('register') }}" class="hover:text-white transition flex items-center gap-2"><span class="w-1 h-1 bg-[#CE5F26] rounded-full"></span>Start a Fundraiser</a></li>
                    @endauth
                </ul>
            </div>

            <div>
                <h4 class="text-white font-bold text-sm uppercase tracking-wider mb-6">Support</h4>
                <ul class="space-y-4">
                    <li><a href="#" class="hover:text-white transition flex items-center gap-2"><span class="w-1 h-1 bg-[#CE5F26] rounded-full"></span>FAQ</a></li>
                    <li><a href="#" class="hover:text-white transition flex items-center gap-2"><span class="w-1 h-1 bg-[#CE5F26] rounded-full"></span>Contact Us</a></li>
                    <li><a href="{{ route('privacy') }}" class="hover:text-white transition flex items-center gap-2"><span class="w-1 h-1 bg-[#CE5F26] rounded-full"></span>Privacy Policy</a></li>
                    <li><a href="#" class="hover:text-white transition flex items-center gap-2"><span class="w-1 h-1 bg-[#CE5F26] rounded-full"></span>Terms of Service</a></li>
                </ul>
            </div>

            <div>
                <h4 class="text-white font-bold text-sm uppercase tracking-wider mb-6">Contact</h4>
                <ul class="space-y-5">
                    <li class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-[#CE5F26] rounded-xl flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                            </svg>
                        </div>
                        <span>+254 732447447</span>
                    </li>
                    <li class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-[#CE5F26] rounded-xl flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <span>info@supportsphere.co.ke</span>
                    </li>
                    <li class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-[#CE5F26] rounded-xl flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </div>
                        <span>Nairobi, Kenya</span>
                    </li>
                </ul>
            </div>
        </div>

        <div class="mt-16 pt-8 border-t border-gray-800/60 flex flex-col lg:flex-row justify-between items-center gap-6">
            <p class="text-sm text-gray-600">&copy; {{ date('Y') }} {{ config('app.name', 'Support Sphere') }}. All rights reserved.</p>
            <div class="flex items-center gap-3">
                <a href="#" class="w-10 h-10 bg-gray-800 rounded-xl flex items-center justify-center hover:bg-gray-700 transition-all text-gray-400 hover:text-white">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                </a>
                <a href="#" class="w-10 h-10 bg-gray-800 rounded-xl flex items-center justify-center hover:bg-gray-700 transition-all text-gray-400 hover:text-white">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/></svg>
                </a>
                <a href="#" class="w-10 h-10 bg-gray-800 rounded-xl flex items-center justify-center hover:bg-gray-700 transition-all text-gray-400 hover:text-white">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 0C5.373 0 0 5.373 0 12s5.373 12 12 12 12-5.373 12-12S18.627 0 12 0zm-2 16h-2v-6h2v6zm-1-6.891c-.607 0-1.1-.496-1.1-1.109 0-.612.492-1.109 1.1-1.109s1.1.497 1.1 1.109c0 .613-.493 1.109-1.1 1.109zM16 16h-2v-3c0-1.071-.786-2-1.875-2S11 11.929 11 13v3H9v-6h2v1.042c.421-.632 1.188-1.042 2-1.042 1.375 0 2.5 1.214 2.5 2.708V16z"/></svg>
                </a>
            </div>
        </div>
    </div>
</footer>