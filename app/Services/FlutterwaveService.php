<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FlutterwaveService
{
    private string $secretKey;
    private string $baseUrl;

    public function __construct()
    {
        $this->secretKey = config('services.flutterwave.secret_key', '');
        $this->baseUrl   = config('services.flutterwave.base_url', 'https://api.flutterwave.com/v3');
    }

    /**
     * Create a hosted payment link and return the checkout URL.
     *
     * @param  array{tx_ref: string, amount: float, currency: string, customer: array, redirect_url: string, meta?: array, payment_options?: string}  $payload
     * @return array{link: string}
     * @throws \RuntimeException
     */
    public function initiatePayment(array $payload): array
    {
        $response = Http::withToken($this->secretKey)
            ->post("{$this->baseUrl}/payments", $payload);

        if (! $response->successful()) {
            Log::error('Flutterwave initiate payment failed', [
                'status'  => $response->status(),
                'body'    => $response->body(),
                'tx_ref'  => $payload['tx_ref'] ?? null,
            ]);
            throw new \RuntimeException('Could not initiate Flutterwave payment: ' . $response->json('message', 'Unknown error'));
        }

        return $response->json();
    }

    /**
     * Verify a completed transaction by its Flutterwave transaction ID.
     */
    public function verifyTransaction(string $transactionId): array
    {
        $response = Http::withToken($this->secretKey)
            ->get("{$this->baseUrl}/transactions/{$transactionId}/verify");

        if (! $response->successful()) {
            Log::error('Flutterwave verify transaction failed', [
                'transaction_id' => $transactionId,
                'status'         => $response->status(),
                'body'           => $response->body(),
            ]);
            throw new \RuntimeException('Could not verify Flutterwave transaction: ' . $response->json('message', 'Unknown error'));
        }

        return $response->json('data', []);
    }

    /**
     * Verify the webhook signature from the verif-hash header.
     * Flutterwave uses a plain-text secret hash (no HMAC — just direct comparison).
     */
    public function verifyWebhookSignature(string $header): bool
    {
        $secret = config('services.flutterwave.webhook_secret', '');
        return ! empty($secret) && hash_equals($secret, $header);
    }
}
