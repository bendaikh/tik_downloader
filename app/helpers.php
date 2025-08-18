<?php

if (!function_exists('get_openssl_version_number')) {

    /**
     * Parse OPENSSL_VERSION_NUMBER constant to
     * use in version_compare function
     * @param null $openssl_version_number
     * @param boolean $patch_as_number [description]
     * @return false|string [type]                          [description]
     */
    function get_openssl_version_number($openssl_version_number = null, bool $patch_as_number = false): bool|string
    {
        if (is_null($openssl_version_number)) $openssl_version_number = OPENSSL_VERSION_NUMBER;
        $openssl_numeric_identifier = str_pad((string)dechex($openssl_version_number), 8, '0', STR_PAD_LEFT);

        $openssl_version_parsed = array();
        $preg = '/(?<major>[[:xdigit:]])(?<minor>[[:xdigit:]][[:xdigit:]])(?<fix>[[:xdigit:]][[:xdigit:]])';
        $preg .= '(?<patch>[[:xdigit:]][[:xdigit:]])(?<type>[[:xdigit:]])/';
        preg_match_all($preg, $openssl_numeric_identifier, $openssl_version_parsed);
        $openssl_version = false;
        if (!empty($openssl_version_parsed)) {
            $alphabet = array(1 => 'a', 2 => 'b', 3 => 'c', 4 => 'd', 5 => 'e', 6 => 'f', 7 => 'g', 8 => 'h', 9 => 'i', 10 => 'j', 11 => 'k',
                12 => 'l', 13 => 'm', 14 => 'n', 15 => 'o', 16 => 'p', 17 => 'q', 18 => 'r', 19 => 's', 20 => 't', 21 => 'u',
                22 => 'v', 23 => 'w', 24 => 'x', 25 => 'y', 26 => 'z');
            $openssl_version = intval($openssl_version_parsed['major'][0]) . '.';
            $openssl_version .= intval($openssl_version_parsed['minor'][0]) . '.';
            $openssl_version .= intval($openssl_version_parsed['fix'][0]);
            $patch_level_dec = hexdec($openssl_version_parsed['patch'][0]);
            if (!$patch_as_number && array_key_exists($patch_level_dec, $alphabet)) {
                $openssl_version .= $alphabet[$patch_level_dec]; // ideal for text comparison
            } else {
                $openssl_version .= '.' . $patch_level_dec; // ideal for version_compare
            }
        }
        return $openssl_version;
    }
}

if (!function_exists('seo_meta_tags')) {
    /**
     * Generate SEO meta tags for a page
     */
    function seo_meta_tags($pageData = [])
    {
        $seoService = app(\App\Service\SeoService::class);
        return $seoService->generateMetaTags($pageData);
    }
}

if (!function_exists('seo_structured_data')) {
    /**
     * Generate structured data for a page
     */
    function seo_structured_data($pageData = [])
    {
        $seoService = app(\App\Service\SeoService::class);
        return $seoService->generateStructuredData($pageData);
    }
}

if (!function_exists('seo_title')) {
    /**
     * Get SEO title for current page
     */
    function seo_title($fallback = null)
    {
        $seoSettings = config('seo', []);
        return $seoSettings['seo_title'] ?? $fallback ?? config('app.name');
    }
}

if (!function_exists('seo_description')) {
    /**
     * Get SEO description for current page
     */
    function seo_description($fallback = null)
    {
        $seoSettings = config('seo', []);
        return $seoSettings['seo_description'] ?? $fallback ?? 'TikTok Video Downloader - Download videos without watermark';
    }
}

if (!function_exists('seo_keywords')) {
    /**
     * Get SEO keywords
     */
    function seo_keywords($fallback = null)
    {
        $seoSettings = config('seo', []);
        return $seoSettings['seo_keywords'] ?? $fallback ?? 'tiktok downloader, tiktok video download, download tiktok without watermark';
    }
}

if (!function_exists('seo_og_image')) {
    /**
     * Get Open Graph image URL
     */
    function seo_og_image($fallback = null)
    {
        $seoSettings = config('seo', []);
        return $seoSettings['og_image'] ?? $fallback ?? asset('images/og-image.jpg');
    }
}

