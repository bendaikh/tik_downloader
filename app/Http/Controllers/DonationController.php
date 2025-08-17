<?php

namespace App\Http\Controllers;

use App\Service\PayPal\PayPalService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class DonationController extends Controller
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
        
        return view('donation.show', compact('config'));
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
     * Handle successful donation
     */
    public function success(Request $request)
    {
        $orderId = $request->get('token');
        
        if ($orderId) {
            $capture = $this->paypalService->capturePayment($orderId);
            
            if ($capture) {
                Log::info('Donation successful', [
                    'order_id' => $orderId,
                    'amount' => $capture['purchase_units'][0]['payments']['captures'][0]['amount'] ?? null
                ]);
                
                return view('donation.success', [
                    'order_id' => $orderId,
                    'amount' => $capture['purchase_units'][0]['payments']['captures'][0]['amount'] ?? null
                ]);
            }
        }

        return view('donation.success', [
            'order_id' => $orderId,
            'amount' => null
        ]);
    }

    /**
     * Handle cancelled donation
     */
    public function cancel()
    {
        return view('donation.cancel');
    }

    /**
     * Handle webhook notifications from PayPal
     */
    public function webhook(Request $request)
    {
        $payload = $request->all();
        
        Log::info('PayPal webhook received', $payload);

        // Verify webhook signature here if needed
        // For now, just log the event
        
        return response()->json(['status' => 'ok']);
    }
}
