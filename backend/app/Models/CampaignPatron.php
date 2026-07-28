<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CampaignPatron extends Model
{
    protected $fillable = [
        'campaign_id',
        'name',
        'photo',
        'message',
        'sort_order',
    ];

    public function campaign(): BelongsTo
    {
        return $this->belongsTo(Campaign::class);
    }
}
