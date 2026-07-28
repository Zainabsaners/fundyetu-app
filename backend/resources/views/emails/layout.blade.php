<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Support Sphere')</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background-color: #f4f4f5;
            color: #1f2937;
            line-height: 1.6;
            -webkit-font-smoothing: antialiased;
        }
        .wrapper {
            max-width: 560px;
            margin: 0 auto;
            padding: 32px 16px;
        }
        .card {
            background: #ffffff;
            border-radius: 0;
            overflow: hidden;
            border: 1px solid #e5e7eb;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05), 0 1px 3px rgba(0,0,0,0.03);
        }
        .logo-bar {
            text-align: center;
            padding: 36px 40px 0;
        }
        .logo-bar img {
            height: 40px;
            width: auto;
        }
        .body-content {
            padding: 28px 40px 36px;
        }
        .body-content h1 {
            font-size: 22px;
            font-weight: 700;
            margin-bottom: 8px;
            color: #111827;
        }
        .body-content p {
            font-size: 15px;
            color: #4b5563;
            margin-bottom: 16px;
        }
        .body-content p:last-of-type {
            margin-bottom: 0;
        }
        .btn {
            display: inline-block;
            padding: 13px 32px;
            font-size: 14px;
            font-weight: 600;
            text-decoration: none;
            border-radius: 8px;
            color: #ffffff;
            background-color: #ce5f26;
        }
        .btn-wrapper {
            margin: 24px 0;
            text-align: center;
        }
        hr.divider {
            border: none;
            border-top: 1px solid #e5e7eb;
            margin: 24px 0;
        }
        .sub-text {
            font-size: 13px;
            color: #9ca3af;
            margin-top: 20px;
        }
        .footer {
            padding: 24px 40px;
            border-top: 1px solid #e5e7eb;
            text-align: center;
        }
        .footer p {
            font-size: 12px;
            color: #9ca3af;
            margin-bottom: 4px;
        }
        .footer p:last-child {
            margin-bottom: 0;
        }
        .footer a {
            color: #ce5f26;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="card">
            <div class="logo-bar">
                <img src="{{ $message->embed(public_path('assets/images/logo/logo.png')) }}" alt="Support Sphere">
            </div>

            <div class="body-content">
                @yield('content')
            </div>

            <div class="footer">
                <p>&copy; {{ date('Y') }} Support Sphere. All rights reserved.</p>
                <p>
                    <a href="{{ config('app.url') }}">Support Sphere</a>
                    &middot;
                    <a href="mailto:{{ config('mail.admin_address', 'support@supportsphere.co.ke') }}">Contact Support</a>
                </p>
            </div>
        </div>
        <p style="text-align:center;font-size:11px;color:#9ca3af;margin-top:16px;">
            If you did not request this email, you can safely ignore it.
        </p>
    </div>
</body>
</html>
