<?php

namespace App\Models;

use App\Enums\CampaignStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Campaign extends Model implements HasMedia
{
    use InteractsWithMedia, SoftDeletes;

    protected $fillable = [
        'user_id',
        'category_id',
        'title',
        'slug',
        'story',
        'target_amount',
        'raised_amount',
        'status',
        'expiry_date',
        'video_url',
        'platform_fee_percent',
        'verified_at',
        'is_treasurer_controlled',
        'treasurer_name',
        'treasurer_phone',
        'treasurer_id_number',
        'location',
        'rejection_reason',
    ];

    protected function casts(): array
    {
        return [
            'target_amount' => 'decimal:2',
            'raised_amount' => 'decimal:2',
            'expiry_date' => 'datetime',
            'verified_at' => 'datetime',
            'platform_fee_percent' => 'decimal:2',
            'is_treasurer_controlled' => 'boolean',
            'status' => CampaignStatus::class,
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function donations(): HasMany
    {
        return $this->hasMany(Donation::class);
    }

    public function withdrawals(): HasMany
    {
        return $this->hasMany(Withdrawal::class);
    }

    public function documents(): HasMany
    {
        return $this->hasMany(CampaignDocument::class);
    }

    public function treasurers(): HasMany
    {
        return $this->hasMany(CampaignTreasurer::class);
    }

    public function patrons(): HasMany
    {
        return $this->hasMany(CampaignPatron::class);
    }

    public function donorCount(): int
    {
        return $this->donations()
            ->where('status', 'completed')
            ->whereNotNull('donor_phone')
            ->where('donor_phone', '!=', '')
            ->distinct('donor_phone')
            ->count('donor_phone');
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(CampaignComment::class);
    }

    public function isActive(): bool
    {
        return $this->status === CampaignStatus::Active;
    }

    public function isVerified(): bool
    {
        return $this->verified_at !== null;
    }

    public function progressPercent(): float
    {
        if ($this->target_amount <= 0) {
            return 0;
        }

        return min(100, round(($this->raised_amount / $this->target_amount) * 100, 2));
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('cover_image')
            ->singleFile();

        $this->addMediaCollection('gallery');

        $this->addMediaCollection('video')
            ->singleFile();
    }
}
