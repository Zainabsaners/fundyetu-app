@extends('emails.layout')

@section('title', 'Your Support Ticket Has Been Replied - Support Sphere')

@section('content')
    <h1>Support Ticket Update</h1>

    <p>Hello {{ $user->name }},</p>

    <p>Your support ticket <strong>#{{ $ticket->id }}</strong> has received a response from our team.</p>

    <table style="width:100%;border-collapse:collapse;margin-bottom:20px;font-size:14px;">
        <tr>
            <td style="padding:8px 12px;border:1px solid #e5e7eb;background:#f9fafb;font-weight:600;color:#374151;">Subject</td>
            <td style="padding:8px 12px;border:1px solid #e5e7eb;color:#4b5563;">{{ $ticket->subject }}</td>
        </tr>
    </table>

    <div style="background:#f0fdf4;border:1px solid #bbf7d0;border-radius:8px;padding:16px;margin-bottom:20px;font-size:14px;color:#166534;">
        <p style="margin:0 0 8px;font-weight:600;">Admin Response:</p>
        <p style="margin:0;white-space:pre-wrap;">{{ $ticket->reply }}</p>
    </div>

    <div class="btn-wrapper">
        <a href="{{ $url }}" class="btn" style="display:inline-block;padding:13px 32px;font-size:14px;font-weight:600;text-decoration:none;border-radius:8px;color:#ffffff;background-color:#ce5f26;">View Response</a>
    </div>

    <hr class="divider">

    <p class="sub-text">If you have any further questions, feel free to reply to this ticket.</p>
@endsection
