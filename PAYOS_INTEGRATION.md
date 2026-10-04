# Hướng dẫn tích hợp PayOS

## 1. Cấu hình

Thêm vào `.env`:

```env
PAYOS_CLIENT_ID=your_client_id_here
PAYOS_API_KEY=your_api_key_here
PAYOS_CHECKSUM_KEY=your_checksum_key_here
PAYOS_RETURN_URL=http://your-domain.com/payos-callback
```

## 2. Service PayOS

File: `app/Services/PayOSService.php`

```php
<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PayOSService
{
    private $clientId;
    private $apiKey;
    private $checksumKey;
    private $baseUrl = 'https://api-merchant.payos.vn';

    public function __construct()
    {
        $this->clientId = env('PAYOS_CLIENT_ID', '');
        $this->apiKey = env('PAYOS_API_KEY', '');
        $this->checksumKey = env('PAYOS_CHECKSUM_KEY', '');
    }

    /**
     * Tạo liên kết thanh toán PayOS
     */
    public function createPaymentLink($orderCode, $amount, $orderDescription, $cancelUrl, $returnUrl)
    {
        $data = [
            'orderCode' => (int)$orderCode,
            'amount' => (int)$amount,
            'description' => $orderDescription,
            'cancelUrl' => $cancelUrl,
            'returnUrl' => $returnUrl,
        ];

        $signature = $this->generateSignature($data);

        $payload = [
            'orderCode' => (int)$orderCode,
            'amount' => (int)$amount,
            'description' => $orderDescription,
            'cancelUrl' => $cancelUrl,
            'returnUrl' => $returnUrl,
            'signature' => $signature,
        ];

        $response = Http::withHeaders([
            'x-client-id' => $this->clientId,
            'x-api-key' => $this->apiKey,
        ])->post($this->baseUrl . '/v2/payment-requests', $payload);

        return $response->json();
    }

    /**
     * Tạo signature cho PayOS
     */
    private function generateSignature($data)
    {
        ksort($data);
        $signature = '';
        foreach ($data as $key => $value) {
            $signature .= $key . '=' . $value . '&';
        }
        $signature = rtrim($signature, '&');
        $signature = hash_hmac('sha256', $signature, $this->checksumKey);
        return $signature;
    }

    /**
     * Kiểm tra trạng thái thanh toán
     */
    public function getPaymentStatus($orderCode)
    {
        $response = Http::withHeaders([
            'x-client-id' => $this->clientId,
            'x-api-key' => $this->apiKey,
        ])->get($this->baseUrl . '/v2/payment-requests/' . $orderCode);

        return $response->json();
    }
}
```

## 3. Controller

Thêm vào Controller (ví dụ `GioHangController.php`):

```php
use App\Services\PayOSService;

public function createPayOSPayment($orderId, $amount)
{
    $payOS = new PayOSService();
    
    $orderCode = 'DH' . time();
    $description = 'Thanh toán đơn hàng ' . $orderCode;
    $cancelUrl = url('/don-hang');
    $returnUrl = url('/payos-callback');
    
    $paymentLink = $payOS->createPaymentLink(
        $orderCode,
        $amount,
        $description,
        $cancelUrl,
        $returnUrl
    );
    
    if (isset($paymentLink['data']['checkoutUrl'])) {
        return redirect($paymentLink['data']['checkoutUrl']);
    }
    
    return back()->with('error', 'Không thể tạo liên kết thanh toán');
}

public function payOSCallback(Request $request)
{
    $orderCode = $request->input('orderCode');
    
    $payOS = new PayOSService();
    $paymentStatus = $payOS->getPaymentStatus($orderCode);
    
    if (isset($paymentStatus['data']['status']) && $paymentStatus['data']['status'] === 'PAID') {
        // Tạo đơn hàng ở đây
        // Cập nhật trạng thái đơn hàng thành 'da_xac_nhan'
        
        return redirect('/don-hang')->with('success', 'Thanh toán thành công!');
    }
    
    return redirect('/don-hang')->with('error', 'Thanh toán thất bại');
}
```

## 4. Routes

```php
Route::get('/payos-payment/{orderId}', [GioHangController::class, 'createPayOSPayment'])->name('payos-payment');
Route::get('/payos-callback', [GioHangController::class, 'payOSCallback'])->name('payos-callback');
```

## 5. Flow

1. User click thanh toán → gọi `createPayOSPayment()`
2. Tạo payment link qua PayOS API
3. Redirect đến PayOS checkout page
4. User thanh toán trên PayOS
5. PayOS redirect về `/payos-callback`
6. Kiểm tra trạng thái qua PayOS API
7. Nếu PAID → tạo đơn hàng

## 6. Lưu ý

- OrderCode phải là số (int)
- Amount phải là số (int)
- Signature phải đúng format theo docs PayOS
- Credentials lấy từ: https://payos.vn/
