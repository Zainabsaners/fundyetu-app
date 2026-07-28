@extends('emails.layout')

@section('title', 'Account Deleted - Support Sphere')

@section('content')
    <h1>Account Deleted</h1>

    <p>Hello {{ $user->name }},</p>

    <p>Your Support Sphere account has been permanently deleted.</p>

    @if($reason)
        <table style="width:100%;border-collapse:collapse;margin-bottom:20px;font-size:14px;">
            <tr>
                <td style="padding:8px 12px;border:1px solid #e5e7eb;background:#fef2f2;font-weight:600;color:#dc2626;">Reason</td>
                <td style="padding:8px 12px;border:1px solid #e5e7eb;color:#4b5563;">{{ $reason }}</td>
            </tr>
        </table>
    @endif

    <p>If you believe this was done in error, please contact our support team.</p>

    <hr class="divider">

    <p class="sub-text">Thank you for being part of Support Sphere.</p>
@endsection
