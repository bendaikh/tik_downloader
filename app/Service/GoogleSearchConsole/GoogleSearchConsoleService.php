<?php

namespace App\Service\GoogleSearchConsole;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GoogleSearchConsoleService
{
    /**
     * Check if Google Search Console is enabled
     */
    public function isEnabled(): bool
    {
        return config('search_console.gsc_enabled', false);
    }

    /**
     * Get the verification method
     */
    public function getVerificationMethod(): ?string
    {
        return config('search_console.gsc_verification_method');
    }

    /**
     * Get the property URL
     */
    public function getPropertyUrl(): ?string
    {
        return config('search_console.gsc_property_url');
    }

    /**
     * Get the sitemap URL
     */
    public function getSitemapUrl(): ?string
    {
        return config('search_console.gsc_sitemap_url');
    }

    /**
     * Check if auto-submit sitemap is enabled
     */
    public function shouldAutoSubmitSitemap(): bool
    {
        return config('search_console.gsc_auto_submit_sitemap', false);
    }

    /**
     * Get the Google Search Console configuration for frontend
     */
    public function getConfig(): array
    {
        return [
            'enabled' => $this->isEnabled(),
            'verification_method' => $this->getVerificationMethod(),
            'property_url' => $this->getPropertyUrl(),
            'sitemap_url' => $this->getSitemapUrl(),
            'auto_submit_sitemap' => $this->shouldAutoSubmitSitemap(),
        ];
    }

    /**
     * Submit sitemap to Google Search Console (placeholder for future API integration)
     */
    public function submitSitemap(string $sitemapUrl): bool
    {
        if (!$this->isEnabled() || !$this->shouldAutoSubmitSitemap()) {
            return false;
        }

        try {
            // Note: This is a placeholder for future Google Search Console API integration
            // Currently, sitemaps need to be submitted manually through the Google Search Console interface
            Log::info('Sitemap submission requested', [
                'sitemap_url' => $sitemapUrl,
                'property_url' => $this->getPropertyUrl()
            ]);

            return true;
        } catch (\Exception $e) {
            Log::error('Failed to submit sitemap to Google Search Console', [
                'error' => $e->getMessage(),
                'sitemap_url' => $sitemapUrl
            ]);

            return false;
        }
    }

    /**
     * Verify website ownership (placeholder for future API integration)
     */
    public function verifyOwnership(): bool
    {
        if (!$this->isEnabled()) {
            return false;
        }

        $method = $this->getVerificationMethod();
        
        switch ($method) {
            case 'html_file':
                return $this->verifyHtmlFile();
            case 'meta_tag':
                return $this->verifyMetaTag();
            case 'dns_record':
                return $this->verifyDnsRecord();
            default:
                return false;
        }
    }

    /**
     * Verify HTML file method
     */
    private function verifyHtmlFile(): bool
    {
        $filename = config('search_console.gsc_html_filename');
        if (!$filename) {
            return false;
        }

        $filePath = public_path($filename);
        return file_exists($filePath);
    }

    /**
     * Verify meta tag method
     */
    private function verifyMetaTag(): bool
    {
        return !empty(config('search_console.gsc_meta_tag'));
    }

    /**
     * Verify DNS record method
     */
    private function verifyDnsRecord(): bool
    {
        return !empty(config('search_console.gsc_dns_record'));
    }

    /**
     * Get verification status
     */
    public function getVerificationStatus(): array
    {
        return [
            'enabled' => $this->isEnabled(),
            'method' => $this->getVerificationMethod(),
            'verified' => $this->verifyOwnership(),
            'property_url' => $this->getPropertyUrl(),
            'sitemap_available' => file_exists(public_path('sitemap.xml')),
            'sitemap_url' => $this->getSitemapUrl(),
        ];
    }
}
