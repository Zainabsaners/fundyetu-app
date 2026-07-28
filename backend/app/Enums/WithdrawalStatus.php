<?php

namespace App\Enums;

enum WithdrawalStatus: string
{
    case Pending = 'pending';
    case TreasurerApproved = 'treasurer_approved';
    case AdminApproved = 'admin_approved';
    case Disbursed = 'disbursed';
    case Rejected = 'rejected';
}
