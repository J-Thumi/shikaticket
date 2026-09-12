<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Resend, Postmark, AWS, and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'postmark' => [
        'key' => env('POSTMARK_API_KEY'),
    ],

    'bitika' => [
        'base_url' => env(
            'BITIKA_BASE_URL',
            'https://bitikaserver.up.railway.app'
        ),

        'api_key' => env('BITIKA_API_KEY'),

        'fixed_amount' => env('BITIKA_FIXED_AMOUNT', 500),

        'lightning_address' => env(
            'BITIKA_LIGHTNING_ADDRESS'
        ),

        'webhook_secret' => env(
            'BITIKA_WEBHOOK_SECRET'
        ),
    ],
    
    'resend' => [
        'key' => env('RESEND_API_KEY'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

];
