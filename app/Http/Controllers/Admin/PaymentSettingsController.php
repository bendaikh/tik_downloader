<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Service\StorableConfig;
use Illuminate\Http\Request;

class PaymentSettingsController extends Controller
{
    use AdminAccessMiddleware;

    public function __construct()
    {
        $this->middleware($this->makeIsAdminMiddleware());
        $this->middleware($this->makeDemoRestrictionMiddleware());
    }

    public function index()
    {
        return view('admin.payment-settings');
    }

    public function update(Request $request)
    {
        $request->validate([
            'paypal_client_id' => 'nullable|string|max:255',
            'paypal_client_secret' => 'nullable|string|max:255',
            'paypal_mode' => 'required|in:sandbox,live',
            'paypal_currency' => 'required|string|max:3',
            'donation_amounts' => 'nullable|string',
            'donation_enabled' => 'boolean',
        ]);

        /** @var StorableConfig $store */
        $store = app('config.storable');

        $settings = [
            'paypal_client_id' => $request->input('paypal_client_id'),
            'paypal_client_secret' => $request->input('paypal_client_secret'),
            'paypal_mode' => $request->input('paypal_mode'),
            'paypal_currency' => $request->input('paypal_currency'),
            'donation_amounts' => $request->input('donation_amounts'),
            'donation_enabled' => $request->boolean('donation_enabled'),
        ];

        $store
            ->put('payments', $settings)
            ->save();

        session()->flash(
            'message',
            ['type' => 'success', 'content' => 'Payment settings updated successfully']
        );

        return back();
    }
}
