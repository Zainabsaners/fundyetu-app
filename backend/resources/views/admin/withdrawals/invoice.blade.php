<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice — Withdrawal #{{ $withdrawal->id }} — Support Sphere</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
            font-size: 13px;
            color: #1e293b;
            padding: 40px;
            background: #f8fafc;
        }
        .invoice-wrapper {
            max-width: 800px;
            margin: 0 auto;
            background: #fff;
            box-shadow: 0 1px 3px rgba(0,0,0,.08);
            overflow: hidden;
        }
        .invoice-header {
            background: #dddfe4;
            color: #1e293b;
            padding: 32px 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .invoice-header .logo-img {
            height: 42px;
            width: auto;
            display: block;
        }
        .invoice-header .badge {
            background: rgba(255,255,255,.2);
            padding: 6px 16px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: .5px;
        }
        .invoice-body { padding: 40px; }
        .meta-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 24px;
            margin-bottom: 32px;
        }
        .meta-group h3 {
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #94a3b8;
            margin-bottom: 6px;
        }
        .meta-group p {
            font-size: 14px;
            font-weight: 600;
            color: #1e293b;
        }
        .meta-group .sub {
            font-size: 12px;
            font-weight: 400;
            color: #64748b;
        }
        .divider { border: none; border-top: 1px solid #e2e8f0; margin: 24px 0; }
        .breakdown-table { width: 100%; border-collapse: collapse; margin-bottom: 24px; }
        .breakdown-table td {
            padding: 10px 16px;
            border: 1px solid #e2e8f0;
            font-size: 13px;
        }
        .breakdown-table .label-cell {
            font-weight: 600;
            color: #475569;
            width: 60%;
        }
        .breakdown-table .value-cell {
            text-align: right;
            font-weight: 700;
            width: 40%;
        }
        .breakdown-table .gross .value-cell { color: #CE5F26; }
        .breakdown-table .fee .value-cell { color: #dc2626; }
        .breakdown-table .net .value-cell { color: #059669; font-size: 15px; }
        .breakdown-table .total-label { font-weight: 700; color: #1e293b; }
        .details-table { width: 100%; border-collapse: collapse; }
        .details-table th,
        .details-table td {
            padding: 10px 12px;
            border: 1px solid #e2e8f0;
            text-align: left;
            font-size: 12px;
        }
        .details-table th {
            background: #f8fafc;
            font-weight: 600;
            color: #64748b;
            text-transform: uppercase;
            font-size: 10px;
            letter-spacing: .5px;
        }
        .footer {
            text-align: center;
            padding: 24px 40px;
            border-top: 1px solid #e2e8f0;
            font-size: 11px;
            color: #94a3b8;
        }
        .print-btn {
            display: block;
            width: fit-content;
            margin: 24px auto 0;
            padding: 10px 32px;
            background: #CE5F26;
            color: #fff;
            border: none;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
        }
        .print-btn:hover { background: #B04E1E; }
        @media print {
            body { background: #fff; padding: 0; }
            .invoice-wrapper { box-shadow: none; }
            .print-btn { display: none; }
        }
    </style>
</head>
<body>
    <div class="invoice-wrapper">
        <div class="invoice-header">
            <div>
                <img src="{{ asset('assets/images/logo/logo.png') }}" alt="Support Sphere" class="logo-img">
                <p style="font-size:12px;opacity:.8;margin-top:4px;">Withdrawal Invoice</p>
            </div>
            <div class="badge">{{ str_replace('_', ' ', ucfirst($withdrawal->status->value)) }}</div>
        </div>

        <div class="invoice-body">
            <div class="meta-grid">
                <div class="meta-group">
                    <h3>Withdrawal</h3>
                    <p class="sub">Requested {{ $withdrawal->created_at->format('M j, Y \a\t H:i') }}</p>
                </div>
                <div class="meta-group">
                    <h3>Campaign</h3>
                    <p>{{ $withdrawal->campaign->title }}</p>
                    <p class="sub">by {{ $withdrawal->campaign->user->name ?? 'Unknown' }}</p>
                </div>
            </div>

            <table class="breakdown-table">
                <tr class="gross">
                    <td class="label-cell">Gross Amount</td>
                    <td class="value-cell">KES {{ number_format($withdrawal->amount, 2) }}</td>
                </tr>
                <tr class="fee">
                    <td class="label-cell">Platform Fee ({{ $withdrawal->campaign->platform_fee_percent ?? 0 }}%)</td>
                    <td class="value-cell">— KES {{ number_format($withdrawal->platform_fee ?? 0, 2) }}</td>
                </tr>
                <tr class="fee">
                    <td class="label-cell">SMS Charge</td>
                    <td class="value-cell">— KES {{ number_format($withdrawal->sms_charge ?? 0, 2) }}</td>
                </tr>
                <tr class="fee">
                    <td class="label-cell">Withdrawal Fee</td>
                    <td class="value-cell">— KES {{ number_format($withdrawal->fee ?? 0, 2) }}</td>
                </tr>
                <tr class="net">
                    <td class="label-cell total-label">Net Amount</td>
                    <td class="value-cell">KES {{ number_format($withdrawal->net_amount ?? $withdrawal->amount, 2) }}</td>
                </tr>
            </table>

            <table class="details-table">
                <tr>
                    <th>Destination</th>
                    <td colspan="3">{{ strtoupper($withdrawal->destination_type) }} — {{ $withdrawal->destination_ref }}</td>
                </tr>
                <tr>
                    <th style="width:25%">Disbursed At</th>
                    <td style="width:25%">{{ $withdrawal->disbursed_at ? $withdrawal->disbursed_at->format('M j, Y H:i') : ($withdrawal->rejected_at ? $withdrawal->rejected_at->format('M j, Y H:i') : '—') }}</td>
                    <th style="width:25%">Status</th>
                    <td style="width:25%">{{ str_replace('_', ' ', ucfirst($withdrawal->status->value)) }}</td>
                </tr>
                @if($withdrawal->status->value === 'rejected' && $withdrawal->rejected_at)
                <tr>
                    <th>Rejected At</th>
                    <td>{{ $withdrawal->rejected_at->format('M j, Y H:i') }}</td>
                    <th></th>
                    <td></td>
                </tr>
                @endif
                @if($withdrawal->rejection_reason)
                <tr>
                    <th>Rejection Reason</th>
                    <td colspan="3" style="color:#dc2626;">{{ $withdrawal->rejection_reason }}</td>
                </tr>
                @endif
                @if($withdrawal->notes)
                <tr>
                    <th>Notes</th>
                    <td colspan="3">{{ $withdrawal->notes }}</td>
                </tr>
                @endif
                @if($withdrawal->approvals->count())
                <tr>
                    <th>Approvals</th>
                    <td colspan="3">
                        @foreach($withdrawal->approvals as $approval)
                            <div style="margin-bottom:4px;">
                                <strong>{{ $approval->treasurer->name ?? 'Treasurer' }}</strong>
                                — {{ $approval->approved_at ? $approval->approved_at->format('M j, Y H:i') : 'N/A' }}
                                @if($approval->notes)
                                    <span style="color:#64748b;">({{ $approval->notes }})</span>
                                @endif
                            </div>
                        @endforeach
                    </td>
                </tr>
                @endif
            </table>

            <button class="print-btn" onclick="window.print()">Print / Download PDF</button>
        </div>

        <div class="footer">
            Support Sphere &mdash; Generated {{ now()->format('M j, Y H:i') }}
        </div>
    </div>
</body>
</html>