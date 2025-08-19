<?php

namespace Themes\TikTokDark\Controllers;

use App\Http\Controllers\DonationController as BaseDonationController;
use App\Service\PayPal\PayPalService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class DonationController extends BaseDonationController
{
    private $paypalService;

    public function __construct(PayPalService $paypalService)
    {
        $this->paypalService = $paypalService;
    }

    /**
     * Show donation modal/page
     */
    public function show(Request $request)
    {
        if (!$this->paypalService->isConfigured()) {
            return redirect()->back()->with('error', 'Donations are not configured.');
        }

        $config = $this->paypalService->getClientConfig();
        
        return view('TikTokDark::donation.show', compact('config'));
    }

    /**
     * Handle successful donation
     */
    public function success(Request $request)
    {
        $orderId = $request->get('token');
        
        if ($orderId) {
            $capture = $this->paypalService->capturePayment($orderId);
            
            if ($capture) {
                $amount = $capture['purchase_units'][0]['payments']['captures'][0]['amount'] ?? null;
                
                Log::info('Donation successful', [
                    'order_id' => $orderId,
                    'amount' => $amount
                ]);

                // Track donation event if Google Analytics is enabled
                if (config('analytics.ga_enabled') && config('analytics.ga_track_donations') && $amount) {
                    $gaService = app(\App\Service\GoogleAnalytics\GoogleAnalyticsService::class);
                    if ($gaService->isEnabled()) {
                        Log::info('Donation tracked in Google Analytics', [
                            'order_id' => $orderId,
                            'amount' => $amount['value'],
                            'currency' => $amount['currency_code']
                        ]);
                    }
                }

                // Track donation event if Safari Analytics is enabled
                if (config('safari.enabled') && config('safari.track_donations') && $amount) {
                    $safariService = app(\App\Service\Safari\SafariService::class);
                    if ($safariService->isEnabled()) {
                        Log::info('Donation tracked in Safari Analytics', [
                            'order_id' => $orderId,
                            'amount' => $amount['value'],
                            'currency' => $amount['currency_code']
                        ]);
                    }
                }
                
                return view('TikTokDark::donation.success', [
                    'order_id' => $orderId,
                    'amount' => $amount
                ]);
            }
        }

        return view('TikTokDark::donation.success', [
            'order_id' => $orderId,
            'amount' => null
        ]);
    }

    /**
     * Handle cancelled donation
     */
    public function cancel()
    {
        return view('TikTokDark::donation.cancel');
    }

    /**
     * Create PayPal order
     */
    public function createOrder(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1',
            'currency' => 'required|string|size:3'
        ]);

        if (!$this->paypalService->isConfigured()) {
            return response()->json(['error' => 'PayPal is not configured'], 400);
        }

        $order = $this->paypalService->createOrder(
            $request->amount,
            $request->currency,
            'Donation to ' . config('app.name')
        );

        if (!$order) {
            return response()->json(['error' => 'Failed to create PayPal order'], 500);
        }

        return response()->json($order);
    }

    /**
     * Process donation (for form submission)
     */
    public function process(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1'
        ]);

        if (!$this->paypalService->isConfigured()) {
            return redirect()->back()->with('error', 'PayPal is not configured.');
        }

        $order = $this->paypalService->createOrder(
            $request->amount,
            'USD',
            'Donation to ' . config('app.name')
        );

        if (!$order) {
            return redirect()->back()->with('error', 'Failed to create donation order.');
        }

        // Redirect to PayPal for payment
        return redirect($order['links'][1]['href']);
    }
}
