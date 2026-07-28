<?php

return [
    'api_key' => env('TEXTSMS_API_KEY'),
    'partner_id' => env('TEXTSMS_PARTNER_ID'),
    'shortcode' => env('TEXTSMS_SHORTCODE', env('TEXTSMS_SENDER_ID', 'Support Sphere')),
    'endpoint' => env('TEXTSMS_ENDPOINT', 'https://sms.textsms.co.ke/api/services/sendsms/'),
];
