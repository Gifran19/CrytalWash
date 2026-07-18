<?php

class MidtransService {
    private static function getBaseUrl() {
        $isProduction = filter_var(getenv('MIDTRANS_IS_PRODUCTION') ?: 'false', FILTER_VALIDATE_BOOLEAN);
        return $isProduction ? 'https://api.midtrans.com' : 'https://api.sandbox.midtrans.com';
    }

    public static function createQrisCharge($id_booking, $total_amount) {
        $serverKey = getenv('MIDTRANS_SERVER_KEY');
        if (!$serverKey) {
            error_log('MIDTRANS_SERVER_KEY belum dikonfigurasi.');
            throw new RuntimeException('Layanan pembayaran belum tersedia.');
        }

        // Generate unique order ID
        $orderId = 'CW-' . $id_booking . '-' . time();

        $payload = [
            'payment_type' => 'qris',
            'transaction_details' => [
                'order_id' => $orderId,
                'gross_amount' => (int)$total_amount
            ]
        ];

        $url = self::getBaseUrl() . '/v2/charge';
        $authHeader = 'Basic ' . base64_encode($serverKey . ':');

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Accept: application/json',
            'Content-Type: application/json',
            'Authorization: ' . $authHeader
        ]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
        curl_setopt($ch, CURLOPT_TIMEOUT, 15);

        $response = curl_exec($ch);
        $curlErr = curl_error($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($curlErr) {
            error_log('Midtrans cURL Error: ' . $curlErr);
            return ['success' => false, 'message' => 'Gagal menghubungi server pembayaran.'];
        }

        if ($httpCode >= 400) {
            error_log('Midtrans HTTP Error Status: ' . $httpCode . ' Response: ' . $response);
            return ['success' => false, 'message' => 'Respon error dari server pembayaran.'];
        }

        $result = json_decode($response, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            error_log('Midtrans JSON Decode Error: ' . json_last_error_msg());
            return ['success' => false, 'message' => 'Respon format tidak valid dari server pembayaran.'];
        }

        if (isset($result['actions'])) {
            foreach ($result['actions'] as $action) {
                if ($action['name'] === 'generate-qr-code') {
                    return [
                        'success' => true,
                        'qr_url' => $action['url'],
                        'order_id' => $orderId
                    ];
                }
            }
        }

        error_log('Midtrans Generate QR Action not found. Response: ' . $response);
        return ['success' => false, 'message' => 'Gagal mendapatkan QR Code pembayaran.'];
    }

    public static function checkPaymentStatus($orderId) {
        $serverKey = getenv('MIDTRANS_SERVER_KEY');
        if (!$serverKey) {
            return ['success' => false, 'message' => 'Layanan pembayaran belum siap.'];
        }

        $url = self::getBaseUrl() . '/v2/' . urlencode($orderId) . '/status';
        $authHeader = 'Basic ' . base64_encode($serverKey . ':');

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Accept: application/json',
            'Authorization: ' . $authHeader
        ]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 15);

        $response = curl_exec($ch);
        $curlErr = curl_error($ch);
        curl_close($ch);

        if ($curlErr) {
            error_log('Midtrans Status cURL Error: ' . $curlErr);
            return ['success' => false, 'message' => 'Gagal memverifikasi status pembayaran.'];
        }

        $result = json_decode($response, true);
        return [
            'success' => true,
            'transaction_status' => $result['transaction_status'] ?? '',
            'fraud_status' => $result['fraud_status'] ?? '',
            'status_code' => $result['status_code'] ?? ''
        ];
    }
}
