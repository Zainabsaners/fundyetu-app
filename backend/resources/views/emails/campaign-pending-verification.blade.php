@extends('emails.layout')

@section('title', 'New Campaign Pending Verification - Support Sphere')

@section('content')
    <h1>New Campaign Pending Verification</h1>

    <p>Hello Admin,</p>

    <p>A new campaign has been submitted for verification.</p>

    <table style="width:100%;border-collapse:collapse;margin-bottom:20px;font-size:14px;">
        <tr>
            <td style="padding:8px 12px;border:1px solid #e5e7eb;background:#f9fafb;font-weight:600;color:#374151;">Campaign</td>
            <td style="padding:8px 12px;border:1px solid #e5e7eb;color:#4b5563;">{{ $campaign->title }}</td>
        </tr>
        <tr>
            <td style="padding:8px 12px;border:1px solid #e5e7eb;background:#f9fafb;font-weight:600;color:#374151;">Owner</td>
            <td style="padding:8px 12px;border:1px solid #e5e7eb;color:#4b5563;">{{ $campaign->user->name }} ({{ $campaign->user->email }})</td>
        </tr>
        <tr>
            <td style="padding:8px 12px;border:1px solid #e5e7eb;background:#f9fafb;font-weight:600;color:#374151;">Target</td>
            <td style="padding:8px 12px;border:1px solid #e5e7eb;color:#4b5563;">KES {{ number_format($campaign->target_amount, 0) }}</td>
        </tr>
    </table>

    <div class="btn-wrapper">
        <a href="{{ $url }}" class="btn" style="display:inline-block;padding:13px 32px;font-size:14px;font-weight:600;text-decoration:none;border-radius:8px;color:#ffffff;background-color:#ce5f26;">Review Campaign</a>
    </div>

    <hr class="divider">

    <p class="sub-text">Please review and approve or reject this campaign at your earliest convenience.</p>
@endsection
