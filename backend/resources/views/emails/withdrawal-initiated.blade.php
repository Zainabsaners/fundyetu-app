@extends('emails.layout')

@section('title', 'Withdrawal Requested - Support Sphere')

@section('content')
    <h1>Withdrawal Requested</h1>

    <p>Hello {{ $user->name }},</p>

    <p>A withdrawal request has been made on your campaign <strong>"{{ $campaign->title }}"</strong>.</p>

    <table style="width:100%;border-collapse:collapse;margin-bottom:20px;font-size:14px;">
        <tr>
            <td style="padding:8px 12px;border:1px solid #e5e7eb;background:#f9fafb;font-weight:600;color:#374151;">Amount</td>
            <td style="padding:8px 12px;border:1px solid #e5e7eb;color:#4b5563;font-weight:600;">KES {{ number_format($withdrawal->amount, 0) }}</td>
        </tr>
        <tr>
            <td style="padding:8px 12px;border:1px solid #e5e7eb;background:#f9fafb;font-weight:600;color:#374151;">Destination</td>
            <td style="padding:8px 12px;border:1px solid #e5e7eb;color:#4b5563;">{{ strtoupper($withdrawal->destination_type) }} — {{ $withdrawal->destination_ref }}</td>
        </tr>
        <tr>
            <td style="padding:8px 12px;border:1px solid #e5e7eb;background:#f9fafb;font-weight:600;color:#374151;">Date</td>
            <td style="padding:8px 12px;border:1px solid #e5e7eb;color:#4b5563;">{{ $withdrawal->created_at->format('M j, Y H:i') }}</td>
        </tr>
    </table>

    <div class="btn-wrapper">
        <a href="{{ $url }}" class="btn" style="display:inline-block;padding:13px 32px;font-size:14px;font-weight:600;text-decoration:none;border-radius:8px;color:#ffffff;background-color:#ce5f26;">View Withdrawals</a>
    </div>

    <hr class="divider">

    <p class="sub-text">If you did <strong>not</strong> authorize this withdrawal, please contact support immediately at <a href="mailto:{{ config('mail.admin_address', 'admin@supportsphere.co.ke') }}" style="color:#ce5f26;">{{ config('mail.admin_address', 'admin@supportsphere.co.ke') }}</a>.</p>
@endsection
