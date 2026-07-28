@extends('emails.layout')

@section('title', 'Campaign Update - Support Sphere')

@section('content')
    <h1>Campaign Update</h1>

    <p>Hello {{ $user->name }},</p>

    <p>Your campaign <strong>"{{ $campaign->title }}"</strong> has been reviewed and we were unable to approve it at this time.</p>

    <table style="width:100%;border-collapse:collapse;margin-bottom:20px;font-size:14px;">
        <tr>
            <td style="padding:8px 12px;border:1px solid #e5e7eb;background:#f9fafb;font-weight:600;color:#374151;">Campaign</td>
            <td style="padding:8px 12px;border:1px solid #e5e7eb;color:#4b5563;">{{ $campaign->title }}</td>
        </tr>
        <tr>
            <td style="padding:8px 12px;border:1px solid #e5e7eb;background:#fef2f2;font-weight:600;color:#dc2626;">Reason</td>
            <td style="padding:8px 12px;border:1px solid #e5e7eb;color:#4b5563;">{{ $reason }}</td>
        </tr>
    </table>

    <p>You can edit your campaign based on this feedback and resubmit it for review.</p>

    <div class="btn-wrapper">
        <a href="{{ $url }}" class="btn" style="display:inline-block;padding:13px 32px;font-size:14px;font-weight:600;text-decoration:none;border-radius:8px;color:#ffffff;background-color:#ce5f26;">Edit Campaign</a>
    </div>

    <hr class="divider">

    <p class="sub-text">Thank you for using Support Sphere!</p>
@endsection
