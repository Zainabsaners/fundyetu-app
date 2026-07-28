@extends('emails.layout')

@section('title', 'Account Activated - Support Sphere')

@section('content')
    <h1>Your Account Has Been Activated!</h1>

    <p>Hello {{ $user->name }},</p>

    <p>Great news! Your Support Sphere account has been reviewed and <strong style="color:#16a34a;">activated</strong>. You can now log in and access your dashboard to start fundraising.</p>

    <p style="margin-bottom:8px;">Here's what you can do now:</p>
    <ul style="color:#4b5563;font-size:14px;margin:0 0 20px;padding-left:20px;">
        <li style="margin-bottom:4px;">Access your dashboard and manage your campaigns</li>
        <li style="margin-bottom:4px;">Complete your KYC verification to submit campaigns</li>
        <li style="margin-bottom:4px;">View your SMS credits and withdrawal options</li>
    </ul>

    <div class="btn-wrapper">
        <a href="{{ $url }}" class="btn" style="display:inline-block;padding:13px 32px;font-size:14px;font-weight:600;text-decoration:none;border-radius:8px;color:#ffffff;background-color:#ce5f26;">Go to Dashboard</a>
    </div>

    <hr class="divider">

    <p class="sub-text">If you have any questions, feel free to contact our support team.</p>
@endsection
