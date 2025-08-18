<?php

namespace App\Console\Commands;

use App\Service\SeoService;
use Illuminate\Console\Command;

class GenerateSitemap extends Command
{
    protected $signature = 'seo:generate-sitemap';
    protected $description = 'Generate XML sitemap for SEO';

    public function handle()
    {
        $this->info('Generating sitemap...');

        try {
            $seoService = app(SeoService::class);
            $sitemap = $seoService->generateSitemap();
            
            $sitemapPath = public_path('sitemap.xml');
            file_put_contents($sitemapPath, $sitemap);
            
            $this->info('Sitemap generated successfully at: ' . $sitemapPath);
            $this->info('Sitemap URL: ' . config('app.url') . '/sitemap.xml');
            
        } catch (\Exception $e) {
            $this->error('Failed to generate sitemap: ' . $e->getMessage());
            return 1;
        }

        return 0;
    }
}
