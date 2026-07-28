<x-dashboard-layout title="Start a Fundraiser">
    <x-slot name="header">Start a Fundraiser</x-slot>

    <div class="max-w-3xl mx-auto" x-data="campaignWizard()">
        {{-- Progress Stepper --}}
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <template x-for="(step, index) in steps" :key="index">
                    <div class="flex items-center" :class="{ 'w-full': index < steps.length - 1 }">
                        <div class="flex items-center gap-2.5">
                            <div class="w-9 h-9 rounded-full flex items-center justify-center text-sm font-bold transition-all duration-300"
                                :class="{
                                    'bg-[#CE5F26] text-white': currentStep >= index,
                                    'bg-gray-100 text-gray-400': currentStep < index
                                }"
                                x-text="index + 1">
                            </div>
                            <span class="text-sm font-medium"
                                :class="{
                                    'text-[#CE5F26]': currentStep >= index,
                                    'text-gray-400': currentStep < index
                                }"
                                x-text="step">
                            </span>
                        </div>
                        <div class="flex-1 h-0.5 mx-3 transition-all duration-300"
                            :class="{ 'bg-[#CE5F26]': currentStep > index, 'bg-gray-100': currentStep <= index }">
                        </div>
                    </div>
                </template>
            </div>
        </div>

        <div class="bg-white border border-gray-200 overflow-hidden">
            <div class="p-6 sm:p-8">
                <form action="{{ route('campaigns.store') }}" method="POST" enctype="multipart/form-data" x-ref="form">
                    @csrf
                    <input type="hidden" name="_step" x-model="currentStep">

                    {{-- Step 1: Basic Info --}}
                    <div x-show="currentStep === 0" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0">
                        <h3 class="text-lg font-bold text-gray-900 mb-6">Basic Information</h3>

                        <div class="mb-5">
                            <label for="title" class="block text-sm font-medium text-gray-700 mb-1.5">Campaign Title</label>
                            <input type="text" name="title" id="title" x-model="form.title" placeholder="e.g. Help James undergo surgery"
                                   class="w-full border border-gray-200 px-4 py-3 text-sm focus:border-[#CE5F26] focus:ring-1 focus:ring-[#CE5F26] outline-none">
                            @error('title') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-5">
                            <div>
                                <label for="category_id" class="block text-sm font-medium text-gray-700 mb-1.5">Category</label>
                                <select name="category_id" id="category_id" x-model="form.category_id"
                                        class="w-full border border-gray-200 px-4 py-3 text-sm focus:border-[#CE5F26] focus:ring-1 focus:ring-[#CE5F26] outline-none">
                                    <option value="">Select category</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                                @error('category_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label for="target_amount" class="block text-sm font-medium text-gray-700 mb-1.5">Target Amount (KES)</label>
                                <input type="number" name="target_amount" id="target_amount" x-model="form.target_amount"
                                       placeholder="e.g. 500000"
                                       class="w-full border border-gray-200 px-4 py-3 text-sm focus:border-[#CE5F26] focus:ring-1 focus:ring-[#CE5F26] outline-none" min="100">
                                @error('target_amount') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-5">
                            <div>
                                <label for="location" class="block text-sm font-medium text-gray-700 mb-1.5">Country</label>
                                <select name="location" id="location" x-model="form.location"
                                        class="w-full border border-gray-200 px-4 py-3 text-sm focus:border-[#CE5F26] focus:ring-1 focus:ring-[#CE5F26] outline-none">
                                    <option value="">Select country</option>
                                    <option value="Kenya">Kenya</option>
                                </select>
                                @error('location') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label for="expiry_date" class="block text-sm font-medium text-gray-700 mb-1.5">End Date <span class="text-gray-400 font-normal">(optional)</span></label>
                                <input type="date" name="expiry_date" id="expiry_date" x-model="form.expiry_date"
                                       class="w-full border border-gray-200 px-4 py-3 text-sm focus:border-[#CE5F26] focus:ring-1 focus:ring-[#CE5F26] outline-none">
                                @error('expiry_date') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                        </div>
                    </div>

                    {{-- Step 2: Media --}}
                    <div x-show="currentStep === 1" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0">
                        <h3 class="text-lg font-bold text-gray-900 mb-6">Media</h3>

                        <div class="mb-5">
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Gallery Images <span class="text-gray-400 font-normal">(optional)</span></label>
                            <div class="border-2 border-dashed border-gray-200 p-6 text-center hover:border-[#CE5F26] transition cursor-pointer" onclick="document.getElementById('gallery_images').click()">
                                <svg class="w-10 h-10 mx-auto text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                                <p class="text-sm text-gray-500">Click to upload gallery images</p>
                                <p class="text-xs text-gray-400 mt-1">You can select multiple images</p>
                                <input type="file" name="gallery_images[]" id="gallery_images" accept="image/jpg,image/jpeg,image/png,image/webp" class="hidden" multiple onchange="previewGallery(this)">
                            </div>
                            <div id="gallery-preview" class="mt-3 grid grid-cols-3 sm:grid-cols-4 gap-2"></div>
                            @error('gallery_images.*') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div class="mb-5">
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Upload Video <span class="text-gray-400 font-normal">(optional, max 50MB)</span></label>
                            <div class="border-2 border-dashed border-gray-200 p-6 text-center hover:border-[#CE5F26] transition cursor-pointer" onclick="document.getElementById('video_file').click()">
                                <svg class="w-10 h-10 mx-auto text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                                <p class="text-sm text-gray-500">Click to upload a video file</p>
                                <p class="text-xs text-gray-400 mt-1">MP4, WebM or OGG. Max 50MB</p>
                                <input type="file" name="video_file" id="video_file" accept="video/mp4,video/webm,video/ogg" class="hidden" onchange="document.getElementById('video-file-name').textContent = this.files[0]?.name || ''; document.getElementById('video-file-info').classList.remove('hidden')">
                            </div>
                            <div id="video-file-info" class="hidden mt-2 flex items-center gap-2 text-sm text-gray-600">
                                <svg class="w-4 h-4 text-[#7AA36A]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                <span id="video-file-name"></span>
                            </div>
                            @error('video_file') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div class="mb-5">
                            <label for="video_url" class="block text-sm font-medium text-gray-700 mb-1.5">Or add a YouTube URL <span class="text-gray-400 font-normal">(optional)</span></label>
                            <input type="url" name="video_url" id="video_url" x-model="form.video_url" placeholder="https://www.youtube.com/watch?v=..."
                                   class="w-full border border-gray-200 px-4 py-3 text-sm focus:border-[#CE5F26] focus:ring-1 focus:ring-[#CE5F26] outline-none">
                            @error('video_url') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    {{-- Step 3: Story --}}
                    <div x-show="currentStep === 2" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0">
                        <h3 class="text-lg font-bold text-gray-900 mb-6">Your Story</h3>

                        <div class="mb-5">
                            <label for="story" class="block text-sm font-medium text-gray-700 mb-1.5">Description</label>
                            <textarea name="story" id="story" rows="10" x-model="form.story"
                                      class="w-full border border-gray-200 px-4 py-3 text-sm focus:border-[#CE5F26] focus:ring-1 focus:ring-[#CE5F26] outline-none"
                                      placeholder="Tell people why you're raising funds, how the money will be used, and any other important details..."></textarea>
                            @error('story') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    {{-- Step 4: Treasurer --}}
                    <div x-show="currentStep === 3" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0">
                        <h3 class="text-lg font-bold text-gray-900 mb-6">Treasurer</h3>

                        <div class="mb-5">
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="is_treasurer_controlled" id="is_treasurer_controlled" value="1"
                                       class="sr-only peer" x-model="form.is_treasurer_controlled">
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-[#CE5F26]/30 rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#CE5F26]"></div>
                                <span class="ms-3 text-sm font-medium text-gray-700">This fundraiser is controlled by a treasurer</span>
                            </label>
                        </div>

                        <div x-show="form.is_treasurer_controlled" class="space-y-4 pl-2 border-l-2 border-[#CE5F26]/30 ml-1">
                            <div>
                                <label for="treasurer_name" class="block text-sm font-medium text-gray-700 mb-1.5">Treasurer Name</label>
                                <input type="text" name="treasurer_name" id="treasurer_name" x-model="form.treasurer_name" placeholder="Full name of treasurer"
                                       class="w-full border border-gray-200 px-4 py-3 text-sm focus:border-[#CE5F26] focus:ring-1 focus:ring-[#CE5F26] outline-none">
                                @error('treasurer_name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label for="treasurer_phone" class="block text-sm font-medium text-gray-700 mb-1.5">Phone Number</label>
                                    <input type="text" name="treasurer_phone" id="treasurer_phone" x-model="form.treasurer_phone" placeholder="e.g. 0712345678"
                                           class="w-full border border-gray-200 px-4 py-3 text-sm focus:border-[#CE5F26] focus:ring-1 focus:ring-[#CE5F26] outline-none">
                                    @error('treasurer_phone') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label for="treasurer_id_number" class="block text-sm font-medium text-gray-700 mb-1.5">ID Number</label>
                                    <input type="text" name="treasurer_id_number" id="treasurer_id_number" x-model="form.treasurer_id_number" placeholder="National ID number"
                                           class="w-full border border-gray-200 px-4 py-3 text-sm focus:border-[#CE5F26] focus:ring-1 focus:ring-[#CE5F26] outline-none">
                                    @error('treasurer_id_number') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Step 5: Review --}}
                    <div x-show="currentStep === 4" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0">
                        <h3 class="text-lg font-bold text-gray-900 mb-6">Review & Submit</h3>

                        <div class="space-y-4">
                            <div class="flex items-center gap-2 p-4 bg-gray-50 border border-gray-100">
                                <span class="text-xs font-semibold text-gray-500 uppercase w-24 shrink-0">Title</span>
                                <span class="text-sm text-gray-800" x-text="form.title"></span>
                            </div>
                            <div class="flex items-center gap-2 p-4 bg-gray-50 border border-gray-100">
                                <span class="text-xs font-semibold text-gray-500 uppercase w-24 shrink-0">Category</span>
                                <span class="text-sm text-gray-800" x-text="form.category_id ? document.querySelector('#category_id option:checked')?.textContent : '—'"></span>
                            </div>
                            <div class="flex items-center gap-2 p-4 bg-gray-50 border border-gray-100">
                                <span class="text-xs font-semibold text-gray-500 uppercase w-24 shrink-0">Target</span>
                                <span class="text-sm text-gray-800" x-text="'KES ' + Number(form.target_amount).toLocaleString()"></span>
                            </div>
                            <div class="flex items-center gap-2 p-4 bg-gray-50 border border-gray-100" x-show="form.expiry_date">
                                <span class="text-xs font-semibold text-gray-500 uppercase w-24 shrink-0">End Date</span>
                                <span class="text-sm text-gray-800" x-text="form.expiry_date"></span>
                            </div>
                            <div class="flex items-center gap-2 p-4 bg-gray-50 border border-gray-100" x-show="form.video_url">
                                <span class="text-xs font-semibold text-gray-500 uppercase w-24 shrink-0">Video</span>
                                <span class="text-sm text-gray-800 truncate" x-text="form.video_url"></span>
                            </div>
                            <div class="flex items-start gap-2 p-4 bg-gray-50 border border-gray-100" x-show="form.story">
                                <span class="text-xs font-semibold text-gray-500 uppercase w-24 shrink-0 pt-0.5">Story</span>
                                <span class="text-sm text-gray-800 line-clamp-3" x-text="form.story"></span>
                            </div>
                            <div class="flex items-center gap-2 p-4 bg-gray-50 border border-gray-100" x-show="form.is_treasurer_controlled">
                                <span class="text-xs font-semibold text-gray-500 uppercase w-24 shrink-0">Treasurer</span>
                                <span class="text-sm text-gray-800" x-text="form.treasurer_name + ' (' + form.treasurer_phone + ')'"></span>
                            </div>
                        </div>

                        <div class="mt-6 flex items-center justify-end">
                            <button type="submit" class="px-8 py-3 bg-[#CE5F26] text-white font-semibold hover:bg-[#B04E1E] transition shadow-lg shadow-[#CE5F26]/30">
                                Create Fundraiser
                            </button>
                        </div>
                    </div>

                    {{-- Navigation Buttons --}}
                    <div class="mt-8 pt-6 border-t border-gray-100 flex items-center justify-between" x-show="currentStep < 4">
                        <div>
                            <button type="button" x-show="currentStep > 0" @click="prevStep" class="px-5 py-2.5 text-sm font-medium text-gray-600 hover:text-gray-900 border border-gray-200 hover:border-gray-300 transition">
                                Back
                            </button>
                        </div>
                        <div class="flex items-center gap-3">
                            <button type="submit" name="save_draft" value="1" class="px-5 py-2.5 text-sm font-medium text-gray-500 hover:text-gray-700 border border-gray-200 hover:border-gray-300 transition">
                                Save as Draft
                            </button>
                            <button type="button" @click="nextStep" class="px-6 py-2.5 bg-[#CE5F26] text-white text-sm font-semibold hover:bg-[#B04E1E] transition">
                                <span x-text="currentStep === 3 ? 'Review' : 'Continue'"></span>
                                <svg class="w-4 h-4 inline ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function campaignWizard() {
            return {
                currentStep: {{ (int) old('_step', 0) }},
                steps: ['Basic Info', 'Media', 'Story', 'Treasurer', 'Review'],
                form: {
                    title: @json(old('title', '')),
                    category_id: @json(old('category_id', '')),
                    target_amount: @json(old('target_amount', '')),
                    expiry_date: @json(old('expiry_date', '')),
                    location: @json(old('location', '')),
                    video_url: @json(old('video_url', '')),
                    story: @json(old('story', '')),
                    is_treasurer_controlled: {{ old('is_treasurer_controlled') ? 'true' : 'false' }},
                    treasurer_name: @json(old('treasurer_name', '')),
                    treasurer_phone: @json(old('treasurer_phone', '')),
                    treasurer_id_number: @json(old('treasurer_id_number', '')),
                },
                nextStep() {
                    if (this.currentStep < this.steps.length - 1) {
                        this.currentStep++;
                        window.scrollTo({ top: 0, behavior: 'smooth' });
                    }
                },
                prevStep() {
                    if (this.currentStep > 0) {
                        this.currentStep--;
                        window.scrollTo({ top: 0, behavior: 'smooth' });
                    }
                }
            }
        }

        function previewGallery(input) {
            const container = document.getElementById('gallery-preview');
            container.innerHTML = '';
            Array.from(input.files).forEach(file => {
                const img = document.createElement('img');
                img.src = window.URL.createObjectURL(file);
                img.className = 'rounded h-24 w-full object-cover border border-gray-100';
                container.appendChild(img);
            });
        }
    </script>
</x-dashboard-layout>
