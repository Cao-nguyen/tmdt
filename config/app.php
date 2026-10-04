<?php

return [

    // Tên ứng dụng
    'name' => env('APP_NAME', 'Laravel'),

    // Môi trường chạy
    'env' => env('APP_ENV', 'production'),

    // Debug mode
    'debug' => (bool) env('APP_DEBUG', false),

    // URL của ứng dụng
    'url' => env('APP_URL', 'http://localhost'),

    // Asset URL
    'asset_url' => env('ASSET_URL'),

    // Timezone
    'timezone' => 'Asia/Ho_Chi_Minh',

    // Locale (ngôn ngữ)
    'locale' => 'vi',

    // Fallback locale
    'fallback_locale' => 'en',

    // Key mã hóa
    'key' => env('APP_KEY'),

    // Cipher
    'cipher' => 'AES-256-CBC',

    // Maintenance mode
    'maintenance' => [
        'driver' => env('APP_MAINTENANCE_DRIVER', 'file'),
        'store' => env('APP_MAINTENANCE_STORE'),
    ],

    // Service Providers
    'providers' => [
        // Laravel Framework Service Providers...
        Illuminate\Auth\AuthServiceProvider::class,
        Illuminate\Broadcasting\BroadcastServiceProvider::class,
        Illuminate\Bus\BusServiceProvider::class,
        Illuminate\Cache\CacheServiceProvider::class,
        Illuminate\Foundation\Providers\ConsoleSupportServiceProvider::class,
        Illuminate\Cookie\CookieServiceProvider::class,
        Illuminate\Database\DatabaseServiceProvider::class,
        Illuminate\Encryption\EncryptionServiceProvider::class,
        Illuminate\Filesystem\FilesystemServiceProvider::class,
        Illuminate\Foundation\Providers\FoundationServiceProvider::class,
        Illuminate\Hashing\HashServiceProvider::class,
        Illuminate\Mail\MailServiceProvider::class,
        Illuminate\Notifications\NotificationServiceProvider::class,
        Illuminate\Pagination\PaginationServiceProvider::class,
        Illuminate\Pipeline\PipelineServiceProvider::class,
        Illuminate\Queue\QueueServiceProvider::class,
        Illuminate\Redis\RedisServiceProvider::class,
        Illuminate\Auth\Passwords\PasswordResetServiceProvider::class,
        Illuminate\Session\SessionServiceProvider::class,
        Illuminate\Translation\TranslationServiceProvider::class,
        Illuminate\Validation\ValidationServiceProvider::class,
        Illuminate\View\ViewServiceProvider::class,

        // Package Service Providers...
        // Laravel\Sanctum\SanctumServiceProvider::class, // Tắt Sanctum
        CloudinaryLabs\CloudinaryLaravel\CloudinaryServiceProvider::class,

        // App Service Providers...
        App\Providers\RouteServiceProvider::class,
    ],

    // Class Aliases
    'aliases' => [
        'Cloudinary' => CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary::class,
    ],
];
