@extends('emails.layout')

@section('title', 'KYC Documents Re-submitted - Support Sphere')

@section('content')
    <h1>KYC Documents Re-submitted</h1>

    <p>Hello Admin,</p>

    <p><strong>{{ $user->name }}</strong> has re-submitted their KYC documents for review. Their KYC status has been updated to <strong>Unverified</strong> — ready for your review.</p>

    <table style="width:100%;border-collapse:collapse;margin-bottom:20px;font-size:14px;">
        <tr>
            <td style="padding:8px 12px;border:1px solid #e5e7eb;background:#f9fafb;font-weight:600;color:#374151;">Name</td>
            <td style="padding:8px 12px;border:1px solid #e5e7eb;color:#4b5563;">{{ $user->name }}</td>
        </tr>
        <tr>
            <td style="padding:8px 12px;border:1px solid #e5e7eb;background:#f9fafb;font-weight:600;color:#374151;">Email</td>
            <td style="padding:8px 12px;border:1px solid #e5e7eb;color:#4b5563;">{{ $user->email }}</td>
        </tr>
    </table>

    <div class="btn-wrapper">
        <a href="{{ $url }}" class="btn" style="display:inline-block;padding:13px 32px;font-size:14px;font-weight:600;text-decoration:none;border-radius:8px;color:#ffffff;background-color:#ce5f26;">Review KYC</a>
    </div>
@endsection
