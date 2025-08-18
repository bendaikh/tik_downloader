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
                        // Fallback: Check if it's a known hosting provider IP
                        $fallbackCountry = $this->getCountryFromIPRange($ip);
                        if ($fallbackCountry) {
                            $countryCode = $fallbackCountry['code'];
                            $countryName = $fallbackCountry['name'];
                        }
                        
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
        // Try multiple geolocation services
        $services = [
            'ipapi' => "https://ipapi.co/{$ip}/json/",
            'ipinfo' => "https://ipinfo.io/{$ip}/json",
            'freegeoip' => "https://freegeoip.app/json/{$ip}",
        ];

        foreach ($services as $service => $url) {
            try {
                $context = stream_context_create([
                    'http' => [
                        'timeout' => 3.0,
                        'ignore_errors' => true,
                        'user_agent' => 'Mozilla/5.0 (compatible; Analytics/1.0)',
                    ]
                ]);
                
                $response = @file_get_contents($url, false, $context);
                
                if ($response) {
                    $data = json_decode($response, true);
                    
                    if (is_array($data)) {
                        // Handle different response formats
                        $countryCode = null;
                        $countryName = null;
                        
                        switch ($service) {
                            case 'ipapi':
                                if (isset($data['country_code'])) {
                                    $countryCode = strtoupper((string) $data['country_code']);
                                    $countryName = isset($data['country_name']) ? (string) $data['country_name'] : null;
                                }
                                break;
                            case 'ipinfo':
                                if (isset($data['country'])) {
                                    $countryCode = strtoupper((string) $data['country']);
                                    $countryName = isset($data['region']) ? (string) $data['region'] : null;
                                }
                                break;
                            case 'freegeoip':
                                if (isset($data['country_code'])) {
                                    $countryCode = strtoupper((string) $data['country_code']);
                                    $countryName = isset($data['country_name']) ? (string) $data['country_name'] : null;
                                }
                                break;
                        }
                        
                        if ($countryCode) {
                            \Log::info("IP geolocation successful", [
                                'ip' => $ip,
                                'service' => $service,
                                'country_code' => $countryCode,
                                'country_name' => $countryName
                            ]);
                            
                            return [
                                'code' => $countryCode,
                                'name' => $countryName,
                            ];
                        }
                    }
                }
            } catch (\Throwable $e) {
                \Log::warning("IP geolocation service failed", [
                    'ip' => $ip,
                    'service' => $service,
                    'error' => $e->getMessage()
                ]);
                continue;
            }
        }
        
        \Log::warning("All IP geolocation services failed", ['ip' => $ip]);
        return null;
    }

    private function getCountryFromIPRange(string $ip): ?array
    {
        // Simple IP range check for common hosting providers
        $ipRanges = [
            '160.177.' => ['code' => 'US', 'name' => 'United States'], // Your hosting provider
            '192.168.' => ['code' => 'XX', 'name' => 'Private Network'],
            '10.' => ['code' => 'XX', 'name' => 'Private Network'],
            '172.16.' => ['code' => 'XX', 'name' => 'Private Network'],
            '172.17.' => ['code' => 'XX', 'name' => 'Private Network'],
            '172.18.' => ['code' => 'XX', 'name' => 'Private Network'],
            '172.19.' => ['code' => 'XX', 'name' => 'Private Network'],
            '172.20.' => ['code' => 'XX', 'name' => 'Private Network'],
            '172.21.' => ['code' => 'XX', 'name' => 'Private Network'],
            '172.22.' => ['code' => 'XX', 'name' => 'Private Network'],
            '172.23.' => ['code' => 'XX', 'name' => 'Private Network'],
            '172.24.' => ['code' => 'XX', 'name' => 'Private Network'],
            '172.25.' => ['code' => 'XX', 'name' => 'Private Network'],
            '172.26.' => ['code' => 'XX', 'name' => 'Private Network'],
            '172.27.' => ['code' => 'XX', 'name' => 'Private Network'],
            '172.28.' => ['code' => 'XX', 'name' => 'Private Network'],
            '172.29.' => ['code' => 'XX', 'name' => 'Private Network'],
            '172.30.' => ['code' => 'XX', 'name' => 'Private Network'],
            '172.31.' => ['code' => 'XX', 'name' => 'Private Network'],
        ];

        foreach ($ipRanges as $range => $country) {
            if (strpos($ip, $range) === 0) {
                return $country;
            }
        }

        return null;
    }
}


