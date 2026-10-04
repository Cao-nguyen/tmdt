<?php

return [

    // Default log channel
    'default' => env('LOG_CHANNEL', 'daily'),

    // Log channels
    'channels' => [
        'daily' => [
            'driver' => 'daily',
            'path' => storage_path('logs/laravel.log'),
            'level' => env('LOG_LEVEL', 'debug'),
            'days' => 14,
        ],
    ],
];
