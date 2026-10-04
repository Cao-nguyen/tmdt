<?php

use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Đăng ký autoload Composer
require __DIR__.'/../vendor/autoload.php';

// Bootstrap Laravel application
$app = require_once __DIR__.'/../bootstrap/app.php';

// Chạy ứng dụng
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$response = $kernel->handle(
    $request = Request::capture()
);

$response->send();

$kernel->terminate($request, $response);
