<?php

namespace Themes\TTDown\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SitemapController extends Controller
{
    public function __invoke(Request $request)
    {
        $sitemap = '<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <url>
        <loc>' . route('home') . '</loc>
        <lastmod>' . now()->toDateString() . '</lastmod>
        <changefreq>daily</changefreq>
        <priority>1.0</priority>
    </url>
    <url>
        <loc>' . route('faq') . '</loc>
        <lastmod>' . now()->toDateString() . '</lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.8</priority>
    </url>
    <url>
        <loc>' . route('how-to-save') . '</loc>
        <lastmod>' . now()->toDateString() . '</lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.8</priority>
    </url>
    <url>
        <loc>' . route('popular-videos') . '</loc>
        <lastmod>' . now()->toDateString() . '</lastmod>
        <changefreq>daily</changefreq>
        <priority>0.7</priority>
    </url>
    <url>
        <loc>' . route('privacy') . '</loc>
        <lastmod>' . now()->toDateString() . '</lastmod>
        <changefreq>monthly</changefreq>
        <priority>0.5</priority>
    </url>
    <url>
        <loc>' . route('tos') . '</loc>
        <lastmod>' . now()->toDateString() . '</lastmod>
        <changefreq>monthly</changefreq>
        <priority>0.5</priority>
    </url>
</urlset>';

        return response($sitemap, 200, [
            'Content-Type' => 'application/xml'
        ]);
    }
}
