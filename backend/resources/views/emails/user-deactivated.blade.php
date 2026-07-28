@extends('emails.layout')

@section('title', 'Account Deactivated - Support Sphere')

@section('content')
    <h1>Account Deactivated</h1>

    <p>Hello {{ $user->name }},</p>

    <p>Your Support Sphere account has been deactivated. You can no longer access your dashboard or manage campaigns.</p>

    @if($reason)
        <table style="width:100%;border-collapse:collapse;margin-bottom:20px;font-size:14px;">
            <tr>
                <td style="padding:8px 12px;border:1px solid #e5e7eb;background:#fef2f2;font-weight:600;color:#dc2626;">Reason</td>
                <td style="padding:8px 12px;border:1px solid #e5e7eb;color:#4b5563;">{{ $reason }}</td>
            </tr>
        </table>
    @endif

    <p>If you believe this was done in error, please contact our support team for assistance.</p>

    <hr class="divider">

    <p class="sub-text">If you have any questions, feel free to contact our support team.</p>
@endsection
