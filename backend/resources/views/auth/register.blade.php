<x-guest-layout>
    @section('title', 'Create Account - ' . config('app.name'))

    <div class="space-y-6">
        <div class="text-center">
            <h1 class="text-3xl font-black text-gray-900 tracking-tight">Start Your Fundraiser</h1>
            <p class="mt-2 text-gray-500">Create your account and launch your campaign in minutes.</p>
        </div>

        <style>
            .register-maroon { background-color: #CE5F26 !important; }
            .register-maroon-text { color: #CE5F26 !important; }
            .register-maroon-hover:hover { background-color: #B04E1E !important; }
            .register-maroon-ring:focus { --tw-ring-color: #CE5F26 !important; border-color: #CE5F26 !important; }
            .register-maroon-shadow { --tw-shadow-color: #CE5F26 !important; }
        </style>

        <form method="POST" action="{{ route('register') }}" class="space-y-5" id="register-form">
            @csrf

            {{-- Step Indicator --}}
            <div class="flex items-center gap-3 mb-2">
                <div class="flex items-center gap-2">
                    <span id="step1-indicator" class="w-8 h-8 rounded-full register-maroon text-white text-sm font-bold flex items-center justify-center">1</span>
                    <span id="step1-label" class="text-sm font-semibold register-maroon-text">Fundraiser Details</span>
                </div>
                <div class="flex-1 h-px bg-gray-200">
                    <div id="step-progress" class="h-px register-maroon w-0 transition-all duration-500"></div>
                </div>
                <div class="flex items-center gap-2">
                    <span id="step2-indicator" class="w-8 h-8 rounded-full bg-gray-200 text-gray-500 text-sm font-bold flex items-center justify-center">2</span>
                    <span id="step2-label" class="text-sm text-gray-400 font-medium">Personal Details</span>
                </div>
            </div>

            {{-- Step 1: Fundraiser Details --}}
            <div id="step-1" class="space-y-4">
                <div>
                    <label for="email" class="block text-sm font-semibold text-gray-700 mb-1.5">Email</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required
                        class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-white text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#CE5F26]/20 focus:border-[#CE5F26] transition @error('email') border-red-300 @enderror"
                        placeholder="you@example.com">
                    <x-input-error :messages="$errors->get('email')" class="mt-1.5" />
                </div>

                <div>
                    <label for="campaign_title" class="block text-sm font-semibold text-gray-700 mb-1.5">Fundraiser Title</label>
                    <input id="campaign_title" type="text" name="campaign_title" value="{{ old('campaign_title') }}" required
                        class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-white text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#CE5F26]/20 focus:border-[#CE5F26] transition @error('campaign_title') border-red-300 @enderror"
                        placeholder="e.g. Help Fund My Surgery">
                    <x-input-error :messages="$errors->get('campaign_title')" class="mt-1.5" />
                </div>

                <div>
                    <label for="category_id" class="block text-sm font-semibold text-gray-700 mb-1.5">Fundraiser Type</label>
                    <select id="category_id" name="category_id" required
                        class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-white text-gray-900 focus:outline-none focus:ring-2 focus:ring-[#CE5F26]/20 focus:border-[#CE5F26] transition @error('category_id') border-red-300 @enderror">
                        <option value="" disabled {{ old('category_id') ? '' : 'selected' }}>Select a category</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                        @endforeach
                    </select>
                    <x-input-error :messages="$errors->get('category_id')" class="mt-1.5" />
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label for="target_amount" class="block text-sm font-semibold text-gray-700 mb-1.5">Target Amount (Ksh)</label>
                        <input id="target_amount" type="number" name="target_amount" value="{{ old('target_amount') }}" required min="1" step="0.01"
                            class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-white text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#CE5F26]/20 focus:border-[#CE5F26] transition @error('target_amount') border-red-300 @enderror"
                            placeholder="e.g. 500000">
                        <x-input-error :messages="$errors->get('target_amount')" class="mt-1.5" />
                    </div>

                    <div>
                        <label for="duration" class="block text-sm font-semibold text-gray-700 mb-1.5">Duration (days)</label>
                        <select id="duration" name="duration" required
                            class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-white text-gray-900 focus:outline-none focus:ring-2 focus:ring-[#CE5F26]/20 focus:border-[#CE5F26] transition @error('duration') border-red-300 @enderror">
                            <option value="" disabled {{ old('duration') ? '' : 'selected' }}>Select</option>
                            <option value="7" {{ old('duration') == 7 ? 'selected' : '' }}>7 days</option>
                            <option value="14" {{ old('duration') == 14 ? 'selected' : '' }}>14 days</option>
                            <option value="30" {{ old('duration') == 30 ? 'selected' : '' }}>30 days</option>
                            <option value="60" {{ old('duration') == 60 ? 'selected' : '' }}>60 days</option>
                            <option value="90" {{ old('duration') == 90 ? 'selected' : '' }}>90 days</option>
                        </select>
                        <x-input-error :messages="$errors->get('duration')" class="mt-1.5" />
                    </div>
                </div>

                <button type="button" id="step1-next"
                    class="w-full py-3.5 register-maroon text-white font-bold rounded-full register-maroon-hover transition shadow-lg register-maroon-shadow">
                    Continue →
                </button>
            </div>

            {{-- Step 2: Personal Details --}}
            <div id="step-2" class="space-y-4 hidden">
                <div>
                    <label for="name" class="block text-sm font-semibold text-gray-700 mb-1.5">Full Name</label>
                    <input id="name" type="text" name="name" value="{{ old('name') }}" required
                        class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-white text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#CE5F26]/20 focus:border-[#CE5F26] transition @error('name') border-red-300 @enderror"
                        placeholder="e.g. John Kamau">
                    <x-input-error :messages="$errors->get('name')" class="mt-1.5" />
                </div>

                <div>
                    <label for="id_number" class="block text-sm font-semibold text-gray-700 mb-1.5">ID Number</label>
                    <input id="id_number" type="text" name="id_number" value="{{ old('id_number') }}" required
                        class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-white text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#CE5F26]/20 focus:border-[#CE5F26] transition @error('id_number') border-red-300 @enderror"
                        placeholder="e.g. 12345678">
                    <x-input-error :messages="$errors->get('id_number')" class="mt-1.5" />
                </div>

                <div>
                    <label for="phone" class="block text-sm font-semibold text-gray-700 mb-1.5">Mobile Number</label>
                    <input id="phone" type="text" name="phone" value="{{ old('phone') }}" required
                        class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-white text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#CE5F26]/20 focus:border-[#CE5F26] transition @error('phone') border-red-300 @enderror"
                        placeholder="e.g. 0712345678">
                    <x-input-error :messages="$errors->get('phone')" class="mt-1.5" />
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label for="password" class="block text-sm font-semibold text-gray-700 mb-1.5">Password</label>
                        <input id="password" type="password" name="password" required autocomplete="new-password"
                            class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-white text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#CE5F26]/20 focus:border-[#CE5F26] transition @error('password') border-red-300 @enderror"
                            placeholder="Create a strong password">
                        <x-input-error :messages="$errors->get('password')" class="mt-1.5" />
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-sm font-semibold text-gray-700 mb-1.5">Confirm Password</label>
                        <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password"
                            class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-white text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#CE5F26]/20 focus:border-[#CE5F26] transition"
                            placeholder="Repeat password">
                    </div>
                </div>

                <div>
                    <label for="birth_year" class="block text-sm font-semibold text-gray-700 mb-1.5">Birth Year</label>
                    <select id="birth_year" name="birth_year" required
                        class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-white text-gray-900 focus:outline-none focus:ring-2 focus:ring-[#CE5F26]/20 focus:border-[#CE5F26] transition @error('birth_year') border-red-300 @enderror">
                        <option value="" disabled {{ old('birth_year') ? '' : 'selected' }}>Select your birth year</option>
                        @for($year = date('Y') - 18; $year >= date('Y') - 100; $year--)
                            <option value="{{ $year }}" {{ old('birth_year') == $year ? 'selected' : '' }}>{{ $year }}</option>
                        @endfor
                    </select>
                    <x-input-error :messages="$errors->get('birth_year')" class="mt-1.5" />
                </div>

                <div class="flex items-center gap-3">
                    <button type="button" id="step2-back"
                        class="flex-1 py-3.5 bg-gray-100 text-gray-700 font-semibold rounded-full hover:bg-gray-200 transition">
                        ← Back
                    </button>
                    <button type="submit"
                        class="flex-[2] py-3.5 register-maroon text-white font-bold rounded-full register-maroon-hover transition shadow-lg register-maroon-shadow">
                        Create Account & Launch
                    </button>
                </div>
            </div>
        </form>

        <p class="text-center text-sm text-gray-500">
            Already have an account?
            <a href="{{ route('login') }}" class="font-semibold text-[#CE5F26] hover:text-navy-dark transition">Sign in</a>
        </p>

        <p class="text-center text-sm text-gray-500">
            <a href="{{ route('home') }}" class="text-gray-400 hover:text-gray-600 transition">&larr; Back to home</a>
        </p>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const step1 = document.getElementById('step-1');
            const step2 = document.getElementById('step-2');
            const nextBtn = document.getElementById('step1-next');
            const backBtn = document.getElementById('step2-back');
            const indicator1 = document.getElementById('step1-indicator');
            const indicator2 = document.getElementById('step2-indicator');
            const label1 = document.getElementById('step1-label');
            const label2 = document.getElementById('step2-label');
            const progress = document.getElementById('step-progress');

            function validateStep1() {
                const fields = [
                    document.getElementById('email'),
                    document.getElementById('campaign_title'),
                    document.getElementById('category_id'),
                    document.getElementById('target_amount'),
                    document.getElementById('duration'),
                ];
                for (const field of fields) {
                    if (!field.value || field.value.trim() === '') {
                        field.classList.add('border-red-300');
                        field.focus();
                        return false;
                    }
                    field.classList.remove('border-red-300');
                }
                return true;
            }

            nextBtn.addEventListener('click', function () {
                if (!validateStep1()) return;
                step1.classList.add('hidden');
                step2.classList.remove('hidden');
                indicator1.classList.remove('register-maroon');
                indicator1.classList.add('bg-terracotta');
                indicator2.classList.remove('bg-gray-200');
                indicator2.classList.add('register-maroon');
                indicator2.classList.remove('text-gray-500');
                indicator2.classList.add('text-white');
                label1.classList.remove('register-maroon-text');
                label1.classList.add('text-terracotta');
                label2.classList.remove('text-gray-400');
                label2.classList.add('register-maroon-text');
                progress.style.width = '100%';
            });

            backBtn.addEventListener('click', function () {
                step2.classList.add('hidden');
                step1.classList.remove('hidden');
                indicator1.classList.remove('bg-terracotta');
                indicator1.classList.add('register-maroon');
                indicator2.classList.remove('register-maroon');
                indicator2.classList.add('bg-gray-200');
                indicator2.classList.remove('text-white');
                indicator2.classList.add('text-gray-500');
                label1.classList.remove('text-terracotta');
                label1.classList.add('register-maroon-text');
                label2.classList.remove('register-maroon-text');
                label2.classList.add('text-gray-400');
                progress.style.width = '0%';
            });
        });
    </script>
</x-guest-layout>
