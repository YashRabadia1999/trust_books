<?php

namespace Workdo\SmsCredit\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class HubtelPaymentService
{
    protected $authorizationHeader;
    protected $merchantAccount;
    protected $initiateUrl;

    public function __construct()
    {
        // Use the Base64-encoded string directly from the .env file
        $this->authorizationHeader = 'Basic ' . env('HUBTEL_API_KEY');
        $this->merchantAccount = env('HUBTEL_API_SECRET');
        $this->initiateUrl = 'https://payproxyapi.hubtel.com/items/initiate';
    }

    /**
     * Initialize a payment request
     */
    public function initiatePayment($data)
    {
        try {
            // Validate credentials
            if (empty(env('HUBTEL_API_KEY')) || empty(env('HUBTEL_API_SECRET'))) {
                return [
                    'status' => false,
                    'message' => 'Hubtel API credentials not configured',
                    'error' => 'Missing HUBTEL_API_KEY or HUBTEL_API_SECRET in .env file'
                ];
            }

            $clientReference = $data['reference'] ?? uniqid('SMSCredit_');

            $payload = [
                "totalAmount" => $data['amount'],
                "description" => $data['description'] ?? "SMS Credit Purchase (Ref: {$clientReference})",
                "callbackUrl" => $data['callback_url'] ?? route('sms-credit.payment.callback'),
                "returnUrl" => $data['return_url'] ?? route('sms-credit.index'),
                "merchantAccountNumber" => $this->merchantAccount,
                "cancellationUrl" => $data['cancel_url'] ?? route('sms-credit.index'),
                "clientReference" => "inv" . $clientReference,
            ];

            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'Authorization' => $this->authorizationHeader,
            ])->post($this->initiateUrl, $payload);

            if ($response->successful()) {
                $responseData = $response->object();

                if (
                    isset($responseData->status) &&
                    strtolower($responseData->status) === 'success' &&
                    isset($responseData->data->checkoutUrl)
                ) {
                    return [
                        'status' => true,
                        'data' => [
                            'checkoutUrl' => $responseData->data->checkoutUrl,
                            'transactionId' => $clientReference,
                            'checkoutId' => $responseData->data->checkoutId ?? null,
                        ],
                        'message' => 'Payment initiated successfully'
                    ];
                }

                Log::error('Hubtel Payment Initiation Failed: Status not success or checkout URL missing', [
                    'response' => $response->json(),
                ]);

                return [
                    'status' => false,
                    'message' => 'Payment initiation failed: Invalid response from Hubtel',
                    'error' => $response->json()
                ];
            }

            Log::error('Hubtel Payment Initiation Failed', [
                'response' => $response->body(),
                'status' => $response->status(),
                'payload' => $payload
            ]);

            return [
                'status' => false,
                'message' => 'Payment initiation failed (HTTP ' . $response->status() . ')',
                'error' => $response->json()
            ];

        } catch (\Exception $e) {
            Log::error('Hubtel Payment Exception', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return [
                'status' => false,
                'message' => 'Payment initiation error',
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Check payment status
     */
    public function checkPaymentStatus($transactionId)
    {
        try {
            $response = Http::withBasicAuth($this->apiKey, $this->apiSecret)
                ->get($this->baseUrl . "/merchants/payments/{$transactionId}/status");

            if ($response->successful()) {
                return [
                    'status' => true,
                    'data' => $response->json()
                ];
            }

            return [
                'status' => false,
                'message' => 'Failed to check payment status'
            ];

        } catch (\Exception $e) {
            Log::error('Hubtel Status Check Exception', ['message' => $e->getMessage()]);

            return [
                'status' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    /**
     * Verify payment callback
     */
    public function verifyCallback($data)
    {
        // Verify the payment status from callback data
        if (isset($data['Status']) && strtolower($data['Status']) === 'success') {
            return [
                'status' => true,
                'transaction_id' => $data['TransactionId'] ?? null,
                'amount' => $data['Amount'] ?? 0,
                'customer_number' => $data['CustomerMsisdn'] ?? null,
            ];
        }

        return [
            'status' => false,
            'message' => $data['StatusMessage'] ?? 'Payment verification failed'
        ];
    }
}
