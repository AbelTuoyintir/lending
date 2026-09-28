<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use RuntimeException;

class PaystackService
{
    protected ?string $secretKey;

    protected string $baseUrl;

    public function __construct()
    {
        $this->secretKey = config('services.paystack.secret_key');
        $this->baseUrl = config('services.paystack.payment_url', 'https://api.paystack.co');
    }

    /**
     * Initialize a Paystack transaction.
     *
     * @param  array{email: string, amount: float|int, reference: string, callback_url: string, metadata?: array}  $data
     * @return array{status: bool, authorization_url?: string, reference: string, message?: string}
     */
    public function initializeTransaction(array $data): array
    {
        $amountInSubunits = (int) round($data['amount'] * 100);

        if (! $this->secretKey || $this->secretKey === 'sk_test_placeholder') {
            return [
                'status' => true,
                'authorization_url' => $data['callback_url'].'?reference='.urlencode($data['reference']).'&status=success',
                'reference' => $data['reference'],
                'message' => 'Paystack transaction initialized (sandbox mode)',
            ];
        }

        $response = Http::withToken($this->secretKey)
            ->acceptJson()
            ->post("{$this->baseUrl}/transaction/initialize", [
                'email' => $data['email'],
                'amount' => $amountInSubunits,
                'currency' => 'GHS',
                'reference' => $data['reference'],
                'callback_url' => $data['callback_url'],
                'metadata' => $data['metadata'] ?? [],
            ]);

        if ($response->failed()) {
            Log::error('Paystack initialization failed', [
                'body' => $response->body(),
                'status' => $response->status(),
            ]);

            throw new RuntimeException('Unable to initialize Paystack payment gateway: '.$response->json('message', 'API request failed'));
        }

        $resData = $response->json();

        return [
            'status' => $resData['status'] ?? false,
            'authorization_url' => $resData['data']['authorization_url'] ?? null,
            'reference' => $data['reference'],
            'message' => $resData['message'] ?? 'Initialization complete',
        ];
    }

    /**
     * Verify a Paystack transaction by reference.
     *
     * @return array{status: bool, amount: float, reference: string, customer_email?: string, metadata?: array}
     */
    public function verifyTransaction(string $reference): array
    {
        if (! $this->secretKey || $this->secretKey === 'sk_test_placeholder') {
            return [
                'status' => true,
                'reference' => $reference,
                'message' => 'Verification successful (sandbox mode)',
            ];
        }

        $response = Http::withToken($this->secretKey)
            ->acceptJson()
            ->get("{$this->baseUrl}/transaction/verify/".urlencode($reference));

        if ($response->failed()) {
            Log::error('Paystack transaction verification failed', [
                'reference' => $reference,
                'body' => $response->body(),
            ]);

            throw new RuntimeException('Paystack payment verification failed: '.$response->json('message', 'Verification endpoint error'));
        }

        $resData = $response->json();

        if (! ($resData['status'] ?? false) || ($resData['data']['status'] ?? '') !== 'success') {
            return [
                'status' => false,
                'reference' => $reference,
                'message' => $resData['data']['gateway_response'] ?? 'Payment was not successful',
            ];
        }

        $paidAmount = ($resData['data']['amount'] ?? 0) / 100;

        return [
            'status' => true,
            'amount' => $paidAmount,
            'reference' => $reference,
            'customer_email' => $resData['data']['customer']['email'] ?? null,
            'metadata' => $resData['data']['metadata'] ?? [],
            'message' => 'Payment verified successfully',
        ];
    }
}
