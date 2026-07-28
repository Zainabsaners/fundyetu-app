<?php

namespace App\Policies;

use App\Models\Campaign;
use App\Models\User;

class CampaignPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Campaign $campaign): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Campaign $campaign): bool
    {
        if ($user->hasPermissionTo('manage campaigns')) {
            return true;
        }

        return (int) $user->id === (int) $campaign->user_id && $campaign->status->value !== 'active';
    }

    public function delete(User $user, Campaign $campaign): bool
    {
        if ($user->hasPermissionTo('manage campaigns')) {
            return true;
        }

        return (int) $user->id === (int) $campaign->user_id && $campaign->status->value === 'draft';
    }

    public function approveWithdrawal(User $user, Campaign $campaign): bool
    {
        return $campaign->treasurers()
            ->where('user_id', $user->id)
            ->where('can_approve_withdrawal', true)
            ->exists();
    }
}
