<?php
// Lấy 3 key này tại: https://my.payos.vn → Kênh thanh toán → Cài đặt
return [
    'client_id'    => env('PAYOS_CLIENT_ID', '71d2d762-285b-4c4c-bd55-640a367be9a0'),
    'api_key'      => env('PAYOS_API_KEY', '7bf2321f-ea23-4d77-812a-9bb7b4e0e7c4'),
    'checksum_key' => env('PAYOS_CHECKSUM_KEY', '76e5aea6530dfca15c2a23e23d482cee792fe5653c128bc560725857c511fd3d'),
    'base_url'     => 'https://api-merchant.payos.vn',
    'return_url'   => 'http://localhost:8000/payos/success',
    'cancel_url'   => 'http://localhost:8000/payos/cancel',
];
