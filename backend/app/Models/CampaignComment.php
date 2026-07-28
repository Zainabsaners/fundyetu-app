<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CampaignComment extends Model
{
    protected $fillable = [
        'campaign_id',
        'user_id',
        'parent_id',
        'body',
    ];

    public function campaign(): BelongsTo
    {
        return $this->belongsTo(Campaign::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function replies(): HasMany
    {
        return $this->hasMany(CampaignComment::class, 'parent_id');
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(CampaignComment::class, 'parent_id');
    }
}
