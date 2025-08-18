<?php

namespace App\Http\Middleware;

use App\Models\Visit;
use Closure;
use DeviceDetector\DeviceDetector;
use DeviceDetector\Parser\Client\Browser as BrowserParser;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TrackVisit
{
    public function handle(Request $request, Closure $next): Response
    {
        try {
            $this->recordVisit($request);
        } catch (\Throwable $e) {
            // Do not break the request flow on analytics failures
        }
        return $next($request);
    }

    private function recordVisit(Request $request): void
    {
        if ($request->is('admin*')) {
            return; // skip admin panel
        }

        $sessionId = $request->session()->getId();
        $visitorId = $request->cookies->get('visitor_id');
        if (!$visitorId) {
            $visitorId = bin2hex(random_bytes(16));
            cookie()->queue(cookie('visitor_id', $visitorId, 60 * 24 * 365));
        }

        $userAgent = (string) $request->userAgent();
        $deviceType = 'other';
        $browser = null;
        if ($userAgent) {
            $dd = new DeviceDetector($userAgent);
            $dd->skipBotDetection();
            $dd->parse();
            if ($dd->isDesktop()) { $deviceType = 'desktop'; }
            elseif ($dd->isMobile()) { $deviceType = 'mobile'; }
            elseif ($dd->isTablet()) { $deviceType = 'tablet'; }
            $clientInfo = $dd->getClient();
            if (is_array($clientInfo) && isset($clientInfo['name'])) {
                $browser = $clientInfo['name'];
            }
        }

        // Prefer infra headers first
        $countryCode = $request->headers->get('CF-IPCountry')
            ?? $request->headers->get('X-Appengine-Country')
            ?? $request->headers->get('X-Geo-Country')
            ?? $request->headers->get('X-Country-Code');
        $countryName = null;

        // If no country code from headers, try HTTPS IP geolocation (ipapi.co)
        if (!$countryCode) {
            $ip = $request->ip();
            if ($ip && !in_array($ip, ['127.0.0.1', '::1', 'localhost'])) {
                try {
                    $geoData = $this->getCountryFromIP($ip);
                    if ($geoData) {
                        $countryCode = $geoData['code'] ?? null;
                        $countryName = $geoData['name'] ?? null;
                    } else {
                        // Log when geolocation fails
                        \Log::info('IP geolocation failed', [
                            'ip' => $ip,
                            'user_agent' => $userAgent,
                            'url' => $request->url()
                        ]);
                    }
                } catch (\Throwable $e) {
                    // Log geolocation errors
                    \Log::error('IP geolocation error', [
                        'ip' => $ip,
                        'error' => $e->getMessage(),
                        'url' => $request->url()
                    ]);
                }
            } else {
                // Log when IP is localhost
                \Log::info('Skipping geolocation for localhost', [
                    'ip' => $ip,
                    'url' => $request->url()
                ]);
            }
        } else {
            // Log when country code comes from headers
            \Log::info('Country code from headers', [
                'country_code' => $countryCode,
                'ip' => $request->ip(),
                'headers' => [
                    'CF-IPCountry' => $request->headers->get('CF-IPCountry'),
                    'X-Appengine-Country' => $request->headers->get('X-Appengine-Country'),
                    'X-Geo-Country' => $request->headers->get('X-Geo-Country'),
                    'X-Country-Code' => $request->headers->get('X-Country-Code'),
                ]
            ]);
        }

        if (is_string($countryCode)) {
            $countryCode = strtoupper($countryCode);
        }

        Visit::create([
            'visitor_id' => $visitorId,
            'session_id' => $sessionId,
            'ip_address' => $request->ip(),
            'country_code' => $countryCode,
            'country_name' => $countryName,
            'user_agent' => $userAgent,
            'device_type' => $deviceType,
            'browser' => $browser,
        ]);
    }

    private function getCountryFromIP(string $ip): ?array
    {
        $context = stream_context_create([
            'http' => [
                'timeout' => 2.0,
                'ignore_errors' => true,
            ]
        ]);
        $url = "https://ipapi.co/{$ip}/json/";
        $response = @file_get_contents($url, false, $context);
        if ($response) {
            $data = json_decode($response, true);
            if (is_array($data) && isset($data['country_code'])) {
                return [
                    'code' => strtoupper((string) $data['country_code']),
                    'name' => isset($data['country_name']) ? (string) $data['country_name'] : null,
                ];
            }
        }
        return null;
    }
}


