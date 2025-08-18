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
        $this->middleware($this->makeIsAdminMiddleware())->except('index');
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

        /** @var StorableConfig $storable */
        $storable = app('config.storable');

        // Save Microsoft services settings to config
        $storable->put('services.microsoft', [
            'bing_webmaster' => [
                'verification_code' => $validated['bing_webmaster_verification'] ?? null,
                'enabled' => $validated['bing_webmaster_enabled'] ?? false,
            ],
            'clarity' => [
                'project_id' => $validated['microsoft_clarity_id'] ?? null,
                'enabled' => $validated['microsoft_clarity_enabled'] ?? false,
            ],
            'ads' => [
                'tracking_id' => $validated['microsoft_ads_id'] ?? null,
                'enabled' => $validated['microsoft_ads_enabled'] ?? false,
            ],
            'application_insights' => [
                'instrumentation_key' => $validated['azure_application_insights_key'] ?? null,
                'enabled' => $validated['azure_application_insights_enabled'] ?? false,
            ],
        ]);

        if (!$storable->save()) {
            return back()->withInput()->with('message', [
                'type' => 'error',
                'content' => 'Failed to save Microsoft services integration settings.',
            ]);
        }

        session()->flash(
            'message',
            ['type' => 'success', 'content' => 'Microsoft services integration settings updated successfully']
        );

        return back();
    }
}
