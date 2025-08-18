<?php

namespace App\Service;

class SeoService
{
    private $seoSettings;

    public function __construct()
    {
        $this->seoSettings = config('seo', []);
    }

    /**
     * Generate meta tags for the current page
     */
    public function generateMetaTags($pageData = [])
    {
        $metaTags = [];

        // Basic SEO
        if (!empty($this->seoSettings['seo_title']) || !empty($pageData['title'])) {
            $metaTags['title'] = $pageData['title'] ?? $this->seoSettings['seo_title'];
        }

        if (!empty($this->seoSettings['seo_description']) || !empty($pageData['description'])) {
            $metaTags['description'] = $pageData['description'] ?? $this->seoSettings['seo_description'];
        }

        if (!empty($this->seoSettings['seo_keywords'])) {
            $metaTags['keywords'] = $this->seoSettings['seo_keywords'];
        }

        if (!empty($this->seoSettings['seo_author'])) {
            $metaTags['author'] = $this->seoSettings['seo_author'];
        }

        if (!empty($this->seoSettings['seo_robots'])) {
            $metaTags['robots'] = $this->seoSettings['seo_robots'];
        }

        if (!empty($this->seoSettings['seo_canonical'])) {
            $metaTags['canonical'] = $this->seoSettings['seo_canonical'];
        }

        // Open Graph
        if (!empty($this->seoSettings['og_title']) || !empty($pageData['og_title'])) {
            $metaTags['og:title'] = $pageData['og_title'] ?? $this->seoSettings['og_title'];
        }

        if (!empty($this->seoSettings['og_description']) || !empty($pageData['og_description'])) {
            $metaTags['og:description'] = $pageData['og_description'] ?? $this->seoSettings['og_description'];
        }

        if (!empty($this->seoSettings['og_image']) || !empty($pageData['og_image'])) {
            $metaTags['og:image'] = $pageData['og_image'] ?? $this->seoSettings['og_image'];
        }

        if (!empty($this->seoSettings['og_type'])) {
            $metaTags['og:type'] = $this->seoSettings['og_type'];
        }

        $metaTags['og:url'] = request()->url();
        $metaTags['og:site_name'] = config('app.name');

        // Twitter Cards
        if (!empty($this->seoSettings['twitter_card'])) {
            $metaTags['twitter:card'] = $this->seoSettings['twitter_card'];
        }

        if (!empty($this->seoSettings['twitter_site'])) {
            $metaTags['twitter:site'] = '@' . ltrim($this->seoSettings['twitter_site'], '@');
        }

        if (!empty($this->seoSettings['twitter_creator'])) {
            $metaTags['twitter:creator'] = '@' . ltrim($this->seoSettings['twitter_creator'], '@');
        }

        // Use OG data for Twitter if not specifically set
        if (!empty($metaTags['og:title']) && empty($metaTags['twitter:title'])) {
            $metaTags['twitter:title'] = $metaTags['og:title'];
        }

        if (!empty($metaTags['og:description']) && empty($metaTags['twitter:description'])) {
            $metaTags['twitter:description'] = $metaTags['og:description'];
        }

        if (!empty($metaTags['og:image']) && empty($metaTags['twitter:image'])) {
            $metaTags['twitter:image'] = $metaTags['og:image'];
        }

        return $metaTags;
    }

    /**
     * Generate structured data (JSON-LD)
     */
    public function generateStructuredData($pageData = [])
    {
        if (empty($this->seoSettings['structured_data_enabled'])) {
            return [];
        }

        $structuredData = [];

        // Organization Schema
        if (!empty($this->seoSettings['schema_organization_enabled'])) {
            $structuredData[] = $this->generateOrganizationSchema();
        }

        // Website Schema
        if (!empty($this->seoSettings['schema_website_enabled'])) {
            $structuredData[] = $this->generateWebsiteSchema();
        }

        // WebPage Schema
        if (!empty($this->seoSettings['schema_webpage_enabled'])) {
            $structuredData[] = $this->generateWebPageSchema($pageData);
        }

        // Article Schema (for blog posts)
        if (!empty($this->seoSettings['schema_article_enabled']) && !empty($pageData['article'])) {
            $structuredData[] = $this->generateArticleSchema($pageData['article']);
        }

        // Product Schema (for products)
        if (!empty($this->seoSettings['schema_product_enabled']) && !empty($pageData['product'])) {
            $structuredData[] = $this->generateProductSchema($pageData['product']);
        }

        // FAQ Schema
        if (!empty($this->seoSettings['schema_faq_enabled']) && !empty($pageData['faqs'])) {
            $structuredData[] = $this->generateFaqSchema($pageData['faqs']);
        }

        // Breadcrumb Schema
        if (!empty($this->seoSettings['schema_breadcrumb_enabled']) && !empty($pageData['breadcrumbs'])) {
            $structuredData[] = $this->generateBreadcrumbSchema($pageData['breadcrumbs']);
        }

        return $structuredData;
    }

