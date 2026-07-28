@extends('emails.layout')

@section('title', 'Verify Email Address - Support Sphere')

@section('content')
    <h1>Welcome to Support Sphere!</h1>

    <p>Dear {{ $user->name }},</p>

    <p>
        Thank you for creating an account with Support Sphere. Please click the button below to verify your email address and get started on your fundraising journey.
    </p>

    <div class="btn-wrapper">
        <a href="{{ $verificationUrl }}" class="btn" style="display:inline-block;padding:13px 32px;font-size:14px;font-weight:600;text-decoration:none;border-radius:8px;color:#ffffff;background-color:#ce5f26;">Verify Email Address</a>
    </div>

    <hr class="divider">

    <p class="sub-text">
        If you did not create an account, no further action is required. This link will expire in {{ config('auth.verification.expire', 60) }} minutes.
    </p>

    <p class="sub-text">
        If you're having trouble clicking the "Verify Email Address" button, copy and paste the URL below into your web browser:
        <br>
        <span style="font-size:12px;color:#6b7280;word-break:break-all;">{{ $verificationUrl }}</span>
    </p>
@endsection
