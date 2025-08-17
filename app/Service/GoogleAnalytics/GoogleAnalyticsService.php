<?php

namespace App\Service\GoogleAnalytics;

class GoogleAnalyticsService
{
    /**
     * Check if Google Analytics is enabled
     */
    public function isEnabled(): bool
    {
        return config('analytics.ga_enabled', false) && 
               !empty(config('analytics.ga_measurement_id'));
    }

    /**
     * Get the measurement ID
     */
    public function getMeasurementId(): ?string
    {
        return config('analytics.ga_measurement_id');
    }

    /**
     * Check if page views should be tracked
     */
    public function shouldTrackPageviews(): bool
    {
        return config('analytics.ga_track_pageviews', true);
    }

    /**
     * Check if events should be tracked
     */
    public function shouldTrackEvents(): bool
    {
        return config('analytics.ga_track_events', true);
    }

    /**
     * Check if downloads should be tracked
     */
    public function shouldTrackDownloads(): bool
    {
        return config('analytics.ga_track_downloads', true);
    }

    /**
     * Check if donations should be tracked
     */
    public function shouldTrackDonations(): bool
    {
        return config('analytics.ga_track_donations', true);
    }

    /**
     * Check if IP addresses should be anonymized
     */
    public function shouldAnonymizeIp(): bool
    {
        return config('analytics.ga_anonymize_ip', true);
    }

    /**
     * Check if debug mode is enabled
     */
    public function isDebugMode(): bool
    {
        return config('analytics.ga_debug_mode', false);
    }

    /**
     * Get the Google Analytics configuration for frontend
     */
    public function getConfig(): array
    {
        return [
            'enabled' => $this->isEnabled(),
            'measurement_id' => $this->getMeasurementId(),
            'track_pageviews' => $this->shouldTrackPageviews(),
            'track_events' => $this->shouldTrackEvents(),
            'track_downloads' => $this->shouldTrackDownloads(),
            'track_donations' => $this->shouldTrackDonations(),
            'anonymize_ip' => $this->shouldAnonymizeIp(),
            'debug_mode' => $this->isDebugMode(),
        ];
    }

    /**
     * Generate the Google Analytics tracking code
     */
    public function getTrackingCode(): string
    {
        if (!$this->isEnabled()) {
            return '';
        }

        $measurementId = $this->getMeasurementId();
        $debugMode = $this->isDebugMode() ? '?debug_mode=1' : '';

        return "
        <!-- Google Analytics 4 -->
        <script async src=\"https://www.googletagmanager.com/gtag/js?id={$measurementId}{$debugMode}\"></script>
        <script>
            window.dataLayer = window.dataLayer || [];
            function gtag(){dataLayer.push(arguments);}
            gtag('js', new Date());
            gtag('config', '{$measurementId}', {
                'anonymize_ip': " . ($this->shouldAnonymizeIp() ? 'true' : 'false') . ",
                'send_page_view': " . ($this->shouldTrackPageviews() ? 'true' : 'false') . "
            });
            
            // Custom event tracking
            window.gaTrackEvent = function(eventName, parameters = {}) {
                if (typeof gtag !== 'undefined') {
                    gtag('event', eventName, parameters);
                    " . ($this->isDebugMode() ? "console.log('GA4 Event:', eventName, parameters);" : "") . "
                }
            };
            
            // Track downloads
            window.gaTrackDownload = function(videoUrl, quality) {
                if (typeof gtag !== 'undefined') {
                    gtag('event', 'download', {
                        'event_category': 'video',
                        'event_label': quality,
                        'value': 1
                    });
                    " . ($this->isDebugMode() ? "console.log('GA4 Download Event:', {videoUrl, quality});" : "") . "
                }
            };
            
            // Track donations
            window.gaTrackDonation = function(amount, currency) {
                if (typeof gtag !== 'undefined') {
                    gtag('event', 'purchase', {
                        'currency': currency,
                        'value': amount,
                        'transaction_id': 'donation_' + Date.now()
                    });
                    " . ($this->isDebugMode() ? "console.log('GA4 Donation Event:', {amount, currency});" : "") . "
                }
            };
        </script>";
    }
}
