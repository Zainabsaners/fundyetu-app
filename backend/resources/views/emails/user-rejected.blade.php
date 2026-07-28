@extends('emails.layout')

@section('title', 'KYC Verification Update - Support Sphere')

@section('content')
    <h1>KYC Verification Update</h1>

    <p>Hello {{ $user->name }},</p>

    <p>Your KYC documents have been reviewed and we were unable to verify them at this time.</p>

    @if($reason)
        <table style="width:100%;border-collapse:collapse;margin-bottom:20px;font-size:14px;">
            <tr>
                <td style="padding:8px 12px;border:1px solid #e5e7eb;background:#fef2f2;font-weight:600;color:#dc2626;">Reason</td>
                <td style="padding:8px 12px;border:1px solid #e5e7eb;color:#4b5563;">{{ $reason }}</td>
            </tr>
        </table>
    @endif

    <p>You can upload new KYC documents from your dashboard and resubmit for verification.</p>

    <hr class="divider">

    <p class="sub-text">If you have any questions, feel free to contact our support team.</p>
@endsection
