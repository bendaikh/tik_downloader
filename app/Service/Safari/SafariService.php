<?php

namespace App\Service\Safari;

class SafariService
{
    /**
     * Check if Safari Analytics is enabled
     */
    public function isEnabled(): bool
    {
        return config('safari.enabled', false) && 
               !empty(config('safari.website_id'));
    }

    /**
     * Get the website ID
     */
    public function getWebsiteId(): ?string
    {
        return config('safari.website_id');
    }

    /**
     * Check if page views should be tracked
     */
    public function shouldTrackPageviews(): bool
    {
        return config('safari.track_pageviews', true);
    }

    /**
     * Check if events should be tracked
     */
    public function shouldTrackEvents(): bool
    {
        return config('safari.track_events', true);
    }

    /**
     * Check if downloads should be tracked
     */
    public function shouldTrackDownloads(): bool
    {
        return config('safari.track_downloads', true);
    }

    /**
     * Check if donations should be tracked
     */
    public function shouldTrackDonations(): bool
    {
        return config('safari.track_donations', true);
    }

    /**
     * Check if user engagement should be tracked
     */
    public function shouldTrackEngagement(): bool
    {
        return config('safari.track_engagement', true);
    }

    /**
     * Check if debug mode is enabled
     */
    public function isDebugMode(): bool
    {
        return config('safari.debug_mode', false);
    }

    /**
     * Get the Safari Analytics configuration for frontend
     */
    public function getConfig(): array
    {
        return [
            'enabled' => $this->isEnabled(),
            'website_id' => $this->getWebsiteId(),
            'track_pageviews' => $this->shouldTrackPageviews(),
            'track_events' => $this->shouldTrackEvents(),
            'track_downloads' => $this->shouldTrackDownloads(),
            'track_donations' => $this->shouldTrackDonations(),
            'track_engagement' => $this->shouldTrackEngagement(),
            'debug_mode' => $this->isDebugMode(),
        ];
    }

    /**
     * Generate the Safari Analytics tracking code
     */
    public function getTrackingCode(): string
    {
        if (!$this->isEnabled()) {
            return '';
        }

        $websiteId = $this->getWebsiteId();
        $debugMode = $this->isDebugMode() ? '?debug=1' : '';

        return "
        <!-- Safari Analytics -->
        <script>
            (function(s,a,f,a,r,i) {
                s[r]=s[r]||function(){(s[r].q=s[r].q||[]).push(arguments)};
                i=a.createElement(f);i.async=1;i.src='https://analytics.safari.com/analytics.js'{$debugMode};
                a.getElementsByTagName(f)[0].parentNode.appendChild(i);
            })(window,document,'script','{$websiteId}','safari');
            
            safari('init', '{$websiteId}');
            " . ($this->shouldTrackPageviews() ? "safari('pageview');" : "") . "
            
            // Custom event tracking
            window.safariTrackEvent = function(eventName, parameters = {}) {
                if (typeof safari !== 'undefined') {
                    safari('event', eventName, parameters);
                    " . ($this->isDebugMode() ? "console.log('Safari Event:', eventName, parameters);" : "") . "
                }
            };
            
            // Track downloads
            window.safariTrackDownload = function(videoUrl, quality) {
                if (typeof safari !== 'undefined') {
                    safari('event', 'download', {
                        'event_category': 'video',
                        'event_label': quality,
                        'value': 1
                    });
                    " . ($this->isDebugMode() ? "console.log('Safari Download Event:', {videoUrl, quality});" : "") . "
                }
            };
            
            // Track donations
            window.safariTrackDonation = function(amount, currency) {
                if (typeof safari !== 'undefined') {
                    safari('event', 'purchase', {
                        'currency': currency,
                        'value': amount,
                        'transaction_id': 'donation_' + Date.now()
                    });
                    " . ($this->isDebugMode() ? "console.log('Safari Donation Event:', {amount, currency});" : "") . "
                }
            };
            
            // Track user engagement
            window.safariTrackEngagement = function(engagementType, duration) {
                if (typeof safari !== 'undefined') {
                    safari('event', 'engagement', {
                        'event_category': 'user_engagement',
                        'event_label': engagementType,
                        'value': duration
                    });
                    " . ($this->isDebugMode() ? "console.log('Safari Engagement Event:', {engagementType, duration});" : "") . "
                }
            };
        </script>";
    }
}
