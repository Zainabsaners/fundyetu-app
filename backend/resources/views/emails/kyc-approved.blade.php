@extends('emails.layout')

@section('title', 'KYC Verified - Support Sphere')

@section('content')
    <h1>KYC Verification Approved!</h1>

    <p>Hello {{ $user->name }},</p>

    <p>Your KYC documents have been reviewed and <strong style="color:#16a34a;">approved</strong>. You can now submit campaigns for fundraising on Support Sphere.</p>

    <p style="margin-bottom:8px;">Here's what you can do now:</p>
    <ul style="color:#4b5563;font-size:14px;margin:0 0 20px;padding-left:20px;">
        <li style="margin-bottom:4px;">Create and submit fundraising campaigns</li>
        <li style="margin-bottom:4px;">Receive donations from supporters</li>
        <li style="margin-bottom:4px;">Withdraw funds directly to your {{ strtoupper($user->withdrawal_method ?? 'account') }}</li>
    </ul>

    <div class="btn-wrapper">
        <a href="{{ $url }}" class="btn" style="display:inline-block;padding:13px 32px;font-size:14px;font-weight:600;text-decoration:none;border-radius:8px;color:#ffffff;background-color:#ce5f26;">Go to Dashboard</a>
    </div>

    <hr class="divider">

    <p class="sub-text">If you have any questions, feel free to contact our support team.</p>
@endsection
