<x-dashboard-layout title="Feedback">
    <x-slot name="header">Feedback</x-slot>

    <div class="max-w-2xl">
        <div class="bg-white border border-gray-200 overflow-hidden">
            <div class="px-5 py-4" style="background-color: #1B2A4A;">
                <h3 class="font-semibold text-white text-sm">Rate Your Experience</h3>
            </div>
            <form method="POST" action="{{ route('feedbacks.store') }}" class="p-5 space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Rating</label>
                    <div class="flex items-center gap-1" x-data="{ rating: 0 }">
                        <template x-for="star in 5" :key="star">
                            <button type="button" @click="rating = star"
                                    class="p-0.5 transition">
                                <svg class="w-8 h-8 cursor-pointer"
                                     :class="star <= rating ? 'text-yellow-400' : 'text-gray-200'"
                                     fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                            </button>
                        </template>
                        <input type="hidden" name="rating" x-model="rating">
                    </div>
                    @error('rating')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Comment (optional)</label>
                    <textarea name="comment" rows="3" placeholder="Tell us what you think..."
                              class="w-full border border-gray-300 px-3 py-2 text-sm focus:border-[#CE5F26] focus:ring-1 focus:ring-[#CE5F26] outline-none resize-none"></textarea>
                </div>
                <button type="submit"
                        class="px-5 py-2 text-sm font-semibold text-white transition" style="background-color: #CE5F26;">
                    Submit Feedback
                </button>
            </form>
        </div>
    </div>
</x-dashboard-layout>
