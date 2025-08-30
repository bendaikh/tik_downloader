<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Service\StorableConfig;
use Illuminate\Http\Request;

class EdgeIntegrationController extends Controller
{
    use AdminAccessMiddleware;

    public function __construct()
    {
        $this->middleware($this->makeIsAdminMiddleware());
        $this->middleware($this->makeDemoRestrictionMiddleware());
    }

    public function index()
    {
        return view('admin.edge-integration');
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'bing_webmaster_verification' => 'nullable|string|max:255',
            'bing_webmaster_enabled' => 'boolean',
            'microsoft_clarity_id' => 'nullable|string|max:255',
            'microsoft_clarity_enabled' => 'boolean',
            'microsoft_ads_id' => 'nullable|string|max:255',
            'microsoft_ads_enabled' => 'boolean',
            'azure_application_insights_key' => 'nullable|string|max:255',
            'azure_application_insights_enabled' => 'boolean',
        ]);

        // Extract verification code from meta tag if full tag was pasted
        $verificationCode = $validated['bing_webmaster_verification'] ?? null;
        if ($verificationCode && strpos($verificationCode, '<meta') !== false) {
            // Extract content from meta tag
            if (preg_match('/content="([^"]+)"/', $verificationCode, $matches)) {
                $verificationCode = $matches[1];
            }
        }

        /** @var StorableConfig $storable */
        $storable = app('config.storable');

        // Save Microsoft services settings to config
        $storable->put('services.microsoft', [
            'bing_webmaster_verification' => $verificationCode,
            'bing_webmaster_enabled' => $validated['bing_webmaster_enabled'] ?? false,
            'microsoft_clarity_id' => $validated['microsoft_clarity_id'] ?? null,
            'microsoft_clarity_enabled' => $validated['microsoft_clarity_enabled'] ?? false,
            'microsoft_ads_id' => $validated['microsoft_ads_id'] ?? null,
            'microsoft_ads_enabled' => $validated['microsoft_ads_enabled'] ?? false,
            'azure_application_insights_key' => $validated['azure_application_insights_key'] ?? null,
            'azure_application_insights_enabled' => $validated['azure_application_insights_enabled'] ?? false,
        ]);

        if (!$storable->save()) {
            return back()->withInput()->with('error', 'Failed to save Microsoft services settings.');
        }
        
        return redirect()->route('admin.edge-integration')
            ->with('success', 'Microsoft services settings updated successfully!');
    }
}