if (!function_exists('seo_canonical_url')) {
    /**
     * Get canonical URL for current page
     */
    function seo_canonical_url()
    {
        $seoSettings = config('seo', []);
        return $seoSettings['seo_canonical'] ?? request()->url();
    }
}

if (!function_exists('seo_robots')) {
    /**
     * Get robots meta tag value
     */
    function seo_robots()
    {
        $seoSettings = config('seo', []);
        return $seoSettings['seo_robots'] ?? 'index,follow';
    }
}

if (!function_exists('seo_twitter_card')) {
    /**
     * Get Twitter card type
     */
    function seo_twitter_card()
    {
        $seoSettings = config('seo', []);
        return $seoSettings['twitter_card'] ?? 'summary_large_image';
    }
}

if (!function_exists('seo_twitter_site')) {
    /**
     * Get Twitter site username
     */
    function seo_twitter_site()
    {
        $seoSettings = config('seo', []);
        return $seoSettings['twitter_site'] ?? null;
    }
}

if (!function_exists('seo_twitter_creator')) {
    /**
     * Get Twitter creator username
     */
    function seo_twitter_creator()
    {
        $seoSettings = config('seo', []);
        return $seoSettings['twitter_creator'] ?? null;
    }
}

if (!function_exists('seo_organization_name')) {
    /**
     * Get organization name for structured data
     */
    function seo_organization_name()
    {
        $seoSettings = config('seo', []);
        return $seoSettings['structured_data_name'] ?? config('app.name');
    }
}

if (!function_exists('seo_organization_logo')) {
    /**
     * Get organization logo URL
     */
    function seo_organization_logo()
    {
        $seoSettings = config('seo', []);
        return $seoSettings['structured_data_logo'] ?? asset('images/logo.png');
    }
}

if (!function_exists('seo_contact_email')) {
    /**
     * Get contact email for structured data
     */
    function seo_contact_email()
    {
        $seoSettings = config('seo', []);
        return $seoSettings['structured_data_contact_email'] ?? null;
    }
}

if (!function_exists('seo_contact_phone')) {
    /**
     * Get contact phone for structured data
     */
    function seo_contact_phone()
    {
        $seoSettings = config('seo', []);
        return $seoSettings['structured_data_contact_phone'] ?? null;
    }
}

if (!function_exists('seo_address')) {
    /**
     * Get organization address for structured data
     */
    function seo_address()
    {
        $seoSettings = config('seo', []);
        return [
            'street' => $seoSettings['structured_data_address'] ?? null,
            'city' => $seoSettings['structured_data_city'] ?? null,
            'state' => $seoSettings['structured_data_state'] ?? null,
            'zip' => $seoSettings['structured_data_zip'] ?? null,
            'country' => $seoSettings['structured_data_country'] ?? null,
        ];
    }
}

if (!function_exists('seo_sitemap_enabled')) {
    /**
     * Check if sitemap is enabled
     */
    function seo_sitemap_enabled()
    {
        $seoSettings = config('seo', []);
        return !empty($seoSettings['sitemap_enabled']);
    }
}

if (!function_exists('seo_sitemap_url')) {
    /**
     * Get sitemap URL
     */
    function seo_sitemap_url()
    {
        return config('app.url') . '/sitemap.xml';
    }
}

if (!function_exists('seo_robots_url')) {
    /**
     * Get robots.txt URL
     */
    function seo_robots_url()
    {
        return config('app.url') . '/robots.txt';
    }
}

if (!function_exists('seo_performance_enabled')) {
    /**
     * Check if performance optimization is enabled
     */
    function seo_performance_enabled()
    {
        $seoSettings = config('seo', []);
        return !empty($seoSettings['performance_enabled']);
    }
}

if (!function_exists('seo_minify_html')) {
    /**
     * Check if HTML minification is enabled
     */
    function seo_minify_html()
    {
        $seoSettings = config('seo', []);
        return !empty($seoSettings['minify_html']);
    }
}

if (!function_exists('seo_minify_css')) {
    /**
     * Check if CSS minification is enabled
     */
    function seo_minify_css()
    {
        $seoSettings = config('seo', []);
        return !empty($seoSettings['minify_css']);
    }
}

