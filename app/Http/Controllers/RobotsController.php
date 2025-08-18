<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;

class RobotsController extends Controller
{
    public function index()
    {
        $content = "User-agent: *\n";
        
        // Allow all by default, but you can customize based on SEO settings
        $robotsSetting = config('seo.seo_robots', 'index,follow');
        
        if ($robotsSetting === 'noindex,nofollow') {
            $content .= "Disallow: /\n";
        } elseif ($robotsSetting === 'noindex,follow') {
            $content .= "Disallow: /\n";
        } else {
            // Allow all pages
            $content .= "Allow: /\n";
        }
        
        // Disallow admin panel
        $content .= "Disallow: /admin/\n";
        $content .= "Disallow: /installer/\n";
        
        // Allow sitemap if enabled
        if (config('seo.sitemap_enabled', false)) {
            $content .= "\nSitemap: " . config('app.url') . "/sitemap.xml\n";
        }
        
        return response($content, 200, [
            'Content-Type' => 'text/plain',
        ]);
    }
}
