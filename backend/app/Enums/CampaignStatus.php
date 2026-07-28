<?php

namespace App\Enums;

enum CampaignStatus: string
{
    case Draft = 'draft';
    case PendingVerification = 'pending_verification';
    case Active = 'active';
    case Paused = 'paused';
    case Completed = 'completed';
    case Cancelled = 'cancelled';
    case Closed = 'closed';
}
