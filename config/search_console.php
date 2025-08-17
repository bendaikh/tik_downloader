<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Google Search Console Configuration
    |--------------------------------------------------------------------------
    |
    | This file contains the configuration for Google Search Console integration.
    |
    */

    'gsc_enabled' => env('GSC_ENABLED', false),
    'gsc_verification_method' => env('GSC_VERIFICATION_METHOD', null),
    'gsc_meta_tag' => env('GSC_META_TAG', ''),
    'gsc_html_file_content' => env('GSC_HTML_FILE_CONTENT', ''),
    'gsc_html_filename' => env('GSC_HTML_FILENAME', ''),
    'gsc_dns_record' => env('GSC_DNS_RECORD', ''),
    'gsc_property_url' => env('GSC_PROPERTY_URL', ''),
    'gsc_auto_submit_sitemap' => env('GSC_AUTO_SUBMIT_SITEMAP', false),
    'gsc_sitemap_url' => env('GSC_SITEMAP_URL', ''),
];
