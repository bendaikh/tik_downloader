<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Service\StorableConfig;
use Illuminate\Http\Request;

class SeoSettingsController extends Controller
{
    use AdminAccessMiddleware;

    public function __construct()
    {
        $this->middleware($this->makeIsAdminMiddleware());
        $this->middleware($this->makeDemoRestrictionMiddleware());
    }

    public function index()
    {
        return view('admin.seo-settings');
    }

    public function update(Request $request)
    {
        $request->validate([
            'seo_title' => 'nullable|string|max:60',
            'seo_description' => 'nullable|string|max:160',
            'seo_keywords' => 'nullable|string|max:500',
            'seo_author' => 'nullable|string|max:100',
            'seo_robots' => 'nullable|string|max:100',
            'seo_canonical' => 'nullable|url',
            'og_title' => 'nullable|string|max:60',
            'og_description' => 'nullable|string|max:160',
            'og_image' => 'nullable|string|max:500',
            'og_type' => 'nullable|string|max:50',
            'twitter_card' => 'nullable|string|max:50',
            'twitter_site' => 'nullable|string|max:100',
            'twitter_creator' => 'nullable|string|max:100',
            'structured_data_enabled' => 'boolean',
            'structured_data_type' => 'nullable|string|max:50',
            'structured_data_name' => 'nullable|string|max:100',
            'structured_data_description' => 'nullable|string|max:500',
            'structured_data_url' => 'nullable|url',
            'structured_data_logo' => 'nullable|url',
            'structured_data_contact_email' => 'nullable|email',
            'structured_data_contact_phone' => 'nullable|string|max:20',
            'structured_data_address' => 'nullable|string|max:200',
            'structured_data_city' => 'nullable|string|max:100',
            'structured_data_state' => 'nullable|string|max:100',
            'structured_data_zip' => 'nullable|string|max:20',
            'structured_data_country' => 'nullable|string|max:100',
        ]);

        /** @var StorableConfig $store */
        $store = app('config.storable');

        $settings = [
            'seo_title' => $request->input('seo_title'),
            'seo_description' => $request->input('seo_description'),
            'seo_keywords' => $request->input('seo_keywords'),
            'seo_author' => $request->input('seo_author'),
            'seo_robots' => $request->input('seo_robots'),
            'seo_canonical' => $request->input('seo_canonical'),
            'og_title' => $request->input('og_title'),
            'og_description' => $request->input('og_description'),
            'og_image' => $request->input('og_image'),
            'og_type' => $request->input('og_type'),
            'twitter_card' => $request->input('twitter_card'),
            'twitter_site' => $request->input('twitter_site'),
            'twitter_creator' => $request->input('twitter_creator'),
            'structured_data_enabled' => $request->boolean('structured_data_enabled'),
            'structured_data_type' => $request->input('structured_data_type'),
            'structured_data_name' => $request->input('structured_data_name'),
            'structured_data_description' => $request->input('structured_data_description'),
            'structured_data_url' => $request->input('structured_data_url'),
            'structured_data_logo' => $request->input('structured_data_logo'),
            'structured_data_contact_email' => $request->input('structured_data_contact_email'),
            'structured_data_contact_phone' => $request->input('structured_data_contact_phone'),
            'structured_data_address' => $request->input('structured_data_address'),
            'structured_data_city' => $request->input('structured_data_city'),
            'structured_data_state' => $request->input('structured_data_state'),
            'structured_data_zip' => $request->input('structured_data_zip'),
            'structured_data_country' => $request->input('structured_data_country'),
        ];

        $store
            ->put('seo', $settings)
            ->save();

        session()->flash(
            'message',
            ['type' => 'success', 'content' => 'SEO settings updated successfully']
        );

        return back();
    }

    public function generateSitemap(Request $request)
    {
        // Generate sitemap logic here
        session()->flash(
            'message',
            ['type' => 'success', 'content' => 'Sitemap generated successfully']
        );

        return back();
    }
}
