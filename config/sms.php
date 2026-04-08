<?php

return [
    'mnotify' => [
        'base_url' => env('MNOTIFY_BASE_URL', 'https://apps.mnotify.net/smsapi'),
        'api_key' => env('MNOTIFY_API_KEY'),
    ],
    'default_cost' => env('DEFAULT_SMS_COST', 0.05),
];
