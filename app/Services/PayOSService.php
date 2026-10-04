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
            'orderCode' => $orderCode,
            'amount' => $amount,
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

        // Debug
        \Log::info('PayOS Request:', $payload);

        $response = Http::withHeaders([
            'x-client-id' => $this->clientId,
            'x-api-key' => $this->apiKey,
        ])->post($this->baseUrl . '/v2/payment-requests', $payload);

        $result = $response->json();

        // Debug
        \Log::info('PayOS Response:', $result);
        \Log::info('PayOS Status:', $response->status());

        return $result;
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
