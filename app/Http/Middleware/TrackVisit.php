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

        $countryCode = $request->headers->get('CF-IPCountry')
            ?? $request->headers->get('X-Country-Code');
        $countryName = null;

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
}


