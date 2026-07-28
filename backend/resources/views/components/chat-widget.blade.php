<div x-data="{ open: false }" class="fixed bottom-6 right-6 z-50">
    {{-- Chat Button --}}
    <button @click="open = !open"
            class="w-14 h-14 bg-[#CE5F26] hover:bg-[#b85422] text-white shadow-lg flex items-center justify-center transition"
            :class="open ? 'rounded-full rotate-45' : 'rounded-full'">
        <svg x-show="!open" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
        </svg>
        <svg x-show="open" x-cloak class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
        </svg>
    </button>

    {{-- Chat Modal --}}
    <div x-show="open" x-cloak @click.outside="open = false"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 scale-95 translate-y-4"
         x-transition:enter-end="opacity-100 scale-100 translate-y-0"
         class="absolute bottom-16 right-0 w-80 sm:w-96 bg-white border border-gray-200 shadow-xl overflow-hidden">

        {{-- Header --}}
        <div class="px-4 py-3" style="background-color: #1B2A4A;">
            <h3 class="font-semibold text-white text-sm">Need Help?</h3>
            <p class="text-xs text-white/70 mt-0.5">Send us a message and we'll respond shortly.</p>
        </div>

        {{-- Form --}}
        <form method="POST" action="{{ route('tickets.store') }}" enctype="multipart/form-data" class="p-4 space-y-3">
            @csrf
            <div>
                <input type="text" name="subject" placeholder="Subject" required
                       class="w-full border border-gray-300 px-3 py-2 text-sm focus:border-[#CE5F26] focus:ring-1 focus:ring-[#CE5F26] outline-none">
            </div>
            <div>
                <textarea name="message" rows="4" placeholder="Tell us what you need..." required
                          class="w-full border border-gray-300 px-3 py-2 text-sm focus:border-[#CE5F26] focus:ring-1 focus:ring-[#CE5F26] outline-none resize-none"></textarea>
            </div>
            <div>
                <label class="flex items-center gap-2 text-sm text-gray-500 cursor-pointer hover:text-[#CE5F26] transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/>
                    </svg>
                    <span x-text="$refs.fileInput.files.length ? $refs.fileInput.files[0].name : 'Attach screenshot'"></span>
                    <input type="file" name="attachment" accept="image/*,.pdf" x-ref="fileInput"
                           class="hidden" @change="$el.form.dispatchEvent(new Event('change'))">
                </label>
            </div>
            <button type="submit"
                    class="w-full py-2 text-sm font-semibold text-white transition" style="background-color: #CE5F26;">
                Send Message
            </button>
        </form>
    </div>
</div>
