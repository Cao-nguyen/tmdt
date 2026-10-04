<?php

return [

    // View paths
    'paths' => [
        resource_path('views'),
    ],

    // Compiled view path
    'compiled' => env(
        'VIEW_COMPILED_PATH',
        realpath(storage_path('framework/views'))
    ),
];
