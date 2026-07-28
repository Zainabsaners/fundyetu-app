<x-admin-layout title="Settings">
    <x-slot name="header">Settings</x-slot>

    @if($errors->any())
        <div class="mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 text-sm">
            <ul class="list-disc list-inside space-y-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div id="settings-page" x-data="{ tab: 'platform' }" class="flex gap-6">
        {{-- Sidebar Tabs --}}
        <div class="w-56 shrink-0">
            <div class="bg-white border border-gray-200 overflow-hidden">
                <div class="px-4 py-3 border-b border-gray-100 bg-gray-50">
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Sections</p>
                </div>
                <nav class="p-2 space-y-0.5">
                    <button @click="tab = 'platform'" :class="tab === 'platform' ? 'bg-[#CE5F26]/10 text-[#CE5F26] font-semibold border-l-2 border-[#CE5F26]' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-800 border-l-2 border-transparent'" class="w-full flex items-center gap-3 px-3 py-2.5 text-sm text-left transition">
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        Platform
                    </button>
                    <button @click="tab = 'sms'" :class="tab === 'sms' ? 'bg-[#CE5F26]/10 text-[#CE5F26] font-semibold border-l-2 border-[#CE5F26]' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-800 border-l-2 border-transparent'" class="w-full flex items-center gap-3 px-3 py-2.5 text-sm text-left transition">
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/></svg>
                        SMS
                    </button>
                    <button @click="tab = 'gateways'" :class="tab === 'gateways' ? 'bg-[#CE5F26]/10 text-[#CE5F26] font-semibold border-l-2 border-[#CE5F26]' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-800 border-l-2 border-transparent'" class="w-full flex items-center gap-3 px-3 py-2.5 text-sm text-left transition">
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                        Payments
                    </button>
                    <button @click="tab = 'smtp'" :class="tab === 'smtp' ? 'bg-[#CE5F26]/10 text-[#CE5F26] font-semibold border-l-2 border-[#CE5F26]' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-800 border-l-2 border-transparent'" class="w-full flex items-center gap-3 px-3 py-2.5 text-sm text-left transition">
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        SMTP
                    </button>
                    <button @click="tab = 'activity'" :class="tab === 'activity' ? 'bg-[#CE5F26]/10 text-[#CE5F26] font-semibold border-l-2 border-[#CE5F26]' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-800 border-l-2 border-transparent'" class="w-full flex items-center gap-3 px-3 py-2.5 text-sm text-left transition">
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                        Activity Logs
                    </button>
                    <button @click="tab = 'notifications'" :class="tab === 'notifications' ? 'bg-[#CE5F26]/10 text-[#CE5F26] font-semibold border-l-2 border-[#CE5F26]' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-800 border-l-2 border-transparent'" class="w-full flex items-center gap-3 px-3 py-2.5 text-sm text-left transition">
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                        Notifications
                        @if(auth()->user()->unreadNotifications->count())
                            <span class="ml-auto inline-flex items-center justify-center w-5 h-5 text-[10px] font-bold text-white bg-red-500">{{ auth()->user()->unreadNotifications->count() }}</span>
                        @endif
                    </button>
                    <button @click="tab = 'profile'" :class="tab === 'profile' ? 'bg-[#CE5F26]/10 text-[#CE5F26] font-semibold border-l-2 border-[#CE5F26]' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-800 border-l-2 border-transparent'" class="w-full flex items-center gap-3 px-3 py-2.5 text-sm text-left transition">
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        Profile
                    </button>
                    <button @click="tab = 'cache'" :class="tab === 'cache' ? 'bg-[#CE5F26]/10 text-[#CE5F26] font-semibold border-l-2 border-[#CE5F26]' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-800 border-l-2 border-transparent'" class="w-full flex items-center gap-3 px-3 py-2.5 text-sm text-left transition">
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                        Cache
                    </button>
                </nav>
            </div>
        </div>

        {{-- Content --}}
        <div class="flex-1 min-w-0">
            {{-- Tab: Platform --}}
            <div x-show="tab === 'platform'" x-cloak>
                <form action="{{ route('admin.settings.update') }}" method="POST" class="bg-white border border-gray-200">
                    @csrf
                    <div class="px-6 py-4 bg-[#1B2A4A]">
                        <h3 class="font-semibold text-white">Platform Settings</h3>
                    </div>
                    <div class="p-6 space-y-5">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Platform Fee (%)</label>
                                <input type="number" name="platform_fee_percent" step="0.01" min="0" max="100"
                                       value="{{ old('platform_fee_percent', $settings['platform_fee_percent'] ?? 4.25) }}"
                                       class="w-full border border-gray-200 outline-none focus:border-[#CE5F26] focus:ring-1 focus:ring-[#CE5F26] px-3 py-2 text-sm">
                                <p class="text-xs text-gray-400 mt-1">Percentage charged per withdrawal</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Withdrawal Fee (KES)</label>
                                <input type="number" name="withdrawal_fee" step="any" min="0"
                                       value="{{ old('withdrawal_fee', $settings['withdrawal_fee'] ?? 30) }}"
                                       class="w-full border border-gray-200 outline-none focus:border-[#CE5F26] focus:ring-1 focus:ring-[#CE5F26] px-3 py-2 text-sm">
                                <p class="text-xs text-gray-400 mt-1">Flat fee per withdrawal request</p>
                            </div>
                        </div>
                        <div class="pt-4 border-t border-gray-100 flex items-center justify-between">
                            <p class="text-xs text-gray-400">Changes apply to all new withdrawals</p>
                            <button type="submit" class="px-5 py-2 text-sm font-semibold text-white transition" style="background-color: #CE5F26;">Save Platform Settings</button>
                        </div>
                    </div>
                </form>
            </div>

            {{-- Tab: SMS --}}
            <div x-show="tab === 'sms'" x-cloak>
                <form action="{{ route('admin.settings.update') }}" method="POST" class="bg-white border border-gray-200">
                    @csrf
                    <div class="px-6 py-4 bg-[#1B2A4A]">
                        <h3 class="font-semibold text-white">SMS Configuration</h3>
                    </div>
                    <div class="p-6 space-y-5">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Cost Per Credit (KES)</label>
                                <input type="number" name="sms_cost_per_credit" step="0.5" min="0"
                                       value="{{ old('sms_cost_per_credit', $settings['sms_cost_per_credit'] ?? 5) }}"
                                       class="w-full border border-gray-200 outline-none focus:border-[#CE5F26] focus:ring-1 focus:ring-[#CE5F26] px-3 py-2 text-sm">
                                <p class="text-xs text-gray-400 mt-1">Cost of one SMS credit</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Sender ID</label>
                                   <input type="text" name="sms_sender_id"
                                        value="{{ old('sms_sender_id', ($settings['sms_sender_id'] ?? null) ?: env('TEXTSMS_SHORTCODE', 'Support Sphere')) }}"
                                       class="w-full border border-gray-200 outline-none focus:border-[#CE5F26] focus:ring-1 focus:ring-[#CE5F26] px-3 py-2 text-sm">
                                <p class="text-xs text-gray-400 mt-1">SMS sender name shown to recipients</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">API Provider</label>
                                <select name="sms_provider"
                                        class="w-full border border-gray-200 outline-none focus:border-[#CE5F26] focus:ring-1 focus:ring-[#CE5F26] px-3 py-2 text-sm bg-white">
                                    <option value="textsms" {{ ($settings['sms_provider'] ?? 'textsms') === 'textsms' ? 'selected' : '' }}>TextSMS</option>
                                    <option value="africastalking" {{ ($settings['sms_provider'] ?? '') === 'africastalking' ? 'selected' : '' }}>Africa's Talking</option>
                                    <option value="twilio" {{ ($settings['sms_provider'] ?? '') === 'twilio' ? 'selected' : '' }}>Twilio</option>
                                </select>
                                <p class="text-xs text-gray-400 mt-1">SMS gateway provider</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">API Key</label>
                                   <input type="password" name="sms_api_key"
                                        value="{{ old('sms_api_key', ($settings['sms_api_key'] ?? null) ?: env('TEXTSMS_API_KEY', '')) }}"
                                       class="w-full border border-gray-200 outline-none focus:border-[#CE5F26] focus:ring-1 focus:ring-[#CE5F26] px-3 py-2 text-sm">
                                <p class="text-xs text-gray-400 mt-1">API key from your SMS provider</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Partner ID</label>
                                   <input type="text" name="sms_partner_id"
                                        value="{{ old('sms_partner_id', ($settings['sms_partner_id'] ?? null) ?: env('TEXTSMS_PARTNER_ID', '')) }}"
                                       class="w-full border border-gray-200 outline-none focus:border-[#CE5F26] focus:ring-1 focus:ring-[#CE5F26] px-3 py-2 text-sm">
                                <p class="text-xs text-gray-400 mt-1">Your TextSMS partner ID</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">API Secret</label>
                                <input type="password" name="sms_api_secret"
                                       value="{{ old('sms_api_secret', $settings['sms_api_secret'] ?? '') }}"
                                       class="w-full border border-gray-200 outline-none focus:border-[#CE5F26] focus:ring-1 focus:ring-[#CE5F26] px-3 py-2 text-sm">
                                <p class="text-xs text-gray-400 mt-1">API secret from your SMS provider</p>
                            </div>
                        </div>
                        <div class="pt-4 border-t border-gray-100 flex items-center justify-between">
                            <p class="text-xs text-gray-400">SMS credits deducted from user balances on withdrawal</p>
                            <button type="submit" class="px-5 py-2 text-sm font-semibold text-white transition" style="background-color: #CE5F26;">Save SMS Settings</button>
                        </div>
                    </div>
                </form>
            </div>

            {{-- Tab: Payment Gateways --}}
            <div x-show="tab === 'gateways'" x-cloak>
                <form action="{{ route('admin.settings.update') }}" method="POST" class="bg-white border border-gray-200">
                    @csrf
                    <div class="px-6 py-4 bg-[#1B2A4A]">
                        <h3 class="font-semibold text-white">Payment Gateways</h3>
                    </div>
                    <div class="p-6 space-y-6">
                        <div>
                            <h4 class="text-sm font-semibold text-gray-800 mb-3">Enable/Disable Payment Methods</h4>
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                                @foreach(['mpesa' => 'M-Pesa', 'airtel' => 'Airtel Money', 'card' => 'VISA / MasterCard', 'paypal' => 'PayPal'] as $key => $label)
                                <label class="flex items-center gap-2 p-3 border border-gray-200 cursor-pointer hover:bg-gray-50 transition">
                                    <input type="hidden" name="{{ $key }}_enabled" value="0">
                                    <input type="checkbox" name="{{ $key }}_enabled" value="1"
                                           {{ ($settings[$key.'_enabled'] ?? '0') === '1' ? 'checked' : '' }}
                                           class="rounded border-gray-300 text-[#CE5F26] focus:ring-[#CE5F26]">
                                    <span class="text-sm font-medium text-gray-700">{{ $label }}</span>
                                </label>
                                @endforeach
                            </div>
                        </div>

                        <div class="pt-4 border-t border-gray-100">
                            <h4 class="text-sm font-semibold text-gray-800 mb-3">M-Pesa Credentials</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Consumer Key</label>
                                    <input type="text" name="mpesa_consumer_key"
                                            value="{{ old('mpesa_consumer_key', ($settings['mpesa_consumer_key'] ?? null) ?: env('MPESA_CONSUMER_KEY', '')) }}"
                                           class="w-full border border-gray-200 outline-none focus:border-[#CE5F26] focus:ring-1 focus:ring-[#CE5F26] px-3 py-2 text-sm">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Consumer Secret</label>
                                    <input type="password" name="mpesa_consumer_secret"
                                           value="{{ old('mpesa_consumer_secret') }}"
                                           placeholder="Leave blank to keep current"
                                           class="w-full border border-gray-200 outline-none focus:border-[#CE5F26] focus:ring-1 focus:ring-[#CE5F26] px-3 py-2 text-sm">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Passkey</label>
                                    <input type="password" name="mpesa_passkey"
                                           value="{{ old('mpesa_passkey') }}"
                                           placeholder="Leave blank to keep current"
                                           class="w-full border border-gray-200 outline-none focus:border-[#CE5F26] focus:ring-1 focus:ring-[#CE5F26] px-3 py-2 text-sm">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Shortcode</label>
                                    <input type="text" name="mpesa_shortcode"
                                            value="{{ old('mpesa_shortcode', ($settings['mpesa_shortcode'] ?? null) ?: env('MPESA_SHORTCODE', '')) }}"
                                           class="w-full border border-gray-200 outline-none focus:border-[#CE5F26] focus:ring-1 focus:ring-[#CE5F26] px-3 py-2 text-sm">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Environment</label>
                                    <select name="mpesa_environment"
                                            class="w-full border border-gray-200 outline-none focus:border-[#CE5F26] focus:ring-1 focus:ring-[#CE5F26] px-3 py-2 text-sm">
                                        @php $env = old('mpesa_environment', ($settings['mpesa_environment'] ?? null) ?: env('MPESA_ENVIRONMENT', 'sandbox')); @endphp
                                        <option value="sandbox" {{ $env === 'sandbox' ? 'selected' : '' }}>Sandbox</option>
                                        <option value="live" {{ $env === 'live' ? 'selected' : '' }}>Live</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="pt-4 border-t border-gray-100 flex items-center justify-between">
                            <p class="text-xs text-gray-400">Disabled methods are hidden from donors and rejected server-side</p>
                            <button type="submit" class="px-5 py-2 text-sm font-semibold text-white transition" style="background-color: #CE5F26;">Save Payment Settings</button>
                        </div>
                    </div>
                </form>
            </div>

            {{-- Tab: SMTP --}}
            <div x-show="tab === 'smtp'" x-cloak>
                <form action="{{ route('admin.settings.update') }}" method="POST" class="bg-white border border-gray-200">
                    @csrf
                    <div class="px-6 py-4 bg-[#1B2A4A]">
                        <h3 class="font-semibold text-white">SMTP Settings</h3>
                    </div>
                    <div class="p-6 space-y-5">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Mail Host</label>
                                   <input type="text" name="smtp_host"
                                        value="{{ old('smtp_host', ($settings['smtp_host'] ?? null) ?: env('MAIL_HOST', '')) }}"
                                       class="w-full border border-gray-200 outline-none focus:border-[#CE5F26] focus:ring-1 focus:ring-[#CE5F26] px-3 py-2 text-sm">
                                <p class="text-xs text-gray-400 mt-1">SMTP server hostname (e.g. smtp.gmail.com)</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Mail Port</label>
                                   <input type="number" name="smtp_port"
                                        value="{{ old('smtp_port', ($settings['smtp_port'] ?? null) ?: env('MAIL_PORT', '587')) }}"
                                       class="w-full border border-gray-200 outline-none focus:border-[#CE5F26] focus:ring-1 focus:ring-[#CE5F26] px-3 py-2 text-sm">
                                <p class="text-xs text-gray-400 mt-1">Common ports: 587 (TLS), 465 (SSL), 25</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Username</label>
                                   <input type="text" name="smtp_username"
                                        value="{{ old('smtp_username', ($settings['smtp_username'] ?? null) ?: env('MAIL_USERNAME', '')) }}"
                                       class="w-full border border-gray-200 outline-none focus:border-[#CE5F26] focus:ring-1 focus:ring-[#CE5F26] px-3 py-2 text-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                                   <input type="password" name="smtp_password"
                                        value="{{ old('smtp_password') }}"
                                        placeholder="Leave blank to keep current"
                                       class="w-full border border-gray-200 outline-none focus:border-[#CE5F26] focus:ring-1 focus:ring-[#CE5F26] px-3 py-2 text-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Encryption</label>
                                   <select name="smtp_encryption"
                                         class="w-full border border-gray-200 outline-none focus:border-[#CE5F26] focus:ring-1 focus:ring-[#CE5F26] px-3 py-2 text-sm bg-white">
                                      @php $encryption = ($settings['smtp_encryption'] ?? null) ?: env('MAIL_ENCRYPTION', 'tls'); @endphp
                                      <option value="tls" {{ $encryption === 'tls' ? 'selected' : '' }}>TLS</option>
                                     <option value="ssl" {{ $encryption === 'ssl' ? 'selected' : '' }}>SSL</option>
                                     <option value="" {{ $encryption === '' ? 'selected' : '' }}>None</option>
                                 </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">From Address</label>
                                   <input type="email" name="smtp_from_address"
                                        value="{{ old('smtp_from_address', ($settings['smtp_from_address'] ?? null) ?: env('MAIL_FROM_ADDRESS', '')) }}"
                                       class="w-full border border-gray-200 outline-none focus:border-[#CE5F26] focus:ring-1 focus:ring-[#CE5F26] px-3 py-2 text-sm">
                                <p class="text-xs text-gray-400 mt-1">Default sender email address</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">From Name</label>
                                   <input type="text" name="smtp_from_name"
                                        value="{{ old('smtp_from_name', ($settings['smtp_from_name'] ?? null) ?: env('MAIL_FROM_NAME', 'Support Sphere')) }}"
                                       class="w-full border border-gray-200 outline-none focus:border-[#CE5F26] focus:ring-1 focus:ring-[#CE5F26] px-3 py-2 text-sm">
                                <p class="text-xs text-gray-400 mt-1">Default sender name</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Admin Email</label>
                                <input type="email" name="smtp_admin_address"
                                       value="{{ old('smtp_admin_address', $settings['smtp_admin_address'] ?? '') }}"
                                       class="w-full border border-gray-200 outline-none focus:border-[#CE5F26] focus:ring-1 focus:ring-[#CE5F26] px-3 py-2 text-sm">
                                <p class="text-xs text-gray-400 mt-1">Admin notification recipient</p>
                            </div>
                        </div>
                        <div class="pt-4 border-t border-gray-100 flex items-center justify-between">
                            <p class="text-xs text-gray-400">These settings override the .env mail configuration at runtime</p>
                            <button type="submit" class="px-5 py-2 text-sm font-semibold text-white transition" style="background-color: #CE5F26;">Save SMTP Settings</button>
                        </div>
                    </div>
                </form>
            </div>

            {{-- Tab: Activity Logs --}}
            <div x-show="tab === 'activity'" x-cloak>
                <div class="bg-white border border-gray-200">
                    <div class="px-6 py-4 bg-[#1B2A4A] flex items-center justify-between">
                        <h3 class="font-semibold text-white">Activity Logs</h3>
                        <span class="text-xs text-white/60">{{ $activityLogs->total() }} entries</span>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm border-collapse">
                            <thead>
                                <tr class="bg-gray-50/50">
                                    <th class="text-left px-4 py-3 font-semibold text-gray-600 text-xs uppercase tracking-wider border border-gray-200">User</th>
                                    <th class="text-left px-4 py-3 font-semibold text-gray-600 text-xs uppercase tracking-wider border border-gray-200">Type</th>
                                    <th class="text-left px-4 py-3 font-semibold text-gray-600 text-xs uppercase tracking-wider border border-gray-200">Description</th>
                                    <th class="text-left px-4 py-3 font-semibold text-gray-600 text-xs uppercase tracking-wider border border-gray-200">IP Address</th>
                                    <th class="text-left px-4 py-3 font-semibold text-gray-600 text-xs uppercase tracking-wider border border-gray-200">Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($activityLogs as $log)
                                    <tr class="hover:bg-gray-50/50 transition">
                                        <td class="px-4 py-3 border border-gray-200 text-gray-800">{{ $log->user?->name ?? 'System' }}</td>
                                        <td class="px-4 py-3 border border-gray-200">
                                            <span class="text-xs font-medium px-2 py-0.5 rounded-full bg-gray-100 text-gray-600">{{ str_replace('_', ' ', $log->type) }}</span>
                                        </td>
                                        <td class="px-4 py-3 border border-gray-200 text-gray-600 max-w-xs truncate">{{ $log->description ?? '—' }}</td>
                                        <td class="px-4 py-3 border border-gray-200 text-gray-500 text-xs">{{ $log->ip_address ?? '—' }}</td>
                                        <td class="px-4 py-3 border border-gray-200 text-gray-500 text-xs whitespace-nowrap">{{ $log->created_at->format('M j, Y g:i A') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-4 py-8 text-center text-gray-400 text-sm border border-gray-200">No activity logs yet.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    @if($activityLogs->hasPages())
                        <div class="px-4 py-3 border-t border-gray-100">
                            {{ $activityLogs->links() }}
                        </div>
                    @endif
                </div>
            </div>

            {{-- Tab: Notifications --}}
            <div x-show="tab === 'notifications'" x-cloak>
                <div class="bg-white border border-gray-200">
                    <div class="px-6 py-4 bg-[#1B2A4A] flex items-center justify-between">
                        <h3 class="font-semibold text-white">Notifications</h3>
                        <div class="flex items-center gap-2">
                            @if(auth()->user()->unreadNotifications->count())
                                <form action="{{ route('admin.notifications.read') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="text-xs text-white/70 hover:text-white underline transition">Mark all read</button>
                                </form>
                            @endif
                            <span class="text-xs text-white/60">{{ $notifications->total() }} total</span>
                        </div>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm border-collapse">
                            <thead>
                                <tr class="bg-gray-50/50">
                                    <th class="text-left px-4 py-3 font-semibold text-gray-600 text-xs uppercase tracking-wider border border-gray-200">Type</th>
                                    <th class="text-left px-4 py-3 font-semibold text-gray-600 text-xs uppercase tracking-wider border border-gray-200">Message</th>
                                    <th class="text-center px-4 py-3 font-semibold text-gray-600 text-xs uppercase tracking-wider border border-gray-200">Status</th>
                                    <th class="text-left px-4 py-3 font-semibold text-gray-600 text-xs uppercase tracking-wider border border-gray-200">Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($notifications as $notification)
                                    @php
                                        $data = $notification->data;
                                        $title = $data['title'] ?? class_basename($notification->type);
                                        $message = $data['message'] ?? '—';
                                    @endphp
                                    <tr class="hover:bg-gray-50/50 transition {{ is_null($notification->read_at) ? 'bg-blue-50/30' : '' }}">
                                        <td class="px-4 py-3 border border-gray-200">
                                            <span class="text-xs font-medium text-gray-700">{{ $title }}</span>
                                        </td>
                                        <td class="px-4 py-3 border border-gray-200 text-gray-600 max-w-md truncate">{{ $message }}</td>
                                        <td class="px-4 py-3 text-center border border-gray-200">
                                            @if(is_null($notification->read_at))
                                                <span class="text-xs font-medium text-blue-600 bg-blue-50 px-2 py-0.5">Unread</span>
                                            @else
                                                <span class="text-xs text-gray-400">Read</span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3 border border-gray-200 text-gray-500 text-xs whitespace-nowrap">{{ $notification->created_at->format('M j, Y g:i A') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-4 py-8 text-center text-gray-400 text-sm border border-gray-200">No notifications.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    @if($notifications->hasPages())
                        <div class="px-4 py-3 border-t border-gray-100">
                            {{ $notifications->links() }}
                        </div>
                    @endif
                </div>
            </div>

            {{-- Tab: Profile --}}
            <div x-show="tab === 'profile'" x-cloak>
                <form action="{{ route('admin.settings.profile') }}" method="POST" class="bg-white border border-gray-200">
                    @csrf
                    <div class="px-6 py-4 bg-[#1B2A4A]">
                        <h3 class="font-semibold text-white">Admin Profile</h3>
                    </div>
                    <div class="p-6 space-y-5">
                        <div class="flex items-center gap-4 mb-6 pb-6 border-b border-gray-100">
                            <div class="w-14 h-14 rounded-full bg-[#1B2A4A] flex items-center justify-center text-white font-bold text-xl shrink-0">
                                {{ substr(auth()->user()->name, 0, 1) }}
                            </div>
                            <div>
                                <p class="font-semibold text-gray-900">{{ auth()->user()->name }}</p>
                                <p class="text-sm text-gray-500">{{ auth()->user()->email }}</p>
                                <p class="text-xs text-gray-400 mt-0.5">Role: {{ auth()->user()->roles->pluck('name')->implode(', ') }}</p>
                            </div>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
                                <input type="text" name="name" value="{{ old('name', auth()->user()->name) }}"
                                       class="w-full border border-gray-200 outline-none focus:border-[#CE5F26] focus:ring-1 focus:ring-[#CE5F26] px-3 py-2 text-sm" required>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                                <input type="email" name="email" value="{{ old('email', auth()->user()->email) }}"
                                       class="w-full border border-gray-200 outline-none focus:border-[#CE5F26] focus:ring-1 focus:ring-[#CE5F26] px-3 py-2 text-sm" required>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Phone</label>
                                <input type="text" name="phone" value="{{ old('phone', auth()->user()->phone) }}"
                                       class="w-full border border-gray-200 outline-none focus:border-[#CE5F26] focus:ring-1 focus:ring-[#CE5F26] px-3 py-2 text-sm">
                            </div>
                        </div>

                        <div class="pt-4 border-t border-gray-100">
                            <h4 class="text-sm font-semibold text-gray-800 mb-3">Change Password</h4>
                            <p class="text-xs text-gray-400 mb-3">Leave blank to keep current password</p>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">New Password</label>
                                    <input type="password" name="password"
                                           class="w-full border border-gray-200 outline-none focus:border-[#CE5F26] focus:ring-1 focus:ring-[#CE5F26] px-3 py-2 text-sm">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Confirm Password</label>
                                    <input type="password" name="password_confirmation"
                                           class="w-full border border-gray-200 outline-none focus:border-[#CE5F26] focus:ring-1 focus:ring-[#CE5F26] px-3 py-2 text-sm">
                                </div>
                            </div>
                        </div>

                        <div class="pt-4 border-t border-gray-100 flex items-center justify-end">
                            <button type="submit" class="px-5 py-2 text-sm font-semibold text-white transition" style="background-color: #CE5F26;">Save Profile</button>
                        </div>
                    </div>
                </form>
            </div>

            {{-- Tab: Cache --}}
            <div x-show="tab === 'cache'" x-cloak>
                <div class="bg-white border border-gray-200">
                    <div class="px-6 py-4 bg-[#1B2A4A]">
                        <h3 class="font-semibold text-white">Cache Management</h3>
                    </div>
                    <div class="p-6 space-y-4">
                        <p class="text-sm text-gray-600">Clear cached files when you make changes to configs, routes, views, or other cached data.</p>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                            <form action="{{ route('admin.settings.cache', 'config') }}" method="POST">
                                @csrf
                                <button type="submit" class="w-full px-4 py-3 text-sm font-semibold text-white transition hover:opacity-90" style="background-color: #CE5F26;">
                                    Clear Config
                                </button>
                            </form>
                            <form action="{{ route('admin.settings.cache', 'route') }}" method="POST">
                                @csrf
                                <button type="submit" class="w-full px-4 py-3 text-sm font-semibold text-white transition hover:opacity-90" style="background-color: #CE5F26;">
                                    Clear Routes
                                </button>
                            </form>
                            <form action="{{ route('admin.settings.cache', 'view') }}" method="POST">
                                @csrf
                                <button type="submit" class="w-full px-4 py-3 text-sm font-semibold text-white transition hover:opacity-90" style="background-color: #CE5F26;">
                                    Clear Views
                                </button>
                            </form>
                            <form action="{{ route('admin.settings.cache', 'all') }}" method="POST">
                                @csrf
                                <button type="submit" class="w-full px-4 py-3 text-sm font-semibold text-white transition hover:opacity-90" style="background-color: #1B2A4A;">
                                    Clear All Cache
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- OTP Confirmation Modal --}}
    <div x-data="otpModal" x-show="show" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black/50" style="background-color: rgba(0,0,0,0.5);">
        <div @click.outside="show = false" class="bg-white w-full max-w-sm mx-4 border border-gray-200">
            <div class="px-5 py-4 bg-[#1B2A4A] flex items-center justify-between">
                <h3 class="font-semibold text-white text-sm">Confirm Changes</h3>
                <button @click="show = false" class="text-white/60 hover:text-white text-lg leading-none">&times;</button>
            </div>
            <div class="p-5 space-y-4">
                <p class="text-sm text-gray-600">An OTP will be sent to your email. Enter it below to save settings.</p>

                <div x-show="!otpSent">
                    <button @click="sendOtp" :disabled="sending" type="button"
                            class="w-full px-4 py-2.5 text-sm font-semibold text-white transition disabled:opacity-50" style="background-color: #CE5F26;">
                        <span x-show="!sending">Send OTP to Email</span>
                        <span x-show="sending">Sending...</span>
                    </button>
                </div>

                <div x-show="otpSent">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Enter OTP</label>
                    <input type="text" x-model="otp" maxlength="6"
                           class="w-full border border-gray-200 outline-none focus:border-[#CE5F26] focus:ring-1 focus:ring-[#CE5F26] px-3 py-2 text-sm text-center text-lg tracking-widest">

                    <div x-show="error" class="text-red-500 text-xs mt-1" x-text="error"></div>

                    <div class="flex gap-2 mt-3">
                        <button @click="sendOtp" :disabled="sending" type="button"
                                class="flex-1 px-3 py-2 text-xs font-medium text-gray-600 border border-gray-200 hover:bg-gray-50 transition disabled:opacity-50">
                            Resend OTP
                        </button>
                        <button @click="confirmOtp" :disabled="!otp || verifying" type="button"
                                class="flex-1 px-3 py-2 text-xs font-semibold text-white transition disabled:opacity-50" style="background-color: #CE5F26;">
                            <span x-show="!verifying">Confirm & Save</span>
                            <span x-show="verifying">Verifying...</span>
                        </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

    <style>
        [x-cloak] { display: none !important; }
    </style>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('otpModal', () => ({
                show: false,
                sending: false,
                otpSent: false,
                verifying: false,
                otp: '',
                error: '',
                pendingForm: null,

                init() {
                    document.querySelectorAll('[x-data="otpModal"]').forEach(el => {
                        // override submit buttons to intercept form submission
                        const forms = document.querySelectorAll('#settings-page form');
                        forms.forEach(form => {
                            form.addEventListener('submit', (e) => {
                                e.preventDefault();
                                this.pendingForm = form;
                                this.show = true;
                                this.otpSent = false;
                                this.otp = '';
                                this.error = '';
                            });
                        });
                    });
                },

                sendOtp() {
                    this.sending = true;
                    this.error = '';
                    fetch('{{ route('admin.settings.send-otp') }}', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json',
                        },
                    })
                    .then(r => r.json())
                    .then(data => {
                        this.sending = false;
                        if (data.success) {
                            this.otpSent = true;
                        } else {
                            this.error = data.message || 'Failed to send OTP.';
                        }
                    })
                    .catch(() => {
                        this.sending = false;
                        this.error = 'Network error. Please try again.';
                    });
                },

                confirmOtp() {
                    if (!this.otp || this.otp.length !== 6) {
                        this.error = 'Please enter the 6-digit OTP.';
                        return;
                    }
                    this.verifying = true;
                    this.error = '';

                    // Add OTP input to the pending form and submit
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'otp';
                    input.value = this.otp;
                    this.pendingForm.appendChild(input);
                    this.pendingForm.submit();
                },
            }));
        });
    </script>
</x-admin-layout>