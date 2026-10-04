<?php

class PayOS
{
    private string $clientId;
    private string $apiKey;
    private string $checksumKey;
    private string $baseUrl;

    public function __construct(array $config)
    {
        $this->clientId    = $config['client_id'];
        $this->apiKey      = $config['api_key'];
        $this->checksumKey = $config['checksum_key'];
        $this->baseUrl     = rtrim($config['base_url'], '/');
    }

    /** Tạo link thanh toán */
    public function createPaymentLink(array $order): array
    {
        $data = [
            'orderCode'   => (int)$order['orderCode'],   // số nguyên, duy nhất
            'amount'      => (int)$order['amount'],       // VND
            'description' => mb_substr($order['description'], 0, 25), // tối đa 25 ký tự
            'returnUrl'   => $order['returnUrl'],
            'cancelUrl'   => $order['cancelUrl'],
        ];

        // Signature: các field sắp xếp theo alphabet
        $signData = "amount={$data['amount']}"
            . "&cancelUrl={$data['cancelUrl']}"
            . "&description={$data['description']}"
            . "&orderCode={$data['orderCode']}"
            . "&returnUrl={$data['returnUrl']}";
        $data['signature'] = hash_hmac('sha256', $signData, $this->checksumKey);

        // Tuỳ chọn thêm
        foreach (['buyerName', 'buyerEmail', 'buyerPhone', 'buyerAddress', 'items', 'expiredAt'] as $k) {
            if (isset($order[$k])) $data[$k] = $order[$k];
        }

        return $this->request('POST', '/v2/payment-requests', $data);
    }

    /** Lấy thông tin đơn (theo orderCode hoặc paymentLinkId) */
    public function getPaymentInfo($id): array
    {
        return $this->request('GET', '/v2/payment-requests/' . $id);
    }

    /** Huỷ link thanh toán */
    public function cancelPayment($id, ?string $reason = null): array
    {
        $body = $reason ? ['cancellationReason' => $reason] : new stdClass();
        return $this->request('POST', '/v2/payment-requests/' . $id . '/cancel', $body);
    }

    /** Đăng ký webhook URL với payOS (chạy 1 lần) */
    public function confirmWebhook(string $webhookUrl): array
    {
        return $this->request('POST', '/confirm-webhook', ['webhookUrl' => $webhookUrl]);
    }

    /** Xác thực chữ ký webhook */
    public function verifyWebhook(array $payload): bool
    {
        if (empty($payload['data']) || empty($payload['signature'])) return false;

        $data = $payload['data'];
        ksort($data);
        $parts = [];
        foreach ($data as $k => $v) {
            if ($v === null || $v === 'undefined' || $v === 'null') $v = '';
            if (is_bool($v)) $v = $v ? 'true' : 'false';
            if (is_array($v)) $v = json_encode($v, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
            $parts[] = $k . '=' . $v;
        }
        $expected = hash_hmac('sha256', implode('&', $parts), $this->checksumKey);

        return hash_equals($expected, $payload['signature']);
    }

    private function request(string $method, string $path, $body = null): array
    {
        $ch = curl_init($this->baseUrl . $path);
        $headers = [
            'Content-Type: application/json',
            'x-client-id: ' . $this->clientId,
            'x-api-key: '   . $this->apiKey,
        ];
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST  => $method,
            CURLOPT_HTTPHEADER     => $headers,
            CURLOPT_TIMEOUT        => 30,
        ]);
        if ($method === 'POST') {
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($body, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
        }

        $res = curl_exec($ch);
        if ($res === false) {
            $err = curl_error($ch);
            curl_close($ch);
            throw new RuntimeException('cURL error: ' . $err);
        }
        curl_close($ch);

        $json = json_decode($res, true);
        if (!is_array($json)) throw new RuntimeException('Invalid response: ' . $res);
        if (($json['code'] ?? '') !== '00') {
            throw new RuntimeException('payOS error: ' . ($json['desc'] ?? 'unknown') . ' (' . ($json['code'] ?? '') . ')');
        }
        return $json['data'] ?? [];
    }
}