if (!function_exists('seo_minify_js')) {
    /**
     * Check if JavaScript minification is enabled
     */
    function seo_minify_js()
    {
        $seoSettings = config('seo', []);
        return !empty($seoSettings['minify_js']);
    }
}

if (!function_exists('seo_enable_gzip')) {
    /**
     * Check if Gzip compression is enabled
     */
    function seo_enable_gzip()
    {
        $seoSettings = config('seo', []);
        return !empty($seoSettings['enable_gzip']);
    }
}

if (!function_exists('seo_enable_browser_caching')) {
    /**
     * Check if browser caching is enabled
     */
    function seo_enable_browser_caching()
    {
        $seoSettings = config('seo', []);
        return !empty($seoSettings['enable_browser_caching']);
    }
}

if (!function_exists('seo_cache_duration')) {
    /**
     * Get cache duration in seconds
     */
    function seo_cache_duration()
    {
        $seoSettings = config('seo', []);
        return $seoSettings['cache_duration'] ?? 86400; // 24 hours default
    }
}

if (!function_exists('seo_lazy_loading_enabled')) {
    /**
     * Check if lazy loading is enabled
     */
    function seo_lazy_loading_enabled()
    {
        $seoSettings = config('seo', []);
        return !empty($seoSettings['lazy_loading_enabled']);
    }
}

if (!function_exists('seo_image_optimization_enabled')) {
    /**
     * Check if image optimization is enabled
     */
    function seo_image_optimization_enabled()
    {
        $seoSettings = config('seo', []);
        return !empty($seoSettings['image_optimization_enabled']);
    }
}

if (!function_exists('seo_webp_conversion_enabled')) {
    /**
     * Check if WebP conversion is enabled
     */
    function seo_webp_conversion_enabled()
    {
        $seoSettings = config('seo', []);
        return !empty($seoSettings['webp_conversion_enabled']);
    }
}

if (!function_exists('seo_structured_data_enabled')) {
    /**
     * Check if structured data is enabled
     */
    function seo_structured_data_enabled()
    {
        $seoSettings = config('seo', []);
        return !empty($seoSettings['structured_data_enabled']);
    }
}

if (!function_exists('seo_schema_organization_enabled')) {
    /**
     * Check if organization schema is enabled
     */
    function seo_schema_organization_enabled()
    {
        $seoSettings = config('seo', []);
        return !empty($seoSettings['schema_organization_enabled']);
    }
}

if (!function_exists('seo_schema_website_enabled')) {
    /**
     * Check if website schema is enabled
     */
    function seo_schema_website_enabled()
    {
        $seoSettings = config('seo', []);
        return !empty($seoSettings['schema_website_enabled']);
    }
}

if (!function_exists('seo_schema_webpage_enabled')) {
    /**
     * Check if webpage schema is enabled
     */
    function seo_schema_webpage_enabled()
    {
        $seoSettings = config('seo', []);
        return !empty($seoSettings['schema_webpage_enabled']);
    }
}

if (!function_exists('seo_schema_article_enabled')) {
    /**
     * Check if article schema is enabled
     */
    function seo_schema_article_enabled()
    {
        $seoSettings = config('seo', []);
        return !empty($seoSettings['schema_article_enabled']);
    }
}

if (!function_exists('seo_schema_product_enabled')) {
    /**
     * Check if product schema is enabled
     */
    function seo_schema_product_enabled()
    {
        $seoSettings = config('seo', []);
        return !empty($seoSettings['schema_product_enabled']);
    }
}

if (!function_exists('seo_schema_faq_enabled')) {
    /**
     * Check if FAQ schema is enabled
     */
    function seo_schema_faq_enabled()
    {
        $seoSettings = config('seo', []);
        return !empty($seoSettings['schema_faq_enabled']);
    }
}

if (!function_exists('seo_schema_breadcrumb_enabled')) {
    /**
     * Check if breadcrumb schema is enabled
     */
    function seo_schema_breadcrumb_enabled()
    {
        $seoSettings = config('seo', []);
        return !empty($seoSettings['schema_breadcrumb_enabled']);
    }
}
