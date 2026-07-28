<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/png" href="{{ asset('assets/images/logo/favicon.png') }}">
    <title>Privacy Policy - {{ config('app.name', 'Support Sphere') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800,900&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css'])
    <style>
        [x-cloak] { display: none !important; }
        html { scroll-behavior: smooth; }
    </style>
</head>
<body class="font-sans antialiased bg-gray-50 text-gray-900">

    {{-- Nav --}}
    <nav class="bg-white border-b border-gray-200">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-4 flex items-center justify-between">
            <a href="{{ url('/') }}" class="flex items-center gap-2.5">
                <img src="{{ asset('assets/images/logo/logo.png') }}" alt="Support Sphere" class="h-8 w-auto">
            </a>
            <a href="{{ url('/') }}" class="text-sm font-medium text-[#CE5F26] hover:underline">&larr; Back to Home</a>
        </div>
    </nav>

    {{-- Content --}}
    <div class="max-w-4xl mx-auto px-6 sm:px-8 lg:px-12 py-12 lg:py-16">
        <div class="bg-white border border-gray-200 p-10 lg:p-16">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Privacy Policy</h1>
            <p class="text-sm text-gray-400 mb-8">Last updated: {{ date('F j, Y') }}</p>

            <div class="space-y-6 text-sm leading-relaxed">

                <p>Support Sphere ("we", "our", or "us") is committed to protecting your privacy. This Privacy Policy explains how we collect, use, disclose, and safeguard your information when you use our fundraising platform.</p>

                <h2 class="text-lg font-semibold text-gray-800 mt-8 mb-3">1. Information We Collect</h2>
                <p>We collect personal information you provide directly to us, including:</p>
                <ul class="list-disc list-inside space-y-1 text-gray-600">
                    <li><strong>Account Information:</strong> name, email address, phone number, and password when you register.</li>
                    <li><strong>KYC Information:</strong> government-issued ID number, date of birth, address, and uploaded identification documents for verification purposes.</li>
                    <li><strong>Payment Information:</strong> M-Pesa phone number, bank account details, and transaction records for processing donations and withdrawals.</li>
                    <li><strong>Donor Information:</strong> donor name, phone number, email address, and donation amount when you contribute to a campaign.</li>
                    <li><strong>Communications:</strong> messages, feedback, and support requests you send to us.</li>
                </ul>

                <h2 class="text-lg font-semibold text-gray-800 mt-8 mb-3">2. How We Use Your Information</h2>
                <ul class="list-disc list-inside space-y-1 text-gray-600">
                    <li>To operate, maintain, and improve our fundraising platform.</li>
                    <li>To process donations and withdrawals, including sending payment confirmations and receipts.</li>
                    <li>To verify your identity through KYC checks, as required by applicable laws and regulations.</li>
                    <li>To send you administrative messages, updates, and notifications related to your account and campaigns.</li>
                    <li>To send SMS notifications to campaign owners and treasurers when a donation is received.</li>
                    <li>To send thank-you messages to donors via SMS or email.</li>
                    <li>To comply with legal obligations and enforce our terms of service.</li>
                </ul>

                <h2 class="text-lg font-semibold text-gray-800 mt-8 mb-3">3. Platform Fees & SMS Charges</h2>
                <p>Support Sphere generates revenue through the following fees, which are clearly disclosed to campaign owners:</p>
                <ul class="list-disc list-inside space-y-1 text-gray-600">
                    <li><strong>Platform Fee:</strong> A percentage-based fee deducted from each withdrawal request. The platform fee percentage is set globally and displayed to campaign owners before they submit a withdrawal.</li>
                    <li><strong>SMS Charges:</strong> Campaign owners are allocated SMS credits used to send donation alerts and thank-you messages. Upon account approval, each user receives <strong>250 free SMS credits</strong>. Each SMS sent from the platform deducts one credit from the campaign owner's account. If credits run out, SMS notifications may be billed to the owner. The cost per SMS credit is configurable by the platform administrator.</li>
                </ul>
                <p class="text-gray-600">Both the platform fee and SMS charges are deducted from the withdrawal amount and are clearly itemised in the withdrawal summary and invoice.</p>

                <h2 class="text-lg font-semibold text-gray-800 mt-8 mb-3">4. SMS & Communication</h2>
                <p>We use SMS to send important notifications related to your campaigns and donations. By creating an account, you consent to receiving SMS communications from us. These include:</p>
                <ul class="list-disc list-inside space-y-1 text-gray-600">
                    <li>Donation receipt notifications to donors who provide their phone number.</li>
                    <li>New donation alerts to campaign owners and designated treasurers.</li>
                    <li>Withdrawal verification OTPs sent to campaign owners and treasurers.</li>
                    <li>Account verification and security alerts.</li>
                </ul>
                <p class="text-gray-600">You may opt out of marketing communications at any time, but transactional SMS messages (such as donation receipts and withdrawal OTPs) are necessary for the platform to function.</p>

                <h2 class="text-lg font-semibold text-gray-800 mt-8 mb-3">5. Data Sharing & Disclosure</h2>
                <p>We do not sell your personal information. We may share your data only in the following circumstances:</p>
                <ul class="list-disc list-inside space-y-1 text-gray-600">
                    <li><strong>Payment Processors:</strong> We share necessary information with Safaricom (M-Pesa) and other payment gateways to process transactions.</li>
                    <li><strong>SMS Providers:</strong> We share phone numbers with our SMS gateway provider (TextSMS) to deliver notifications.</li>
                    <li><strong>Legal Compliance:</strong> We may disclose information if required by law, regulation, or legal process.</li>
                    <li><strong>With Your Consent:</strong> We may share your information for other purposes with your explicit consent.</li>
                </ul>

                <h2 class="text-lg font-semibold text-gray-800 mt-8 mb-3">6. Data Security</h2>
                <p>We implement appropriate technical and organisational measures to protect your personal information, including encryption of sensitive data in transit and at rest, secure server infrastructure, and access controls. However, no method of transmission over the Internet is 100% secure.</p>

                <h2 class="text-lg font-semibold text-gray-800 mt-8 mb-3">7. Data Retention</h2>
                <p>We retain your personal information for as long as your account is active or as needed to provide you services, comply with legal obligations, resolve disputes, and enforce our agreements. KYC documents are retained in accordance with applicable regulatory requirements.</p>

                <h2 class="text-lg font-semibold text-gray-800 mt-8 mb-3">8. Your Rights</h2>
                <p>Depending on your jurisdiction, you may have the right to:</p>
                <ul class="list-disc list-inside space-y-1 text-gray-600">
                    <li>Access, correct, or delete your personal information.</li>
                    <li>Withdraw consent where processing is based on consent.</li>
                    <li>Object to or restrict processing of your data.</li>
                    <li>Request portability of your data.</li>
                    <li>Lodge a complaint with a data protection authority.</li>
                </ul>
                <p class="text-gray-600">To exercise these rights, contact us at <strong>info@supportsphere.co.ke</strong>.</p>

                <h2 class="text-lg font-semibold text-gray-800 mt-8 mb-3">9. Third-Party Services</h2>
                <p>Our platform integrates with third-party services including:</p>
                <ul class="list-disc list-inside space-y-1 text-gray-600">
                    <li><strong>Safaricom M-Pesa</strong> — for payment processing.</li>
                    <li><strong>TextSMS.co.ke</strong> — for SMS delivery.</li>
                    <li><strong>Flutterwave</strong> — for card and PayPal payments.</li>
                </ul>
                <p class="text-gray-600">These third parties have their own privacy policies governing the use of your data.</p>

                <h2 class="text-lg font-semibold text-gray-800 mt-8 mb-3">10. Changes to This Policy</h2>
                <p>We may update this Privacy Policy from time to time. We will notify you of any changes by posting the new policy on this page and updating the "Last updated" date. Material changes will be communicated via email or platform notification.</p>

                <h2 class="text-lg font-semibold text-gray-800 mt-8 mb-3">11. Contact Us</h2>
                <p>If you have questions about this Privacy Policy or our data practices, please contact us at:</p>
                <div class="bg-gray-50 p-4 mt-3 text-gray-600">
                    <p><strong>Email:</strong> info@supportsphere.co.ke</p>
                    <p><strong>Phone:</strong> +254 732447447</p>
                    <p><strong>Address:</strong> Nairobi, Kenya</p>
                </div>

            </div>
        </div>
    </div>

    @include('partials.footer')

</body>
</html>