    private function generateOrganizationSchema()
    {
        $schema = [
            '@context' => 'https://schema.org',
            '@type' => $this->seoSettings['structured_data_type'] ?? 'Organization',
            'name' => $this->seoSettings['structured_data_name'] ?? config('app.name'),
            'url' => $this->seoSettings['structured_data_url'] ?? config('app.url'),
        ];

        if (!empty($this->seoSettings['structured_data_description'])) {
            $schema['description'] = $this->seoSettings['structured_data_description'];
        }

        if (!empty($this->seoSettings['structured_data_logo'])) {
            $schema['logo'] = $this->seoSettings['structured_data_logo'];
        }

        // Contact information
        $contactPoint = [];
        if (!empty($this->seoSettings['structured_data_contact_email'])) {
            $contactPoint['email'] = $this->seoSettings['structured_data_contact_email'];
        }
        if (!empty($this->seoSettings['structured_data_contact_phone'])) {
            $contactPoint['telephone'] = $this->seoSettings['structured_data_contact_phone'];
        }

        if (!empty($contactPoint)) {
            $schema['contactPoint'] = [
                '@type' => 'ContactPoint',
                'contactType' => 'customer service',
                ...$contactPoint
            ];
        }

        // Address
        $address = [];
        if (!empty($this->seoSettings['structured_data_address'])) {
            $address['streetAddress'] = $this->seoSettings['structured_data_address'];
        }
        if (!empty($this->seoSettings['structured_data_city'])) {
            $address['addressLocality'] = $this->seoSettings['structured_data_city'];
        }
        if (!empty($this->seoSettings['structured_data_state'])) {
            $address['addressRegion'] = $this->seoSettings['structured_data_state'];
        }
        if (!empty($this->seoSettings['structured_data_zip'])) {
            $address['postalCode'] = $this->seoSettings['structured_data_zip'];
        }
        if (!empty($this->seoSettings['structured_data_country'])) {
            $address['addressCountry'] = $this->seoSettings['structured_data_country'];
        }

        if (!empty($address)) {
            $schema['address'] = [
                '@type' => 'PostalAddress',
                ...$address
            ];
        }

        return $schema;
    }

    private function generateWebsiteSchema()
    {
        return [
            '@context' => 'https://schema.org',
            '@type' => 'WebSite',
            'name' => config('app.name'),
            'url' => config('app.url'),
            'description' => $this->seoSettings['structured_data_description'] ?? 'TikTok Video Downloader - Download videos without watermark',
            'potentialAction' => [
                '@type' => 'SearchAction',
                'target' => config('app.url') . '/search?q={search_term_string}',
                'query-input' => 'required name=search_term_string'
            ]
        ];
    }

    private function generateWebPageSchema($pageData)
    {
        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'WebPage',
            'name' => $pageData['title'] ?? config('app.name'),
            'url' => request()->url(),
        ];

        if (!empty($pageData['description'])) {
            $schema['description'] = $pageData['description'];
        }

        if (!empty($pageData['image'])) {
            $schema['image'] = $pageData['image'];
        }

        if (!empty($pageData['datePublished'])) {
            $schema['datePublished'] = $pageData['datePublished'];
        }

        if (!empty($pageData['dateModified'])) {
            $schema['dateModified'] = $pageData['dateModified'];
        }

