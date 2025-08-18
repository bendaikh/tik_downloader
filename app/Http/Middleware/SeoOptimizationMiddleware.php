<?php

namespace App\Http\Middleware;

use App\Service\SeoService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SeoOptimizationMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Only apply to HTML responses
        if (!$this->isHtmlResponse($response)) {
            return $response;
        }

        // Skip for admin panel
        if ($request->is('admin*')) {
            return $response;
        }

        $seoService = app(SeoService::class);
        $content = $response->getContent();

        // Apply performance optimizations
        $optimizedContent = $seoService->applyPerformanceOptimizations($content);

        // Apply browser caching headers if enabled
        if (config('seo.enable_browser_caching', false)) {
            $cacheDuration = config('seo.cache_duration', 86400);
            $response->headers->set('Cache-Control', "public, max-age={$cacheDuration}");
            $response->headers->set('Expires', gmdate('D, d M Y H:i:s \G\M\T', time() + $cacheDuration));
        }

        // Apply Gzip compression if enabled
        if (config('seo.enable_gzip', false) && $this->shouldCompress($request)) {
            $response->headers->set('Content-Encoding', 'gzip');
            $optimizedContent = gzencode($optimizedContent, 9);
        }

        $response->setContent($optimizedContent);

        return $response;
    }

    private function isHtmlResponse(Response $response): bool
    {
        $contentType = $response->headers->get('Content-Type', '');
        return str_contains($contentType, 'text/html');
    }

    private function shouldCompress(Request $request): bool
    {
        // Check if client supports gzip
        $acceptEncoding = $request->headers->get('Accept-Encoding', '');
        return str_contains($acceptEncoding, 'gzip');
    }
}
