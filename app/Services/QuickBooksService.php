<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class QuickBooksService
{
    private string $clientId;
    private string $clientSecret;
    private string $redirectUri;
    private bool   $sandbox;
    private string $baseUrl;
    private string $authUrl;
    private string $tokenUrl;
    private string $discoveryUrl;

    public function __construct()
    {
        $this->clientId     = config('services.quickbooks.client_id') ?? '';
        $this->clientSecret = config('services.quickbooks.client_secret') ?? '';
        $this->redirectUri  = config('services.quickbooks.redirect_uri') ?? '';
        $this->sandbox      = (bool) config('services.quickbooks.sandbox', false);

        $this->baseUrl  = $this->sandbox
            ? 'https://sandbox-quickbooks.api.intuit.com/v3/company'
            : 'https://quickbooks.api.intuit.com/v3/company';

        $this->authUrl  = 'https://appcenter.intuit.com/connect/oauth2';
        $this->tokenUrl = 'https://oauth.platform.intuit.com/oauth2/v1/tokens/bearer';
    }

    // ── OAuth ─────────────────────────────────────────────────────────────────

    public function getAuthorizationUrl(string $state): string
    {
        return $this->authUrl . '?' . http_build_query([
            'client_id'     => $this->clientId,
            'redirect_uri'  => $this->redirectUri,
            'response_type' => 'code',
            'scope'         => 'com.intuit.quickbooks.accounting',
            'state'         => $state,
        ]);
    }

    public function exchangeCodeForTokens(string $code, string $realmId): array
    {
        $response = Http::withBasicAuth($this->clientId, $this->clientSecret)
            ->asForm()
            ->post($this->tokenUrl, [
                'grant_type'   => 'authorization_code',
                'code'         => $code,
                'redirect_uri' => $this->redirectUri,
            ]);

        if (! $response->successful()) {
            throw new \RuntimeException('QuickBooks token exchange failed: ' . $response->body());
        }

        $tokens = $response->json();
        $this->storeTokens($tokens, $realmId);
        return $tokens;
    }

    private function refreshAccessToken(): void
    {
        $tokens  = $this->getStoredTokens();
        $realmId = Cache::get('qb_realm_id', '');

        if (! $tokens || empty($tokens['refresh_token'])) {
            throw new \RuntimeException('QuickBooks not connected — no refresh token. Re-authorize from admin settings.');
        }

        $response = Http::withBasicAuth($this->clientId, $this->clientSecret)
            ->asForm()
            ->post($this->tokenUrl, [
                'grant_type'    => 'refresh_token',
                'refresh_token' => $tokens['refresh_token'],
            ]);

        if (! $response->successful()) {
            throw new \RuntimeException('QuickBooks token refresh failed: ' . $response->body());
        }

        $this->storeTokens($response->json(), $realmId);
    }

    private function storeTokens(array $tokens, string $realmId): void
    {
        Cache::put('qb_tokens', $tokens, now()->addSeconds($tokens['x_refresh_token_expires_in'] ?? 8726400));
        Cache::put('qb_realm_id', $realmId, now()->addSeconds($tokens['x_refresh_token_expires_in'] ?? 8726400));
    }

    private function getStoredTokens(): ?array
    {
        return Cache::get('qb_tokens');
    }

    public function isConnected(): bool
    {
        $tokens = $this->getStoredTokens();
        return ! empty($tokens['refresh_token']);
    }

    // ── API calls ─────────────────────────────────────────────────────────────

    private function request(string $method, string $endpoint, array $data = []): array
    {
        $tokens  = $this->getStoredTokens();
        $realmId = Cache::get('qb_realm_id', '');

        if (! $tokens || ! $realmId) {
            throw new \RuntimeException('QuickBooks not connected.');
        }

        $url = "{$this->baseUrl}/{$realmId}/{$endpoint}?minorversion=65";

        $response = Http::withToken($tokens['access_token'])
            ->accept('application/json')
            ->$method($url, $data);

        if ($response->status() === 401) {
            $this->refreshAccessToken();
            $tokens   = $this->getStoredTokens();
            $response = Http::withToken($tokens['access_token'])
                ->accept('application/json')
                ->$method($url, $data);
        }

        if (! $response->successful()) {
            Log::error("QuickBooks API error [{$method} {$endpoint}]", [
                'status' => $response->status(),
                'body'   => $response->body(),
            ]);
            throw new \RuntimeException("QuickBooks API error: {$response->status()}");
        }

        return $response->json();
    }

    /**
     * Find or create a QuickBooks Customer for the given user.
     * Returns the QB Customer ID.
     */
    public function findOrCreateCustomer(string $displayName, string $email): string
    {
        $query    = "SELECT * FROM Customer WHERE PrimaryEmailAddr = '{$email}' MAXRESULTS 1";
        $response = $this->request('get', 'query', ['query' => $query]);

        $customers = $response['QueryResponse']['Customer'] ?? [];

        if (! empty($customers)) {
            return (string) $customers[0]['Id'];
        }

        $created = $this->request('post', 'customer', [
            'DisplayName'      => $displayName,
            'PrimaryEmailAddr' => ['Address' => $email],
        ]);

        return (string) $created['Customer']['Id'];
    }

    /**
     * Create an invoice and immediately mark it as paid.
     * Returns ['invoice_id' => QB invoice ID].
     */
    public function createPaidInvoice(string $customerId, string $description, float $amount, string $currency): array
    {
        $invoiceData = [
            'CustomerRef'  => ['value' => $customerId],
            'CurrencyRef'  => ['value' => $currency],
            'Line'         => [[
                'Amount'          => $amount,
                'DetailType'      => 'SalesItemLineDetail',
                'SalesItemLineDetail' => [
                    'ItemRef'   => ['value' => '1', 'name' => 'Services'],
                    'UnitPrice' => $amount,
                    'Qty'       => 1,
                ],
                'Description'     => $description,
            ]],
        ];

        $invoice   = $this->request('post', 'invoice', $invoiceData);
        $invoiceId = $invoice['Invoice']['Id'];
        $syncToken = $invoice['Invoice']['SyncToken'];

        // Mark the invoice as paid using a Payment entity
        $this->request('post', 'payment', [
            'CustomerRef' => ['value' => $customerId],
            'TotalAmt'    => $amount,
            'Line'        => [[
                'Amount'    => $amount,
                'LinkedTxn' => [[
                    'TxnId'   => $invoiceId,
                    'TxnType' => 'Invoice',
                ]],
            ]],
        ]);

        return ['invoice_id' => $invoiceId, 'sync_token' => $syncToken];
    }
}
