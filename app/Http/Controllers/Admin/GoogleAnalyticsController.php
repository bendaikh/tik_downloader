<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Service\StorableConfig;
use Illuminate\Http\Request;

class GoogleAnalyticsController extends Controller
{
    use AdminAccessMiddleware;

    public function __construct()
    {
        $this->middleware($this->makeIsAdminMiddleware());
        $this->middleware($this->makeDemoRestrictionMiddleware());
    }

    public function index()
    {
        return view('admin.google-analytics');
    }

    public function update(Request $request)
    {
        $request->validate([
            'ga_measurement_id' => 'nullable|string|max:20',
            'ga_enabled' => 'boolean',
            'ga_track_pageviews' => 'boolean',
            'ga_track_events' => 'boolean',
            'ga_track_downloads' => 'boolean',
            'ga_track_donations' => 'boolean',
            'ga_anonymize_ip' => 'boolean',
            'ga_debug_mode' => 'boolean',
        ]);

        /** @var StorableConfig $store */
        $store = app('config.storable');

        $settings = [
            'ga_measurement_id' => $request->input('ga_measurement_id'),
            'ga_enabled' => $request->boolean('ga_enabled'),
            'ga_track_pageviews' => $request->boolean('ga_track_pageviews'),
            'ga_track_events' => $request->boolean('ga_track_events'),
            'ga_track_downloads' => $request->boolean('ga_track_downloads'),
            'ga_track_donations' => $request->boolean('ga_track_donations'),
            'ga_anonymize_ip' => $request->boolean('ga_anonymize_ip'),
            'ga_debug_mode' => $request->boolean('ga_debug_mode'),
        ];

        $store
            ->put('analytics', $settings)
            ->save();

        session()->flash(
            'message',
            ['type' => 'success', 'content' => 'Google Analytics settings updated successfully']
        );

        return back();
    }
}
