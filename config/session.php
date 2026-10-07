<?php

return [

    // Session driver mặc định
    'driver' => env('SESSION_DRIVER', 'file'),

    // Session lifetime (phút)
    'lifetime' => env('SESSION_LIFETIME', 4320),

    // Expire on close
    'expire_on_close' => false,

    // Session encryption
    'encrypt' => false,

    // Session files path
    'files' => storage_path('framework/sessions'),

    // Session database connection
    'connection' => null,

    // Session table
    'table' => 'sessions',

    // Session cache store
    'store' => null,

    // Session lotteries
    'lottery' => [2, 100],

    // Session cookie name
    'cookie' => env('SESSION_COOKIE', 'laravel_session'),

    // Session cookie path
    'path' => '/',

    // Session cookie domain
    'domain' => env('SESSION_DOMAIN'),

    // Session cookie secure
    'secure' => env('SESSION_SECURE_COOKIE'),

    // Session cookie HTTP only
    'http_only' => true,

    // Same-site cookies
    'same_site' => 'lax',
];
