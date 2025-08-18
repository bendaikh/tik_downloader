<?php

namespace App\Service\PayPal;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PayPalService
{
    private $clientId;
    private $clientSecret;
    private $mode;
    private $baseUrl;

    public function __construct()
    {
        $this->clientId = config('payments.paypal_client_id');
        $this->clientSecret = config('payments.paypal_client_secret');
        $this->mode = config('payments.paypal_mode', 'sandbox');
        $this->baseUrl = $this->mode === 'live' 
            ? 'https://api-m.paypal.com' 
            : 'https://api-m.sandbox.paypal.com';
    }

    /**
     * Get PayPal access token
     */
    public function getAccessToken()
    {
        try {
            $response = Http::withHeaders([
                'Accept' => 'application/json',
                'Accept-Language' => 'en_US',
            ])->withBasicAuth($this->clientId, $this->clientSecret)
            ->asForm()
            ->post($this->baseUrl . '/v1/oauth2/token', [
                'grant_type' => 'client_credentials'
            ]);

            if ($response->successful()) {
                return $response->json('access_token');
            }

            Log::error('PayPal access token error', [
                'response' => $response->json(),
                'status' => $response->status()
            ]);

            return null;
        } catch (\Exception $e) {
            Log::error('PayPal access token exception', [
                'message' => $e->getMessage()
            ]);
            return null;
        }
    }

    /**
     * Create PayPal order
     */
    public function createOrder($amount, $currency = 'USD', $description = 'Donation')
    {
        $accessToken = $this->getAccessToken();
        
        if (!$accessToken) {
            return null;
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $accessToken,
                'Content-Type' => 'application/json',
            ])->post($this->baseUrl . '/v2/checkout/orders', [
                'intent' => 'CAPTURE',
                'purchase_units' => [
                    [
                        'amount' => [
                            'currency_code' => $currency,
                            'value' => number_format($amount, 2, '.', '')
                        ],
                        'description' => $description
                    ]
                ],
                'application_context' => [
                    'return_url' => route('donation.success'),
                    'cancel_url' => route('donation.cancel'),
                    'brand_name' => config('app.name'),
                    'user_action' => 'PAY_NOW'
                ]
            ]);

            if ($response->successful()) {
                return $response->json();
            }

            Log::error('PayPal create order error', [
                'response' => $response->json(),
                'status' => $response->status()
            ]);

            return null;
        } catch (\Exception $e) {
            Log::error('PayPal create order exception', [
                'message' => $e->getMessage()
            ]);
            return null;
        }
    }

    /**
     * Capture PayPal payment
     */
    public function capturePayment($orderId)
    {
        $accessToken = $this->getAccessToken();
        
        if (!$accessToken) {
            return null;
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $accessToken,
                'Content-Type' => 'application/json',
            ])->post($this->baseUrl . '/v2/checkout/orders/' . $orderId . '/capture');

            if ($response->successful()) {
                return $response->json();
            }

            Log::error('PayPal capture payment error', [
                'response' => $response->json(),
                'status' => $response->status()
            ]);

            return null;
        } catch (\Exception $e) {
            Log::error('PayPal capture payment exception', [
                'message' => $e->getMessage()
            ]);
            return null;
        }
    }

    /**
     * Check if PayPal is configured
     */
    public function isConfigured()
    {
        return !empty($this->clientId) && !empty($this->clientSecret);
    }

    /**
     * Get PayPal client configuration for frontend
     */
    public function getClientConfig()
    {
        return [
            'client_id' => $this->clientId,
            'currency' => config('payments.paypal_currency', 'USD'),
            'mode' => $this->mode,
            'donation_amounts' => $this->getDonationAmounts(),
            'enabled' => config('payments.donation_enabled', false)
        ];
    }

    /**
     * Get donation amounts as array
     */
    private function getDonationAmounts()
    {
        $amounts = config('payments.donation_amounts', '5,10,25,50,100');
        return array_map('trim', explode(',', $amounts));
    }
}
