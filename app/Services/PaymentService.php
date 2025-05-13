<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;


class PaymentService
{
    protected string $paymentGatewayUrl;
    protected string $apiKey;

    public function __construct()
    {
        $this->paymentGatewayUrl = config('services.payment.url');
        $this->apiKey = config('services.payment.key');
    }


    public function processPayment(Order $order, User $user, array $paymentData): Payment
    {
        try {
            $this->validatePaymentData($paymentData);

           
            $response = $this->sendPaymentRequest([
                'amount' => $order->total,
                'currency' => 'EGP',
                'customer_email' => $user->email,
                'order_id' => $order->id,
                'payment_method' => $paymentData['method']
            ]);

            $payment = $this->createPaymentRecord($order, $user, $response);

            if ($response['success']) {
                $order->update(['payment_status' => 'paid']);
            }

            return $payment;

        } catch (\Exception $e) {
            Log::error('Payment failed: ' . $e->getMessage());
            throw $e;
        }
    }

    protected function sendPaymentRequest(array $data): array
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->apiKey,
            'Accept' => 'application/json'
        ])->post($this->paymentGatewayUrl, $data);

        return $response->json();
    }

    protected function createPaymentRecord(Order $order, User $user, array $response): Payment
    {
        return Payment::create([
            'order_id' => $order->id,
            'user_id' => $user->id,
            'amount' => $order->total,
            'gateway' => $response['gateway'],
            'transaction_id' => $response['transaction_id'],
            'status' => $response['success'] ? 'completed' : 'failed',
            'metadata' => json_encode($response)
        ]);
    }


    protected function validatePaymentData(array $data): void
    {
        $validMethods = ['credit_card', 'vodafone_cash', 'paypal'];

        if (!in_array($data['method'], $validMethods)) {
            throw new \InvalidArgumentException('not valid payment method');
        }

        if ($data['method'] === 'credit_card' && empty($data['card_token'])) {
            throw new \InvalidArgumentException('card data is invalid');
        }
    }


    public function refundPayment(Payment $payment): bool
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->apiKey
        ])->post($this->paymentGatewayUrl . '/refund', [
            'transaction_id' => $payment->transaction_id
        ]);

        if ($response['success']) {
            $payment->update(['status' => 'refunded']);
            return true;
        }

        return false;
    }
}