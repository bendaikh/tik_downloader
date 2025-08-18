<?php

return [
    /*
    |--------------------------------------------------------------------------
    | SEO Configuration
    |--------------------------------------------------------------------------
    |
    | This file contains all the SEO-related configuration settings for the
    | TikTok downloader application.
    |
    */

    // Basic SEO
    'seo_title' => env('SEO_TITLE', 'TikTok Video Downloader - Download Without Watermark'),
    'seo_description' => env('SEO_DESCRIPTION', 'Download TikTok videos without watermark for free. Fast, secure, and easy to use TikTok video downloader.'),
    'seo_keywords' => env('SEO_KEYWORDS', 'tiktok downloader, tiktok video download, download tiktok without watermark'),
    'seo_author' => env('SEO_AUTHOR', ''),
    'seo_robots' => env('SEO_ROBOTS', 'index,follow'),
    'seo_canonical' => env('SEO_CANONICAL', ''),

    // Open Graph
    'og_title' => env('OG_TITLE', 'TikTok Video Downloader - Free Download'),
    'og_description' => env('OG_DESCRIPTION', 'Download TikTok videos without watermark instantly. Free, fast, and secure.'),
    'og_image' => env('OG_IMAGE', ''),
    'og_type' => env('OG_TYPE', 'website'),

    // Twitter Cards
    'twitter_card' => env('TWITTER_CARD', 'summary_large_image'),
    'twitter_site' => env('TWITTER_SITE', ''),
    'twitter_creator' => env('TWITTER_CREATOR', ''),

    // Structured Data
    'structured_data_enabled' => env('STRUCTURED_DATA_ENABLED', false),
    'structured_data_type' => env('STRUCTURED_DATA_TYPE', 'Organization'),
    'structured_data_name' => env('STRUCTURED_DATA_NAME', ''),
    'structured_data_description' => env('STRUCTURED_DATA_DESCRIPTION', ''),
    'structured_data_url' => env('STRUCTURED_DATA_URL', ''),
    'structured_data_logo' => env('STRUCTURED_DATA_LOGO', ''),
    'structured_data_contact_email' => env('STRUCTURED_DATA_CONTACT_EMAIL', ''),
    'structured_data_contact_phone' => env('STRUCTURED_DATA_CONTACT_PHONE', ''),
    'structured_data_address' => env('STRUCTURED_DATA_ADDRESS', ''),
    'structured_data_city' => env('STRUCTURED_DATA_CITY', ''),
    'structured_data_state' => env('STRUCTURED_DATA_STATE', ''),
    'structured_data_zip' => env('STRUCTURED_DATA_ZIP', ''),
    'structured_data_country' => env('STRUCTURED_DATA_COUNTRY', ''),

    // Sitemap
    'sitemap_enabled' => env('SITEMAP_ENABLED', true),
    'sitemap_auto_generate' => env('SITEMAP_AUTO_GENERATE', true),
    'sitemap_include_blogs' => env('SITEMAP_INCLUDE_BLOGS', true),
    'sitemap_include_products' => env('SITEMAP_INCLUDE_PRODUCTS', true),
    'sitemap_priority_home' => env('SITEMAP_PRIORITY_HOME', '1.0'),
    'sitemap_priority_blogs' => env('SITEMAP_PRIORITY_BLOGS', '0.8'),
    'sitemap_priority_products' => env('SITEMAP_PRIORITY_PRODUCTS', '0.7'),
    'sitemap_changefreq_home' => env('SITEMAP_CHANGEFREQ_HOME', 'daily'),
    'sitemap_changefreq_blogs' => env('SITEMAP_CHANGEFREQ_BLOGS', 'weekly'),
    'sitemap_changefreq_products' => env('SITEMAP_CHANGEFREQ_PRODUCTS', 'weekly'),

    // Schema Markup
    'schema_markup_enabled' => env('SCHEMA_MARKUP_ENABLED', false),
    'schema_organization_enabled' => env('SCHEMA_ORGANIZATION_ENABLED', false),
    'schema_website_enabled' => env('SCHEMA_WEBSITE_ENABLED', false),
    'schema_webpage_enabled' => env('SCHEMA_WEBPAGE_ENABLED', false),
    'schema_article_enabled' => env('SCHEMA_ARTICLE_ENABLED', false),
    'schema_product_enabled' => env('SCHEMA_PRODUCT_ENABLED', false),
    'schema_faq_enabled' => env('SCHEMA_FAQ_ENABLED', false),
    'schema_breadcrumb_enabled' => env('SCHEMA_BREADCRUMB_ENABLED', false),

    // Performance
    'performance_enabled' => env('PERFORMANCE_ENABLED', false),
    'minify_html' => env('MINIFY_HTML', false),
    'minify_css' => env('MINIFY_CSS', false),
    'minify_js' => env('MINIFY_JS', false),
    'enable_gzip' => env('ENABLE_GZIP', false),
    'enable_browser_caching' => env('ENABLE_BROWSER_CACHING', false),
    'cache_duration' => env('CACHE_DURATION', 86400),
    'lazy_loading_enabled' => env('LAZY_LOADING_ENABLED', false),
    'image_optimization_enabled' => env('IMAGE_OPTIMIZATION_ENABLED', false),
    'webp_conversion_enabled' => env('WEBP_CONVERSION_ENABLED', false),
];
