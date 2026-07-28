@extends('emails.layout')

@section('title', $type === 'approved' ? 'Campaign Approved - Support Sphere' : 'Campaign Update - Support Sphere')

@section('content')
    <h1 style="color: {{ $type === 'approved' ? '#16a34a' : '#dc2626' }};">
        {{ $type === 'approved' ? 'Approved!' : 'Campaign Update' }}
    </h1>

    <p>Dear {{ $campaign->user->name }},</p>

    @if($type === 'approved')
        <p>Great news! Your campaign <strong>"{{ $campaign->title }}"</strong> has been reviewed and <strong style="color:#16a34a;">approved</strong>. It is now live and accepting donations.</p>
    @else
        <p>Your campaign <strong>"{{ $campaign->title }}"</strong> has been reviewed and we were unable to approve it at this time. It has been moved back to draft so you can make adjustments.</p>
    @endif

    <table style="width:100%;border-collapse:collapse;margin-bottom:20px;font-size:14px;">
        <tr>
            <td style="padding:8px 12px;border:1px solid #e5e7eb;background:#f9fafb;font-weight:600;color:#374151;">Campaign</td>
            <td style="padding:8px 12px;border:1px solid #e5e7eb;color:#4b5563;">{{ $campaign->title }}</td>
        </tr>
        <tr>
            <td style="padding:8px 12px;border:1px solid #e5e7eb;background:#f9fafb;font-weight:600;color:#374151;">Target</td>
            <td style="padding:8px 12px;border:1px solid #e5e7eb;color:#4b5563;">KES {{ number_format($campaign->target_amount, 0) }}</td>
        </tr>
    </table>

    <div class="btn-wrapper">
        @if($type === 'approved')
            <a href="{{ route('campaigns.show', $campaign) }}" class="btn" style="display:inline-block;padding:13px 32px;font-size:14px;font-weight:600;text-decoration:none;border-radius:8px;color:#ffffff;background-color:#ce5f26;">View Campaign</a>
        @else
            <a href="{{ route('campaigns.edit', $campaign) }}" class="btn" style="display:inline-block;padding:13px 32px;font-size:14px;font-weight:600;text-decoration:none;border-radius:8px;color:#ffffff;background-color:#ce5f26;">Edit Campaign</a>
        @endif
    </div>

    <hr class="divider">

    <p class="sub-text">Thank you for using Support Sphere!</p>
@endsection