        return $schema;
    }

    private function generateArticleSchema($article)
    {
        return [
            '@context' => 'https://schema.org',
            '@type' => 'Article',
            'headline' => $article['title'] ?? '',
            'description' => $article['description'] ?? '',
            'image' => $article['image'] ?? '',
            'author' => [
                '@type' => 'Organization',
                'name' => $this->seoSettings['structured_data_name'] ?? config('app.name')
            ],
            'publisher' => [
                '@type' => 'Organization',
                'name' => $this->seoSettings['structured_data_name'] ?? config('app.name'),
                'logo' => [
                    '@type' => 'ImageObject',
                    'url' => $this->seoSettings['structured_data_logo'] ?? ''
                ]
            ],
            'datePublished' => $article['published_at'] ?? now()->toISOString(),
            'dateModified' => $article['updated_at'] ?? now()->toISOString(),
            'mainEntityOfPage' => [
                '@type' => 'WebPage',
                '@id' => request()->url()
            ]
        ];
    }

    private function generateProductSchema($product)
    {
        return [
            '@context' => 'https://schema.org',
            '@type' => 'Product',
            'name' => $product['name'] ?? '',
            'description' => $product['description'] ?? '',
            'image' => $product['image'] ?? '',
            'url' => $product['url'] ?? request()->url(),
            'brand' => [
                '@type' => 'Brand',
                'name' => $this->seoSettings['structured_data_name'] ?? config('app.name')
            ],
            'offers' => [
                '@type' => 'Offer',
                'price' => $product['price'] ?? '0',
                'priceCurrency' => $product['currency'] ?? 'USD',
                'availability' => 'https://schema.org/InStock'
            ]
        ];
    }

    private function generateFaqSchema($faqs)
    {
        $mainEntity = [];
        foreach ($faqs as $faq) {
            $mainEntity[] = [
                '@type' => 'Question',
                'name' => $faq['question'] ?? '',
                'acceptedAnswer' => [
                    '@type' => 'Answer',
                    'text' => $faq['answer'] ?? ''
                ]
            ];
        }

        return [
            '@context' => 'https://schema.org',
            '@type' => 'FAQPage',
            'mainEntity' => $mainEntity
        ];
    }

    private function generateBreadcrumbSchema($breadcrumbs)
    {
        $itemListElement = [];
        $position = 1;

        foreach ($breadcrumbs as $breadcrumb) {
            $itemListElement[] = [
                '@type' => 'ListItem',
                'position' => $position,
                'name' => $breadcrumb['name'] ?? '',
                'item' => $breadcrumb['url'] ?? ''
            ];
            $position++;
        }

        return [
            '@context' => 'https://schema.org',
            '@type' => 'BreadcrumbList',
            'itemListElement' => $itemListElement
        ];
    }

    /**
     * Apply performance optimizations
     */
    public function applyPerformanceOptimizations($content)
    {
        if (empty($this->seoSettings['performance_enabled'])) {
            return $content;
        }

        // Minify HTML
        if (!empty($this->seoSettings['minify_html'])) {
            $content = $this->minifyHtml($content);
        }

        // Minify CSS
        if (!empty($this->seoSettings['minify_css'])) {
            $content = $this->minifyCss($content);
        }

        // Minify JavaScript
        if (!empty($this->seoSettings['minify_js'])) {
            $content = $this->minifyJs($content);
        }

        // Add lazy loading to images
        if (!empty($this->seoSettings['lazy_loading_enabled'])) {
            $content = $this->addLazyLoading($content);
        }

        return $content;
    }

    private function minifyHtml($content)
    {
        // Remove comments (except IE conditional comments)
        $content = preg_replace('/<!--(?!\s*(?:\[if [^\]]+]|<!|>))(?:(?!-->).)*-->/s', '', $content);
        
        // Remove whitespace between tags
        $content = preg_replace('/>\s+</', '><', $content);
        
        // Remove whitespace at the beginning and end
        $content = trim($content);
        
        return $content;
    }

    private function minifyCss($content)
    {
        // Find and minify CSS in style tags
        $content = preg_replace_callback('/<style[^>]*>(.*?)<\/style>/s', function($matches) {
            $css = $matches[1];
            // Remove comments
            $css = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $css);
            // Remove whitespace
            $css = preg_replace('/\s+/', ' ', $css);
            // Remove unnecessary semicolons
            $css = str_replace(';}', '}', $css);
            return '<style>' . trim($css) . '</style>';
        }, $content);

        return $content;
    }

    private function minifyJs($content)
    {
        // Find and minify JavaScript in script tags
        $content = preg_replace_callback('/<script[^>]*>(.*?)<\/script>/s', function($matches) {
            $js = $matches[1];
            // Remove comments (simple approach)
            $js = preg_replace('/\/\*.*?\*\//s', '', $js);
            $js = preg_replace('/\/\/.*$/m', '', $js);
            // Remove extra whitespace
            $js = preg_replace('/\s+/', ' ', $js);
            return '<script>' . trim($js) . '</script>';
        }, $content);

        return $content;
    }

    private function addLazyLoading($content)
    {
        // Add loading="lazy" to img tags that don't already have it
        $content = preg_replace('/<img([^>]*?)(?<!loading="lazy")([^>]*?)>/i', '<img$1$2 loading="lazy">', $content);
        
        return $content;
    }

    /**
     * Generate sitemap content
     */
    public function generateSitemap()
    {
        $baseUrl = config('app.url');
        $currentDate = now()->toISOString();

        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";

        // Home page
        $xml .= $this->addSitemapUrl(
            $baseUrl, 
            $currentDate, 
            $this->seoSettings['sitemap_priority_home'] ?? '1.0',
            $this->seoSettings['sitemap_changefreq_home'] ?? 'daily'
        );

        // Blog posts
        if (!empty($this->seoSettings['sitemap_include_blogs'])) {
            $posts = \App\Models\Blog::published()->get();
            foreach ($posts as $post) {
                $xml .= $this->addSitemapUrl(
                    $baseUrl . '/blog/' . $post->slug,
                    $post->published_at ? $post->published_at->toISOString() : $currentDate,
                    $this->seoSettings['sitemap_priority_blogs'] ?? '0.8',
                    $this->seoSettings['sitemap_changefreq_blogs'] ?? 'weekly'
                );
            }
        }

        // Products
        if (!empty($this->seoSettings['sitemap_include_products'])) {
            $products = \App\Models\Product::active()->get();
            foreach ($products as $product) {
                $xml .= $this->addSitemapUrl(
                    $baseUrl . '/products/' . $product->slug,
                    $product->created_at->toISOString(),
                    $this->seoSettings['sitemap_priority_products'] ?? '0.7',
                    $this->seoSettings['sitemap_changefreq_products'] ?? 'weekly'
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
