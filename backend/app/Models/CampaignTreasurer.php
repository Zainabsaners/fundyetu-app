<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CampaignTreasurer extends Model
{
    protected $fillable = [
        'campaign_id',
        'user_id',
        'can_approve_withdrawal',
    ];

    protected function casts(): array
    {
        return [
            'can_approve_withdrawal' => 'boolean',
        ];
    }

    public function campaign(): BelongsTo
    {
        return $this->belongsTo(Campaign::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
