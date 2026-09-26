<?php
namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Exception;

class BlinkService
{
    protected string $endpoint;
    protected string $apiKey;
    protected string $defaultWalletId;

    public function __construct()
    {
        $this->endpoint = config('services.blink.endpoint');
        $this->apiKey = config('services.blink.api_key');
        $this->defaultWalletId = config('services.blink.wallet_id');
    }

    /**
     * Send funds via BOLT11 Lightning Invoice
     *
     * @param string $paymentRequest (lnbc...)
     * @param string|null $walletId
     * @return array
     * @throws Exception
     */
    public function payLnInvoice(string $paymentRequest, ?string $walletId = null): array
    {
        $walletId = $walletId ?? $this->defaultWalletId;

        $query = <<<'GRAPHQL'
            mutation LnInvoicePaymentSend($input: LnInvoicePaymentInput!) {
                lnInvoicePaymentSend(input: $input) {
                    status
                    errors {
                        message
                        path
                        code
                    }
                }
            }
        GRAPHQL;

        $response = Http::withHeaders([
            'X-API-KEY' => $this->apiKey,
            'Content-Type' => 'application/json',
        ])->post($this->endpoint, [
            'query' => $query,
            'variables' => [
                'input' => [
                    'walletId' => $walletId,
                    'paymentRequest' => $paymentRequest,
                ],
            ],
        ]);

        if ($response->failed()) {
            Log::error('Blink API Request Failed', ['body' => $response->body()]);
            throw new Exception("HTTP request failed with status: {$response->status()}");
        }

        $result = $response->json();

        // Check for GraphQL schema/execution errors
        if (isset($result['errors'])) {
            $errorMessage = $result['errors'][0]['message'] ?? 'GraphQL Error';
            Log::error('Blink GraphQL Error', ['errors' => $result['errors']]);
            throw new Exception("Blink GraphQL Error: {$errorMessage}");
        }

        // Return the mutation status payload
        return $result['data']['lnInvoicePaymentSend'];
    }

    /**
     * Send directly to a Lightning Address (e.g. user@blink.sv)
     */
    public function sendToLnAddress(string $lnAddress, int $amountInSats, ?string $walletId = null): array
    {
        $walletId = $walletId ?? $this->defaultWalletId;

        $query = <<<'GRAPHQL'
            mutation LnAddressPaymentSend($input: LnAddressPaymentSendInput!) {
                lnAddressPaymentSend(input: $input) {
                    status
                    errors {
                        code
                        message
                        path
                    }
                }
            }
        GRAPHQL;

        $response = Http::withHeaders([
            'X-API-KEY' => $this->apiKey,
            'Content-Type' => 'application/json',
        ])->post($this->endpoint, [
            'query' => $query,
            'variables' => [
                'input' => [
                    'walletId' => $walletId,
                    'amount' => $amountInSats,
                    'lnAddress' => $lnAddress,
                ],
            ],
        ]);

        return $response->json('data.lnAddressPaymentSend', []);
    }
}