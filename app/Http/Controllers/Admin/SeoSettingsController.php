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
        $this->middleware($this->makeIsAdminMiddleware())->except('index');
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
            'sitemap_enabled' => 'boolean',
            'sitemap_auto_generate' => 'boolean',
            'sitemap_include_blogs' => 'boolean',
            'sitemap_include_products' => 'boolean',
            'sitemap_priority_home' => 'nullable|numeric|between:0,1',
            'sitemap_priority_blogs' => 'nullable|numeric|between:0,1',
            'sitemap_priority_products' => 'nullable|numeric|between:0,1',
            'sitemap_changefreq_home' => 'nullable|string|max:20',
            'sitemap_changefreq_blogs' => 'nullable|string|max:20',
            'sitemap_changefreq_products' => 'nullable|string|max:20',
            'schema_markup_enabled' => 'boolean',
            'schema_organization_enabled' => 'boolean',
            'schema_website_enabled' => 'boolean',
            'schema_webpage_enabled' => 'boolean',
            'schema_article_enabled' => 'boolean',
            'schema_product_enabled' => 'boolean',
            'schema_faq_enabled' => 'boolean',
            'schema_breadcrumb_enabled' => 'boolean',
            'performance_enabled' => 'boolean',
            'minify_html' => 'boolean',
            'minify_css' => 'boolean',
            'minify_js' => 'boolean',
            'enable_gzip' => 'boolean',
            'enable_browser_caching' => 'boolean',
            'cache_duration' => 'nullable|integer|min:1',
            'lazy_loading_enabled' => 'boolean',
            'image_optimization_enabled' => 'boolean',
            'webp_conversion_enabled' => 'boolean',
        ]);

        /** @var StorableConfig $store */
        $store = app('config.storable');

        $settings = [
            // Basic SEO
            'seo_title' => $request->input('seo_title'),
            'seo_description' => $request->input('seo_description'),
            'seo_keywords' => $request->input('seo_keywords'),
            'seo_author' => $request->input('seo_author'),
            'seo_robots' => $request->input('seo_robots'),
            'seo_canonical' => $request->input('seo_canonical'),
            
            // Open Graph
            'og_title' => $request->input('og_title'),
            'og_description' => $request->input('og_description'),
            'og_image' => $request->input('og_image'),
            'og_type' => $request->input('og_type'),
            
            // Twitter Cards
            'twitter_card' => $request->input('twitter_card'),
            'twitter_site' => $request->input('twitter_site'),
            'twitter_creator' => $request->input('twitter_creator'),
            
            // Structured Data
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
            
            // Sitemap
            'sitemap_enabled' => $request->boolean('sitemap_enabled'),
            'sitemap_auto_generate' => $request->boolean('sitemap_auto_generate'),
            'sitemap_include_blogs' => $request->boolean('sitemap_include_blogs'),
            'sitemap_include_products' => $request->boolean('sitemap_include_products'),
            'sitemap_priority_home' => $request->input('sitemap_priority_home'),
            'sitemap_priority_blogs' => $request->input('sitemap_priority_blogs'),
            'sitemap_priority_products' => $request->input('sitemap_priority_products'),
            'sitemap_changefreq_home' => $request->input('sitemap_changefreq_home'),
            'sitemap_changefreq_blogs' => $request->input('sitemap_changefreq_blogs'),
            'sitemap_changefreq_products' => $request->input('sitemap_changefreq_products'),
            
            // Schema Markup
            'schema_markup_enabled' => $request->boolean('schema_markup_enabled'),
            'schema_organization_enabled' => $request->boolean('schema_organization_enabled'),
            'schema_website_enabled' => $request->boolean('schema_website_enabled'),
            'schema_webpage_enabled' => $request->boolean('schema_webpage_enabled'),
            'schema_article_enabled' => $request->boolean('schema_article_enabled'),
            'schema_product_enabled' => $request->boolean('schema_product_enabled'),
            'schema_faq_enabled' => $request->boolean('schema_faq_enabled'),
            'schema_breadcrumb_enabled' => $request->boolean('schema_breadcrumb_enabled'),
            
            // Performance
            'performance_enabled' => $request->boolean('performance_enabled'),
            'minify_html' => $request->boolean('minify_html'),
            'minify_css' => $request->boolean('minify_css'),
            'minify_js' => $request->boolean('minify_js'),
            'enable_gzip' => $request->boolean('enable_gzip'),
            'enable_browser_caching' => $request->boolean('enable_browser_caching'),
            'cache_duration' => $request->input('cache_duration'),
            'lazy_loading_enabled' => $request->boolean('lazy_loading_enabled'),
            'image_optimization_enabled' => $request->boolean('image_optimization_enabled'),
            'webp_conversion_enabled' => $request->boolean('webp_conversion_enabled'),
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

    public function generateSitemap()
    {
        try {
            $sitemap = $this->buildSitemap();
            
            // Save sitemap to public directory
            $sitemapPath = public_path('sitemap.xml');
            file_put_contents($sitemapPath, $sitemap);

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

    private function buildSitemap()
    {
        $baseUrl = config('app.url');
        $currentDate = now()->toISOString();
        $seoSettings = config('seo', []);

        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";

        // Home page
        $xml .= $this->addSitemapUrl(
            $baseUrl, 
            $currentDate, 
            $seoSettings['sitemap_priority_home'] ?? '1.0',
            $seoSettings['sitemap_changefreq_home'] ?? 'daily'
        );

        // Blog posts
        if ($seoSettings['sitemap_include_blogs'] ?? true) {
            $posts = \App\Models\Blog::published()->get();
            foreach ($posts as $post) {
                $xml .= $this->addSitemapUrl(
                    $baseUrl . '/blog/' . $post->slug,
                    $post->published_at ? $post->published_at->toISOString() : $currentDate,
                    $seoSettings['sitemap_priority_blogs'] ?? '0.8',
                    $seoSettings['sitemap_changefreq_blogs'] ?? 'weekly'
                );
            }
        }

        // Products
        if ($seoSettings['sitemap_include_products'] ?? true) {
            $products = \App\Models\Product::active()->get();
            foreach ($products as $product) {
                $xml .= $this->addSitemapUrl(
                    $baseUrl . '/products/' . $product->slug,
                    $product->created_at->toISOString(),
                    $seoSettings['sitemap_priority_products'] ?? '0.7',
                    $seoSettings['sitemap_changefreq_products'] ?? 'weekly'
                );
            }
        }

        // Static pages
        $staticPages = [
            '/faq' => ['priority' => '0.6', 'changefreq' => 'monthly'],
            '/how-to-save' => ['priority' => '0.6', 'changefreq' => 'monthly'],
            '/popular-videos' => ['priority' => '0.7', 'changefreq' => 'daily'],
            '/privacy' => ['priority' => '0.3', 'changefreq' => 'yearly'],
            '/tos' => ['priority' => '0.3', 'changefreq' => 'yearly'],
        ];

        foreach ($staticPages as $page => $settings) {
            $xml .= $this->addSitemapUrl(
                $baseUrl . $page, 
                $currentDate, 
                $settings['priority'], 
                $settings['changefreq']
            );
        }

        $xml .= '</urlset>';

        return $xml;
    }

    private function addSitemapUrl($url, $lastmod, $priority, $changefreq)
    {
        return "  <url>\n" .
               "    <loc>" . htmlspecialchars($url) . "</loc>\n" .
               "    <lastmod>" . $lastmod . "</lastmod>\n" .
               "    <changefreq>" . $changefreq . "</changefreq>\n" .
               "    <priority>" . $priority . "</priority>\n" .
               "  </url>\n";
    }
}
