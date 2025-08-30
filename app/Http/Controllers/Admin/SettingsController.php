<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateSettingsRequest;
use App\Service\StorableConfig;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    use AdminAccessMiddleware;

    public function __construct()
    {
        $this->middleware($this->makeIsAdminMiddleware());
        $this->middleware($this->makeDemoRestrictionMiddleware());
    }

    public function index()
    {
        return view('admin.settings');
    }

    public function update(UpdateSettingsRequest $request)
    {
        /** @var StorableConfig $store */
        $store = app('config.storable');

        $validated = $request->validated();
        $validated['link_per_bitrate'] = (int)$request->get('link_per_bitrate');

        $store
            ->put('app', $validated)
            ->save();

        session()->flash(
            'message',
            ['type' => 'success', 'content' => 'Settings updated successfully']
        );

        return back();
    }
}
