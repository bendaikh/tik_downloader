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
    }

    public function index()
    {
        return view('admin.google-search-console');
    }

    public function update(Request $request)
    {
        $request->validate([
            'gsc_enabled' => 'boolean',
            'gsc_verification_method' => 'nullable|string|in:html_file,meta_tag,dns_record',
            'gsc_meta_tag' => 'nullable|string|max:500',
            'gsc_html_file_content' => 'nullable|string|max:1000',
            'gsc_dns_record' => 'nullable|string|max:200',
            'gsc_property_url' => 'nullable|url',
            'gsc_auto_submit_sitemap' => 'boolean',
            'gsc_sitemap_url' => 'nullable|url',
        ]);

        /** @var StorableConfig $store */
        $store = app('config.storable');

        $settings = [
            'gsc_enabled' => $request->boolean('gsc_enabled'),
            'gsc_verification_method' => $request->input('gsc_verification_method'),
            'gsc_meta_tag' => $request->input('gsc_meta_tag'),
            'gsc_html_file_content' => $request->input('gsc_html_file_content'),
            'gsc_dns_record' => $request->input('gsc_dns_record'),
            'gsc_property_url' => $request->input('gsc_property_url'),
            'gsc_auto_submit_sitemap' => $request->boolean('gsc_auto_submit_sitemap'),
            'gsc_sitemap_url' => $request->input('gsc_sitemap_url'),
        ];

        // If HTML file verification is selected, create the verification file
        if ($request->input('gsc_verification_method') === 'html_file' && $request->input('gsc_html_file_content')) {
            $this->createVerificationFile($request->input('gsc_html_file_content'));
        }

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
