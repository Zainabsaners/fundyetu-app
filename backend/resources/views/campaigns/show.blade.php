<x-app-layout>
    {{-- Hero Banner --}}
    <div class="relative overflow-hidden">
        @if($campaign->getFirstMediaUrl('cover_image'))
            <div class="absolute inset-0">
                <img src="{{ $campaign->getFirstMediaUrl('cover_image') }}" alt="{{ $campaign->title }}" class="w-full h-full object-cover">
            </div>
            <div class="absolute inset-0 bg-[#1b2a4a]/40"></div>
        @else
            <div class="absolute inset-0 bg-[#1b2a4a]"></div>
        @endif

        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 lg:py-16">
            <div class="flex flex-col lg:flex-row lg:items-end lg:justify-between gap-8">
                <div class="max-w-2xl">
                    <h1 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold text-white leading-tight mb-4">{{ $campaign->title }}</h1>
                    <div class="flex flex-wrap items-center gap-x-4 gap-y-2 text-white/80 text-sm">
                        @if($campaign->isVerified())
                            <div class="relative group">
                                <span class="inline-flex items-center gap-1.5 text-green-300">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 1L3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-4zm-2 16l-4-4 1.41-1.41L10 14.17l6.59-6.59L18 9l-8 8z"/></svg>
                                    Verified
                                </span>
                                <div class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 px-3 py-1.5 bg-gray-900 text-white text-xs rounded-lg opacity-0 group-hover:opacity-100 transition pointer-events-none whitespace-nowrap shadow-lg">
                                    This fundraiser has been verified by Support Sphere
                                    <div class="absolute top-full left-1/2 -translate-x-1/2 border-4 border-transparent border-t-gray-900"></div>
                                </div>
                            </div>
                        @endif
                        @if($campaign->expiry_date)
                            @php
                                $expiry = \Carbon\Carbon::parse($campaign->expiry_date);
                                $daysLeft = (int) now()->diffInDays($expiry, false);
                            @endphp
                            <span class="flex items-center gap-1.5">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                @if($daysLeft > 0)
                                    <span class="{{ $daysLeft <= 7 ? 'text-red-400 font-semibold' : '' }}">{{ $daysLeft }} {{ Str::plural('day', $daysLeft) }} left</span>
                                @elseif($daysLeft === 0)
                                    <span class="text-yellow-400 font-semibold">Ends today</span>
                                @else
                                    <span class="text-gray-500">Ended {{ abs($daysLeft) }} {{ Str::plural('day', abs($daysLeft)) }} ago</span>
                                @endif
                            </span>
                        @endif
                        <span class="flex items-center gap-1.5">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" /></svg>
                            {{ $campaign->donorCount() }} Donors
                        </span>
                        <div class="relative group">
                            <span class="flex items-center gap-1.5">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                Treasurer Controlled: <span class="font-semibold {{ $campaign->is_treasurer_controlled ? 'text-green-300' : 'text-white/50' }}">{{ $campaign->is_treasurer_controlled ? 'Yes' : 'No' }}</span>
                            </span>
                            <div class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 px-3 py-1.5 bg-gray-900 text-white text-xs rounded-lg opacity-0 group-hover:opacity-100 transition pointer-events-none whitespace-nowrap shadow-lg">
                                @if($campaign->is_treasurer_controlled) Funds are managed by a designated treasurer @else Funds go directly to the campaign owner @endif
                                <div class="absolute top-full left-1/2 -translate-x-1/2 border-4 border-transparent border-t-gray-900"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="w-full lg:w-80 bg-white/10 backdrop-blur-xl p-6 border border-white/10 shrink-0">
                    <div class="mb-4">
                        <div class="flex justify-between items-end mb-2">
                            <span class="text-2xl font-bold text-white">KES {{ number_format($campaign->raised_amount, 0) }}</span>
                            <span class="text-white/70 text-sm">{{ $campaign->progressPercent() }}%</span>
                        </div>
                        <div class="h-2.5 bg-white/20 rounded-full overflow-hidden">
                            <div class="h-full bg-[#CE5F26] rounded-full transition-all duration-1000" style="width: {{ $campaign->progressPercent() }}%"></div>
                        </div>
                        <p class="text-white/60 text-sm mt-1">raised of KES {{ number_format($campaign->target_amount, 0) }}</p>
                    </div>
                    @if($campaign->isActive())
                        <a href="#donation-section" class="block w-full text-center px-6 py-3 bg-[#CE5F26] text-white font-semibold rounded-xl hover:bg-[#B04E1E] transition-all shadow-lg shadow-[#CE5F26]/30">
                            Donate Now
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Image Carousel --}}
    @php $galleryImages = $campaign->getMedia('gallery'); @endphp
    @if($galleryImages->count() > 0)
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 -mt-10 relative z-20">
        <div x-data="carousel({{ $galleryImages->count() }})" class="relative rounded-2xl overflow-hidden bg-gray-100 shadow-lg">
            <div class="aspect-[16/9] sm:aspect-[21/9] relative overflow-hidden">
                <template x-for="(image, index) in images" :key="index">
                    <img :src="image" :alt="'Image ' + (index + 1)"
                         class="absolute inset-0 w-full h-full object-cover transition-all duration-500 ease-in-out"
                         :class="index === current ? 'opacity-100 scale-100' : 'opacity-0 scale-105'">
                </template>
                <div class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent pointer-events-none"></div>
            </div>

            <div x-show="images.length > 1" class="contents">
                <button @click="prev()" class="absolute left-3 top-1/2 -translate-y-1/2 w-10 h-10 rounded-full bg-white/90 hover:bg-white shadow-lg flex items-center justify-center transition-all hover:scale-105 z-10">
                    <svg class="w-5 h-5 text-gray-800" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                </button>
                <button @click="next()" class="absolute right-3 top-1/2 -translate-y-1/2 w-10 h-10 rounded-full bg-white/90 hover:bg-white shadow-lg flex items-center justify-center transition-all hover:scale-105 z-10">
                    <svg class="w-5 h-5 text-gray-800" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </button>
                <div class="absolute bottom-4 left-1/2 -translate-x-1/2 flex gap-2 z-10">
                    <template x-for="(image, index) in images" :key="index">
                        <button @click="go(index)" class="w-2 h-2 rounded-full transition-all duration-300"
                                :class="index === current ? 'bg-white w-6' : 'bg-white/50 hover:bg-white/70'"></button>
                    </template>
                </div>
            </div>
        </div>

        <script>
            function carousel(count) {
                return {
                    images: [ @foreach($galleryImages as $media) '{{ $media->getUrl() }}', @endforeach ],
                    current: 0,
                    interval: null,
                    init() { if (count > 1) this.interval = setInterval(() => this.next(), 5000); },
                    next() { this.current = (this.current + 1) % count; },
                    prev() { this.current = (this.current - 1 + count) % count; },
                    go(i) { this.current = i; },
                    destroy() { if (this.interval) clearInterval(this.interval); }
                }
            }
        </script>
    </div>
    @endif

    {{-- Donate & Share Buttons + Progress + Stats --}}
    <div class="bg-[#F5F3EE] mt-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            {{-- Action Buttons --}}
            <div class="flex items-center justify-center gap-4 mb-8">
                @if($campaign->isActive())
                    <a href="#donation-section" class="px-10 py-3.5 bg-[#D4A017] text-gray-900 font-bold rounded-full hover:bg-[#C09015] transition-all shadow-lg shadow-[#D4A017]/30 text-sm tracking-wide">
                        DONATE
                    </a>
                @endif
                <button onclick="shareCampaign()" class="px-10 py-3.5 border-2 border-[#D4A017] text-gray-800 font-bold rounded-full hover:bg-[#D4A017]/5 transition-all text-sm tracking-wide">
                    SHARE
                </button>
            </div>

            {{-- Progress Bar --}}
            @php $pct = $campaign->progressPercent(); @endphp
            <div class="mb-6">
                <div class="relative w-full h-4 bg-gray-200 rounded-full overflow-hidden">
                    <div class="h-full bg-[#7AA36A] rounded-full transition-all duration-1000" style="width: {{ $pct }}%"></div>
                    <div class="absolute top-1/2 -translate-y-1/2 w-4 h-4 bg-[#D4A017] rounded-full shadow-md border-2 border-white" style="left: calc({{ $pct }}% - 8px)"></div>
                </div>
                <div class="flex justify-between items-start mt-2">
                    <div class="text-sm">
                        <span class="font-bold text-gray-900">KES {{ number_format($campaign->raised_amount, 0) }}</span>
                        <span class="text-gray-500"> funds raised of </span>
                        <span class="font-bold text-gray-900">KES {{ number_format($campaign->target_amount, 0) }} goal</span>
                    </div>
                    <span class="text-sm font-bold text-gray-800">{{ $pct }}%</span>
                </div>
            </div>

            {{-- Stats Row --}}
            <div class="grid grid-cols-3 gap-4 text-center">
                <div>
                    <div class="text-2xl sm:text-3xl font-bold text-[#7AA36A]">KES {{ number_format($campaign->raised_amount, 0) }}</div>
                    <div class="text-xs sm:text-sm text-gray-500 mt-0.5">Funds Raised</div>
                </div>
                <div>
                    <div class="text-2xl sm:text-3xl font-bold text-[#7AA36A]">{{ $campaign->donorCount() }}</div>
                    <div class="text-xs sm:text-sm text-gray-500 mt-0.5">Donors</div>
                </div>
                <div>
                    <div class="text-2xl sm:text-3xl font-bold {{ $campaign->expiry_date ? (\Carbon\Carbon::parse($campaign->expiry_date)->isPast() ? 'text-gray-400' : 'text-[#7AA36A]') : 'text-gray-200' }}">
                        @if($campaign->expiry_date)
                            @php $daysLeft = (int) now()->diffInDays(\Carbon\Carbon::parse($campaign->expiry_date), false); @endphp
                            {{ $daysLeft > 0 ? $daysLeft : 0 }}
                        @else
                            0
                        @endif
                    </div>
                    <div class="text-xs sm:text-sm text-gray-500 mt-0.5">Days Left</div>
                </div>
            </div>
        </div>
    </div>

    {{-- Tabs: Description, Video, Gallery, Comments --}}
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 pb-16">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden -mt-6 relative z-10">
            <div x-data="{ tab: 'description' }">
                {{-- Tab Headers --}}
                <div class="border-b border-gray-100">
                    <div class="flex overflow-x-auto">
                        <button @click="tab = 'description'" :class="tab === 'description' ? 'border-[#D4A017] text-[#D4A017]' : 'border-transparent text-gray-500 hover:text-gray-700'" class="px-6 py-4 text-sm font-semibold border-b-2 transition whitespace-nowrap">Description</button>
                        <button @click="tab = 'video'" :class="tab === 'video' ? 'border-[#D4A017] text-[#D4A017]' : 'border-transparent text-gray-500 hover:text-gray-700'" class="px-6 py-4 text-sm font-semibold border-b-2 transition whitespace-nowrap">Video</button>
                        <button @click="tab = 'gallery'" :class="tab === 'gallery' ? 'border-[#D4A017] text-[#D4A017]' : 'border-transparent text-gray-500 hover:text-gray-700'" class="px-6 py-4 text-sm font-semibold border-b-2 transition whitespace-nowrap">Gallery</button>
                        <button @click="tab = 'comments'" :class="tab === 'comments' ? 'border-[#D4A017] text-[#D4A017]' : 'border-transparent text-gray-500 hover:text-gray-700'" class="px-6 py-4 text-sm font-semibold border-b-2 transition whitespace-nowrap">Comments ({{ $campaign->comments->count() }})</button>
                    </div>
                </div>

                {{-- Description Tab --}}
                <div x-show="tab === 'description'" x-cloak class="p-6 sm:p-8 pt-10 sm:pt-12">
                    <div class="prose prose-lg max-w-none">
                        {!! nl2br(e($campaign->story)) !!}
                    </div>



                    @if($campaign->patrons->count())
                        <div class="mt-8 pt-6 border-t border-gray-100">
                            <h3 class="text-lg font-bold text-gray-900 mb-6">Patrons</h3>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                @foreach($campaign->patrons as $patron)
                                    <div class="flex items-center gap-3 bg-gray-50 p-4 rounded-xl">
                                        @if($patron->photo) <img src="{{ $patron->photo }}" alt="" class="w-11 h-11 rounded-full object-cover">
                                        @else <div class="w-11 h-11 rounded-full bg-[#CE5F26]/10 flex items-center justify-center text-[#CE5F26] font-semibold text-sm">{{ substr($patron->name, 0, 1) }}</div>
                                        @endif
                                        <div>
                                            <p class="font-semibold text-gray-800">{{ $patron->name }}</p>
                                            @if($patron->message) <p class="text-sm text-gray-500">{{ $patron->message }}</p> @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif


                </div>

                {{-- Video Tab --}}
                <div x-show="tab === 'video'" x-cloak class="p-6 sm:p-8">
                    @php $videoFile = $campaign->getFirstMedia('video'); @endphp
                    @if($videoFile)
                        <div class="aspect-video rounded-xl overflow-hidden shadow-sm bg-black">
                            <video class="w-full h-full" controls>
                                <source src="{{ $videoFile->getUrl() }}" type="{{ $videoFile->mime_type }}">
                                Your browser does not support the video tag.
                            </video>
                        </div>
                    @elseif($campaign->video_url)
                        <div class="aspect-video rounded-xl overflow-hidden shadow-sm">
                            <iframe src="{{ $campaign->video_url }}" class="w-full h-full" frameborder="0" allowfullscreen></iframe>
                        </div>
                    @else
                        <div class="text-center py-16 text-gray-400">
                            <svg class="w-16 h-16 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                            <p class="text-lg font-medium">No video available</p>
                            <p class="text-sm mt-1">The fundraiser owner hasn't added a video yet.</p>
                        </div>
                    @endif
                </div>

                {{-- Gallery Tab --}}
                <div x-show="tab === 'gallery'" x-cloak class="p-6 sm:p-8">
                    @php $galleryImages = $campaign->getMedia('gallery'); @endphp
                    @if($galleryImages->count() > 0)
                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
                            @foreach($galleryImages as $media)
                                <a href="{{ $media->getUrl() }}" target="_blank" class="block rounded-xl overflow-hidden group">
                                    <img src="{{ $media->getUrl() }}" class="w-full h-48 object-cover group-hover:scale-105 transition duration-500">
                                </a>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-16 text-gray-400">
                            <svg class="w-16 h-16 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            <p class="text-lg font-medium">No gallery images</p>
                            <p class="text-sm mt-1">The fundraiser owner hasn't added any gallery images yet.</p>
                        </div>
                    @endif
                </div>

                {{-- Comments Tab --}}
                <div x-show="tab === 'comments'" x-cloak class="p-6 sm:p-8">
                    @auth
                        <form action="{{ route('campaigns.comment', $campaign) }}" method="POST" class="mb-8">
                            @csrf
                            <label for="comment-body" class="block text-sm font-medium text-gray-700 mb-2">Leave a comment</label>
                            <textarea name="body" id="comment-body" rows="3" required
                                      class="w-full rounded-xl border-gray-200 shadow-sm focus:border-[#D4A017] focus:ring-[#D4A017] px-4 py-3 resize-none"
                                      placeholder="Share your thoughts on this fundraiser..."></textarea>
                            @error('body') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            <div class="flex justify-end mt-3">
                                <button type="submit" class="px-6 py-2.5 bg-[#D4A017] text-white font-semibold rounded-full hover:bg-[#C09015] transition text-sm">
                                    Post Comment
                                </button>
                            </div>
                        </form>
                    @else
                        <div class="mb-8 p-4 bg-gray-50 rounded-xl text-center text-sm text-gray-500">
                            <a href="{{ route('login') }}" class="text-[#D4A017] font-semibold hover:underline">Log in</a> to leave a comment.
                        </div>
                    @endauth

                    <div class="space-y-5">
                        @forelse($campaign->comments->where('parent_id', null) as $comment)
                            <div class="bg-gray-50 rounded-xl p-5">
                                <div class="flex items-start gap-3">
                                    <div class="w-9 h-9 rounded-full bg-[#D4A017]/10 flex items-center justify-center text-[#D4A017] font-semibold text-sm shrink-0">{{ substr($comment->user->name ?? 'A', 0, 1) }}</div>
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center gap-2 mb-1">
                                            <span class="font-semibold text-sm text-gray-800">{{ $comment->user->name ?? 'Anonymous' }}</span>
                                            <span class="text-xs text-gray-400">{{ $comment->created_at->diffForHumans() }}</span>
                                        </div>
                                        <p class="text-sm text-gray-700">{{ $comment->body }}</p>

                                        @if($comment->replies->count())
                                            <div class="mt-3 space-y-3 pl-4 border-l-2 border-gray-200">
                                                @foreach($comment->replies as $reply)
                                                    <div class="flex items-start gap-2">
                                                        <div class="w-7 h-7 rounded-full bg-gray-200 flex items-center justify-center text-gray-600 font-semibold text-xs shrink-0">{{ substr($reply->user->name ?? 'A', 0, 1) }}</div>
                                                        <div>
                                                            <div class="flex items-center gap-2">
                                                                <span class="font-semibold text-xs text-gray-700">{{ $reply->user->name ?? 'Anonymous' }}</span>
                                                                <span class="text-xs text-gray-400">{{ $reply->created_at->diffForHumans() }}</span>
                                                            </div>
                                                            <p class="text-sm text-gray-600">{{ $reply->body }}</p>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @endif

                                        @auth
                                            <button onclick="document.getElementById('reply-form-{{ $comment->id }}').classList.toggle('hidden')" class="text-xs text-[#D4A017] font-semibold mt-2 hover:underline">Reply</button>
                                            <form id="reply-form-{{ $comment->id }}" action="{{ route('campaigns.comment', $campaign) }}" method="POST" class="hidden mt-3">
                                                @csrf
                                                <input type="hidden" name="parent_id" value="{{ $comment->id }}">
                                                <div class="flex gap-2">
                                                    <input type="text" name="body" placeholder="Write a reply..." required
                                                           class="flex-1 rounded-lg border-gray-200 text-sm focus:border-[#D4A017] focus:ring-[#D4A017] px-3 py-2">
                                                    <button type="submit" class="px-4 py-2 bg-[#D4A017] text-white text-sm font-semibold rounded-lg hover:bg-[#C09015] transition">Reply</button>
                                                </div>
                                            </form>
                                        @endauth
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-12 text-gray-400">
                                <svg class="w-12 h-12 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                                <p class="text-lg font-medium">No comments yet</p>
                                <p class="text-sm mt-1">Be the first to share your thoughts.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Donation Method Selector --}}
    @if($campaign->isActive())
    <div id="donation-section" class="bg-[#F0EFEB]" x-data="{
        method: 'mpesa',
        amount: 200,
        showModal: false,
        phone: '',
        name: '',
        anonymous: false,
        message: '',
        loading: false,
        stkSent: false,
        error: '',
        donationId: null,
        pollTimer: null,
        submitDonation() {
            this.loading = true; this.error = '';
            fetch('{{ route('donations.store', $campaign) }}', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                body: JSON.stringify({ amount: this.amount, payment_method: 'mpesa', donor_phone: this.phone, donor_name: this.name, message: this.message })
            })
            .then(r => r.json().then(data => ({ ok: r.ok, data })))
            .then(({ ok, data }) => {
                if (!ok) { throw new Error(data.error || (data.errors ? Object.values(data.errors).flat()[0] : data.message) || 'Payment request failed'); }
                this.donationId = data.donation_id;
                this.loading = false;
                this.stkSent = true;
                this.pollTimer = setInterval(() => {
                    fetch('/donations/' + this.donationId + '/status')
                        .then(r => r.json())
                        .then(d => { if (d.status === 'completed') { clearInterval(this.pollTimer); window.location.href = '/donations/' + this.donationId + '/thank-you'; } })
                        .catch(() => {});
                }, 5000);
            })
            .catch(err => { this.loading = false; this.error = err.message; });
        }
    }">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
            <div class="rounded-lg overflow-hidden">
                <div class="bg-[#7AA36A] flex items-center justify-center" style="height: 50px;">
                    <span class="text-white font-bold text-base">Select donation method</span>
                </div>

                <div class="flex flex-col md:flex-row">
                {{-- Left: Payment tabs --}}
                <div class="w-full md:w-64 shrink-0">
                    @if($enabledMethods['mpesa'])
                    <button @click="method = 'mpesa'" :class="method === 'mpesa' ? 'bg-[#D4A017]/15 border-l-[5px] border-[#D4A017] shadow-md font-bold text-[#7AA36A]' : 'bg-white hover:bg-gray-50 text-gray-500'" class="w-full flex items-center gap-3 px-4 py-3.5 text-sm border-b border-gray-100 transition">
                        <img src="{{ asset('assets/images/providers/mpesa.png') }}" alt="M-Pesa" class="w-8 h-8 object-contain">
                        <span>M-Pesa</span>
                    </button>
                    @endif
                    @if($enabledMethods['airtel'])
                    <button @click="method = 'airtel'" :class="method === 'airtel' ? 'bg-[#D4A017]/15 border-l-[5px] border-[#D4A017] shadow-md font-bold text-[#7AA36A]' : 'bg-white hover:bg-gray-50 text-gray-500'" class="w-full flex items-center gap-3 px-4 py-3.5 text-sm border-b border-gray-100 transition">
                        <img src="{{ asset('assets/images/providers/airtel.png') }}" alt="Airtel" class="w-8 h-8 object-contain">
                        <span>Airtel Money</span>
                    </button>
                    @endif
                    @if($enabledMethods['card'])
                    <button @click="method = 'card'" :class="method === 'card' ? 'bg-[#D4A017]/15 border-l-[5px] border-[#D4A017] shadow-md font-bold text-[#7AA36A]' : 'bg-white hover:bg-gray-50 text-gray-500'" class="w-full flex items-center gap-3 px-4 py-3.5 text-sm border-b border-gray-100 transition">
                        <span class="w-8 h-8 flex items-center justify-center"><svg viewBox="0 0 48 32" class="w-8 h-5"><rect x="1" y="4" width="46" height="24" rx="3" fill="#1A1F71"/><rect x="6" y="12" width="16" height="10" rx="1.5" fill="white"/><rect x="26" y="12" width="16" height="10" rx="1.5" fill="white"/><circle cx="24" cy="17" r="6" fill="#F7941E"/></svg></span>
                        <span>VISA / MasterCard</span>
                    </button>
                    @endif
                    @if($enabledMethods['paypal'])
                    <button @click="method = 'paypal'" :class="method === 'paypal' ? 'bg-[#D4A017]/15 border-l-[5px] border-[#D4A017] shadow-md font-bold text-[#7AA36A]' : 'bg-white hover:bg-gray-50 text-gray-500'" class="w-full flex items-center gap-3 px-4 py-3.5 text-sm border-b border-gray-100 transition">
                        <span class="w-8 h-8 flex items-center justify-center"><svg viewBox="0 0 36 36" class="w-8 h-8"><path d="M28.5 6H12.4c-.9 0-1.7.7-1.9 1.6L7 25.5c-.1.5.3 1 .8 1h4.6l1.1-7.1c.1-.8.8-1.4 1.7-1.4h3.1c4.1 0 7.3-1.7 8.2-6.6.4-1.8.2-3.3-.7-4.3-.7-.8-1.8-1.1-3.3-1.1z" fill="#003087"/><path d="M27.2 7.1c-.2-.1-.5-.2-.8-.3.3.1.5.2.8.3z" fill="#009FDA"/><path d="M28.5 6H12.4c-.9 0-1.7.7-1.9 1.6L7 25.5c-.1.5.3 1 .8 1h4.6l1.1-7c.1-.8.8-1.4 1.7-1.4h3.1c4.1 0 7.3-1.7 8.2-6.6.4-1.8.2-3.3-.7-4.3-.7-.8-1.8-1.1-3.3-1.1z" fill="#009FDA"/><path d="M27.2 7.1c0-.1-.1-.1-.1-.1 0 0 0 .1.1.1z" fill="#003087"/></svg></span>
                        <span>PayPal</span>
                    </button>
                    @endif
                </div>

                {{-- Right: Payment form --}}
                <div class="flex-1 bg-white p-6 sm:p-8">
                    {{-- M-Pesa Form --}}
                    @if($enabledMethods['mpesa'])
                    <div x-show="method === 'mpesa'" x-cloak>
                        <div class="flex justify-center mb-6">
                            <img src="{{ asset('assets/images/providers/mpesa.png') }}" alt="M-Pesa" class="h-12 object-contain">
                        </div>

                        <div class="grid grid-cols-3 gap-3 mb-4">
                            <button @click="amount = 2300" :class="amount === 2300 ? 'bg-[#D4A017] text-gray-900 border-[#D4A017] font-bold' : 'bg-white text-gray-700 border-gray-200 hover:border-gray-300 font-medium'" class="w-full py-3 px-4 rounded-full text-sm border-2 text-center transition">2,300</button>
                            <button @click="amount = 2000" :class="amount === 2000 ? 'bg-[#D4A017] text-gray-900 border-[#D4A017] font-bold' : 'bg-white text-gray-700 border-gray-200 hover:border-gray-300 font-medium'" class="w-full py-3 px-4 rounded-full text-sm border-2 text-center transition">2,000</button>
                            <button @click="amount = 7500" :class="amount === 7500 ? 'bg-[#D4A017] text-gray-900 border-[#D4A017] font-bold' : 'bg-white text-gray-700 border-gray-200 hover:border-gray-300 font-medium'" class="w-full py-3 px-4 rounded-full text-sm border-2 text-center transition">7,500</button>
                        </div>

                        <div class="mb-5">
                            <label class="block text-xs text-gray-500 mb-1 font-medium">Custom amount</label>
                            <div class="flex items-center bg-gray-100 rounded-xl">
                                <span class="pl-4 pr-2 text-sm font-semibold text-gray-500">KES</span>
                                <input type="number" x-model="amount" class="w-full bg-transparent px-2 py-3 text-sm text-gray-800 font-bold border-0 focus:outline-none focus:ring-0" placeholder="200">
                            </div>
                        </div>

                        <div class="flex justify-center mb-6">
                            <button type="button" @click="showModal = true" class="px-10 py-3 bg-[#CE5F26] text-white font-bold rounded-full text-sm hover:bg-[#B04E1E] transition">DONATE NOW</button>
                        </div>


                    </div>

                    {{-- Donation Modal --}}
                    <div x-show="showModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4">
                        <div class="fixed inset-0 bg-black/60" @click="if (pollTimer) clearInterval(pollTimer); showModal = false; loading = false; stkSent = false; error = ''; donationId = null"></div>
                        <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-lg max-h-[90vh] overflow-y-auto" @click.stop>
                            <button @click="if (pollTimer) clearInterval(pollTimer); showModal = false; loading = false; stkSent = false; error = ''; donationId = null" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 transition">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>

                            {{-- Form --}}
                            <div x-show="!loading && !stkSent && !error" class="p-6 sm:p-8">
                                <div class="text-center mb-6">
                                    <h3 class="text-lg font-bold text-gray-900">{{ $campaign->title }}</h3>
                                    <p class="text-2xl font-bold text-[#7AA36A] mt-1">KES <span x-text="amount.toLocaleString()">200</span> M-Pesa Donation</p>
                                </div>

                                <div class="bg-[#F0EFEB] rounded-xl p-4 mb-6 space-y-1 text-sm text-gray-600">
                                    <p>Fill the form below, Click Donate</p>
                                    <p>Enter M-Pesa PIN when prompted on your phone</p>
                                    <p>You'll receive receipts from M-Pesa, and Support Sphere</p>
                                </div>

                                <form @submit.prevent="submitDonation" class="space-y-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">M-Pesa Phone Number</label>
                                        <input type="tel" name="donor_phone" x-model="phone" placeholder="Enter a valid M-Pesa Phone Number" required
                                               class="w-full rounded-xl border-gray-200 bg-gray-50 px-4 py-3 text-sm focus:border-[#D4A017] focus:ring-[#D4A017]">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Display Name</label>
                                        <input type="text" name="donor_name" x-model="name" placeholder="Enter display name"
                                               class="w-full rounded-xl border-gray-200 bg-gray-50 px-4 py-3 text-sm focus:border-[#D4A017] focus:ring-[#D4A017]">
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <input type="checkbox" x-model="anonymous" id="anonymous-modal" class="rounded border-gray-300 text-[#D4A017] focus:ring-[#D4A017]">
                                        <label for="anonymous-modal" class="text-sm text-gray-600">Anonymous</label>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Goodwill Message</label>
                                        <textarea name="message" x-model="message" rows="3" placeholder="Leave a goodwill message..."
                                                  class="w-full rounded-xl border-gray-200 bg-gray-50 px-4 py-3 text-sm focus:border-[#D4A017] focus:ring-[#D4A017] resize-none"></textarea>
                                    </div>

                                    <button type="submit" :disabled="!phone"
                                            class="w-full py-3.5 bg-[#CE5F26] text-white font-bold rounded-full text-sm hover:bg-[#B04E1E] transition disabled:opacity-50 disabled:cursor-not-allowed">
                                        DONATE KES <span x-text="amount.toLocaleString()">200</span>
                                    </button>
                                </form>
                            </div>

                            {{-- Loading state --}}
                            <div x-show="loading" class="p-6 sm:p-8 text-center">
                                <div class="animate-spin rounded-full h-12 w-12 border-4 border-[#D4A017] border-t-transparent mx-auto mb-4"></div>
                                <h3 class="text-lg font-bold text-gray-900 mb-2">Sending STK Push...</h3>
                                <p class="text-sm text-gray-600">Please wait while we send the M-Pesa prompt to your phone.</p>
                            </div>

                            {{-- STK sent state --}}
                            <div x-show="stkSent" class="p-6 sm:p-8 text-center">
                                <div class="mx-auto w-16 h-16 rounded-full flex items-center justify-center mb-4">
                                    <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                </div>
                                <h3 class="text-lg font-bold text-gray-900 mb-2">STK Push Sent!</h3>
                                <p class="text-sm text-gray-600 mb-4">Check your phone for the M-Pesa PIN prompt and enter your PIN to complete payment.</p>
                                <div class="animate-pulse flex justify-center gap-2 mb-2">
                                    <div class="w-2 h-2 bg-[#D4A017] rounded-full"></div>
                                    <div class="w-2 h-2 bg-[#D4A017] rounded-full" style="animation-delay: 0.3s"></div>
                                    <div class="w-2 h-2 bg-[#D4A017] rounded-full" style="animation-delay: 0.6s"></div>
                                </div>
                                <p class="text-xs text-gray-400">Waiting for payment confirmation...</p>
                            </div>

                            {{-- Error state --}}
                            <div x-show="error" class="p-6 sm:p-8 text-center">
                                <div class="mx-auto w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mb-4">
                                    <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                </div>
                                <h3 class="text-lg font-bold text-gray-900 mb-2">Payment Error</h3>
                                <p class="text-sm text-gray-600 mb-4" x-text="error"></p>
                                <button @click="error = ''" class="px-6 py-2.5 bg-[#CE5F26] text-white font-bold rounded-full text-sm hover:bg-[#B04E1E] transition">
                                    Try Again
                                </button>
                            </div>
                        </div>
                    </div>

                    @endif
                    {{-- Airtel Form --}}
                    @if($enabledMethods['airtel'])
                    <div x-show="method === 'airtel'" x-cloak>
                        <div class="flex justify-center mb-6">
                            <img src="{{ asset('assets/images/providers/airtel.png') }}" alt="Airtel" class="h-12 object-contain">
                        </div>
                        <div class="grid grid-cols-3 gap-3 mb-4">
                            <button @click="amount = 2300" :class="amount === 2300 ? 'bg-[#D4A017] text-gray-900 border-[#D4A017] font-bold' : 'bg-white text-gray-700 border-gray-200 hover:border-gray-300 font-medium'" class="w-full py-3 px-4 rounded-full text-sm border-2 text-center transition">2,300</button>
                            <button @click="amount = 2000" :class="amount === 2000 ? 'bg-[#D4A017] text-gray-900 border-[#D4A017] font-bold' : 'bg-white text-gray-700 border-gray-200 hover:border-gray-300 font-medium'" class="w-full py-3 px-4 rounded-full text-sm border-2 text-center transition">2,000</button>
                            <button @click="amount = 7500" :class="amount === 7500 ? 'bg-[#D4A017] text-gray-900 border-[#D4A017] font-bold' : 'bg-white text-gray-700 border-gray-200 hover:border-gray-300 font-medium'" class="w-full py-3 px-4 rounded-full text-sm border-2 text-center transition">7,500</button>
                        </div>
                        <div class="mb-5">
                            <label class="block text-xs text-gray-500 mb-1 font-medium">Custom amount</label>
                            <div class="flex items-center bg-gray-100 rounded-xl">
                                <span class="pl-4 pr-2 text-sm font-semibold text-gray-500">KES</span>
                                <input type="number" x-model="amount" class="w-full bg-transparent px-2 py-3 text-sm text-gray-800 font-bold border-0 focus:outline-none focus:ring-0" placeholder="200">
                            </div>
                        </div>
                        <div class="flex justify-center mb-6">
                            <button type="button" class="px-10 py-3 bg-[#CE5F26] text-white font-bold rounded-full text-sm hover:bg-[#B04E1E] transition">DONATE NOW</button>
                        </div>
                    </div>

                    @endif
                    {{-- Card Form --}}
                    @if($enabledMethods['card'])
                    <div x-show="method === 'card'" x-cloak>
                        <div class="flex justify-center mb-6">
                            <div class="flex items-center gap-3 bg-blue-600 text-white px-6 py-3 rounded-xl">
                                <span class="font-bold text-lg">CARD PAYMENT</span>
                            </div>
                        </div>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-xs text-gray-500 mb-1 font-medium">Card Number</label>
                                <input type="text" placeholder="4242 4242 4242 4242" class="w-full bg-gray-100 border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none">
                            </div>
                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-xs text-gray-500 mb-1 font-medium">Expiry</label>
                                    <input type="text" placeholder="MM/YY" class="w-full bg-gray-100 border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none">
                                </div>
                                <div>
                                    <label class="block text-xs text-gray-500 mb-1 font-medium">CVV</label>
                                    <input type="text" placeholder="123" class="w-full bg-gray-100 border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none">
                                </div>
                            </div>
                            <div>
                                <label class="block text-xs text-gray-500 mb-1 font-medium">Amount (KES)</label>
                                <input type="number" x-model="amount" class="w-full bg-gray-100 border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none">
                            </div>
                        </div>
                        <div class="flex justify-center mt-6">
                            <button type="button" class="px-10 py-3 bg-[#CE5F26] text-white font-bold rounded-full text-sm hover:bg-[#B04E1E] transition">DONATE NOW</button>
                        </div>
                    </div>

                    @endif
                    {{-- PayPal Form --}}
                    @if($enabledMethods['paypal'])
                    <div x-show="method === 'paypal'" x-cloak>
                        <div class="flex justify-center mb-6">
                            <svg viewBox="0 0 124 33" class="h-8"><path d="M28.5 6H12.4c-.9 0-1.7.7-1.9 1.6L7 25.5c-.1.5.3 1 .8 1h4.6l1.1-7.1c.1-.8.8-1.4 1.7-1.4h3.1c4.1 0 7.3-1.7 8.2-6.6.4-1.8.2-3.3-.7-4.3-.7-.8-1.8-1.1-3.3-1.1z" fill="#003087"/><path d="M28.5 6H12.4c-.9 0-1.7.7-1.9 1.6L7 25.5c-.1.5.3 1 .8 1h4.6l1.1-7c.1-.8.8-1.4 1.7-1.4h3.1c4.1 0 7.3-1.7 8.2-6.6.4-1.8.2-3.3-.7-4.3-.7-.8-1.8-1.1-3.3-1.1z" fill="#009FDA"/></svg>
                        </div>
                        <div class="text-center py-8 text-gray-500 text-sm">
                            <p>You will be redirected to PayPal to complete your donation.</p>
                        </div>
                        <div class="mb-5">
                            <label class="block text-xs text-gray-500 mb-1 font-medium">Amount (KES)</label>
                            <input type="number" x-model="amount" class="w-full bg-gray-100 border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none">
                        </div>
                        <div class="flex justify-center">
                            <button type="button" class="px-10 py-3 bg-[#CE5F26] text-white font-bold rounded-full text-sm hover:bg-[#B04E1E] transition">DONATE NOW</button>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @endif

    <style>
        [x-cloak] { display: none !important; }
        html { scroll-behavior: smooth; }
    </style>

    <script>
        function shareCampaign() {
            if (navigator.share) {
                navigator.share({ title: '{{ $campaign->title }}', url: window.location.href });
            } else {
                navigator.clipboard.writeText(window.location.href);
                alert('Link copied to clipboard!');
            }
        }
    </script>

    @include('partials.footer')
</x-app-layout>
