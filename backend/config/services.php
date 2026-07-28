<?php

return [

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'resend' => [
        'key' => env('RESEND_KEY'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    'mpesa' => [
        'consumer_key' => env('MPESA_CONSUMER_KEY'),
        'consumer_secret' => env('MPESA_CONSUMER_SECRET'),
        'passkey' => env('MPESA_PASSKEY'),
        'shortcode' => env('MPESA_SHORTCODE'),
        'environment' => env('MPESA_ENVIRONMENT', 'sandbox'),
        'callback_url' => env('MPESA_CALLBACK_URL'),
        'timeout_url' => env('MPESA_TIMEOUT_URL'),
        'result_url' => env('MPESA_RESULT_URL'),
        'initiator_name' => env('MPESA_INITIATOR_NAME'),
        'security_credential' => env('MPESA_SECURITY_CREDENTIAL'),
    ],

    'airtel' => [
        'client_id' => env('AIRTEL_CLIENT_ID'),
        'client_secret' => env('AIRTEL_CLIENT_SECRET'),
        'environment' => env('AIRTEL_ENVIRONMENT', 'sandbox'),
    ],

    'flutterwave' => [
        'secret_key' => env('FLW_SECRET_KEY'),
        'public_key' => env('FLW_PUBLIC_KEY'),
        'environment' => env('FLW_ENVIRONMENT', 'sandbox'),
    ],

    'africastalking' => [
        'username' => env('AT_USERNAME'),
        'api_key' => env('AT_API_KEY'),
        'from' => env('AT_FROM', 'Support Sphere'),
    ],

];
