<?php

return [

    // Cache driver mặc định
    'default' => env('CACHE_DRIVER', 'array'),

    // Các cache stores
    'stores' => [
        'array' => [
            'driver' => 'array',
        ],
        'file' => [
            'driver' => 'file',
            'path' => storage_path('framework/cache/data'),
        ],
    ],

    // Cache key prefix
    'prefix' => env('CACHE_PREFIX', 'laravel_cache'),
];
