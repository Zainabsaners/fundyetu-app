<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentGatewayLog extends Model
{
    protected $fillable = [
        'gateway',
        'endpoint',
        'request_payload',
        'response_payload',
        'transaction_id',
        'status',
    ];
}
