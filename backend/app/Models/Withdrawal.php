<?php

namespace App\Models;

use App\Enums\WithdrawalStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Withdrawal extends Model
{
    protected $fillable = [
        'campaign_id',
        'requested_by',
        'amount',
        'fee',
        'platform_fee',
        'sms_charge',
        'net_amount',
        'destination_type',
        'destination_ref',
        'status',
        'notes',
        'evidence',
        'rejection_reason',
        'disbursed_at',
        'rejected_at',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'fee' => 'decimal:2',
            'platform_fee' => 'decimal:2',
            'sms_charge' => 'decimal:2',
            'net_amount' => 'decimal:2',
            'disbursed_at' => 'datetime',
            'rejected_at' => 'datetime',
            'status' => WithdrawalStatus::class,
        ];
    }

    public function campaign(): BelongsTo
    {
        return $this->belongsTo(Campaign::class);
    }

    public function requester(): BelongsTo
    {
        return $this->belongsTo(User::class, 'requested_by');
    }

    public function approvals(): HasMany
    {
        return $this->hasMany(WithdrawalApproval::class);
    }
}
