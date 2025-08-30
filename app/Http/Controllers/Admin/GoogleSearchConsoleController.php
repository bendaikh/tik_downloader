<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Service\StorableConfig;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class GoogleSearchConsoleController extends Controller
{
    use AdminAccessMiddleware;

    public function __construct()
    {
        $this->middleware($this->makeIsAdminMiddleware());
        $this->middleware($this->makeDemoRestrictionMiddleware());
    }

    public function index()
    {
        return view('admin.google-search-console');
    }

    public function update(Request $request)
    {
        $request->validate([
            'gsc_property_url' => 'nullable|url',
            'gsc_enabled' => 'boolean',
            'gsc_verification_code' => 'nullable|string|max:255',
            'gsc_track_search_queries' => 'boolean',
            'gsc_track_click_through_rate' => 'boolean',
            'gsc_track_impressions' => 'boolean',
            'gsc_track_average_position' => 'boolean',
        ]);

        /** @var StorableConfig $store */
        $store = app('config.storable');

        $settings = [
            'property_url' => $request->input('gsc_property_url'),
            'enabled' => $request->boolean('gsc_enabled'),
            'verification_code' => $request->input('gsc_verification_code'),
            'track_search_queries' => $request->boolean('gsc_track_search_queries'),
            'track_click_through_rate' => $request->boolean('gsc_track_click_through_rate'),
            'track_impressions' => $request->boolean('gsc_track_impressions'),
            'track_average_position' => $request->boolean('gsc_track_average_position'),
        ];

        $store
            ->put('search_console', $settings)
            ->save();

        session()->flash(
            'message',
            ['type' => 'success', 'content' => 'Google Search Console settings updated successfully']
        );

        return back();
    }

    /**
     * Create HTML verification file
     */
    private function createVerificationFile($content)
    {
        try {
            // Extract filename from content (usually starts with "google")
            if (preg_match('/google[0-9a-f]{16,}\.html/', $content, $matches)) {
                $filename = $matches[0];
            } else {
                $filename = 'google' . time() . '.html';
            }

            // Create the verification file in public directory
            $filePath = public_path($filename);
            File::put($filePath, $content);

            // Update the settings with the filename
            /** @var StorableConfig $store */
            $store = app('config.storable');
            $currentSettings = $store->get('search_console', []);
            $currentSettings['gsc_html_filename'] = $filename;
            $store->put('search_console', $currentSettings)->save();

        } catch (\Exception $e) {
            \Log::error('Failed to create Google Search Console verification file: ' . $e->getMessage());
        }
    }

    /**
     * Generate sitemap
     */
    public function generateSitemap()
    {
        try {
            $sitemap = $this->buildSitemap();
            
            // Save sitemap to public directory
            $sitemapPath = public_path('sitemap.xml');
            File::put($sitemapPath, $sitemap);

            session()->flash(
                'message',
                ['type' => 'success', 'content' => 'Sitemap generated successfully at /sitemap.xml']
            );

        } catch (\Exception $e) {
            session()->flash(
                'message',
                ['type' => 'error', 'content' => 'Failed to generate sitemap: ' . $e->getMessage()]
            );
        }

        return back();
    }

    /**
     * Build XML sitemap
     */
    private function buildSitemap()
    {
        $baseUrl = config('app.url');
        $currentDate = now()->toISOString();

        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";

        // Home page
        $xml .= $this->addSitemapUrl($baseUrl, $currentDate, '1.0');

        // Blog posts
        $posts = \App\Models\Blog::published()->get();
        foreach ($posts as $post) {
            $xml .= $this->addSitemapUrl(
                $baseUrl . '/blog/' . $post->slug,
                $post->published_at ? $post->published_at->toISOString() : $currentDate,
                '0.8'
            );
        }

        // Products
        $products = \App\Models\Product::active()->get();
        foreach ($products as $product) {
            $xml .= $this->addSitemapUrl(
                $baseUrl . '/products/' . $product->slug,
                $product->created_at->toISOString(),
                '0.7'
            );
        }

        // Static pages
        $staticPages = [
            '/faq' => '0.6',
            '/how-to-save' => '0.6',
            '/popular-videos' => '0.7',
            '/privacy' => '0.3',
            '/tos' => '0.3',
        ];

        foreach ($staticPages as $page => $priority) {
            $xml .= $this->addSitemapUrl($baseUrl . $page, $currentDate, $priority);
        }

        $xml .= '</urlset>';

        return $xml;
    }

    /**
     * Add URL to sitemap
     */
    private function addSitemapUrl($url, $lastmod, $priority)
    {
        return "  <url>\n" .
               "    <loc>" . htmlspecialchars($url) . "</loc>\n" .
               "    <lastmod>" . $lastmod . "</lastmod>\n" .
               "    <changefreq>weekly</changefreq>\n" .
               "    <priority>" . $priority . "</priority>\n" .
               "  </url>\n";
    }
}
