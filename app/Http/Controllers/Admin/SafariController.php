<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Service\StorableConfig;
use Illuminate\Http\Request;

class SafariController extends Controller
{
    use AdminAccessMiddleware;

    public function __construct()
    {
        $this->middleware($this->makeIsAdminMiddleware());
        $this->middleware($this->makeDemoRestrictionMiddleware());
    }

    public function index()
    {
        return view('admin.safari');
    }

    public function update(Request $request)
    {
        $request->validate([
            'safari_website_id' => 'nullable|string|max:50',
            'safari_enabled' => 'boolean',
            'safari_track_pageviews' => 'boolean',
            'safari_track_events' => 'boolean',
            'safari_track_downloads' => 'boolean',
            'safari_track_donations' => 'boolean',
            'safari_track_engagement' => 'boolean',
            'safari_debug_mode' => 'boolean',
        ]);

        /** @var StorableConfig $store */
        $store = app('config.storable');

        $settings = [
            'website_id' => $request->input('safari_website_id'),
            'enabled' => $request->boolean('safari_enabled'),
            'track_pageviews' => $request->boolean('safari_track_pageviews'),
            'track_events' => $request->boolean('safari_track_events'),
            'track_downloads' => $request->boolean('safari_track_downloads'),
            'track_donations' => $request->boolean('safari_track_donations'),
            'track_engagement' => $request->boolean('safari_track_engagement'),
            'debug_mode' => $request->boolean('safari_debug_mode'),
        ];

        $store
            ->put('safari', $settings)
            ->save();

        session()->flash(
            'message',
            ['type' => 'success', 'content' => 'Safari Analytics settings updated successfully']
        );

        return back();
    }
}
