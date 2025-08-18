<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Download;

class DownloadFileController extends Controller
{
    public function __invoke(Request $request)
    {
        if (!$request->filled('url') || empty($request->get('url')))
            return redirect()->route('home');

        return $this->streamDownloadResponse($request);
    }

    function streamDownloadResponse(Request $request)
    {
        $url = $request->get('url');
        $extension = $request->get('extension', 'mp4');
        $url = base64_decode($url);

        $filename = config("app.name") . '-' . time() . '.' . $extension;

        // Track download event if Google Analytics is enabled
        if (config('analytics.ga_enabled') && config('analytics.ga_track_downloads')) {
            $gaService = app(\App\Service\GoogleAnalytics\GoogleAnalyticsService::class);
            if ($gaService->isEnabled()) {
                // Log download event (will be tracked via JavaScript)
                \Log::info('Video download initiated', [
                    'url' => $url,
                    'extension' => $extension,
                    'filename' => $filename
                ]);
            }
        }

        // start a buffer before sending headers
        // some php env may not buffer by default
        if (!ob_get_level()) ob_start();

        return response()->streamDownload(
            function () use ($url, $request) {
                $bytes = $this->streamFileContent($url);
                try {
                    Download::create([
                        'visitor_id' => $request->cookies->get('visitor_id'),
                        'session_id' => $request->session()->getId(),
                        'ip_address' => $request->ip(),
                        'user_agent' => (string) $request->userAgent(),
                        'video_id' => $request->get('video_id'),
                        'type' => $request->get('type', 'video'),
                        'bytes' => $bytes,
                    ]);
                } catch (\Throwable $e) {}
            },
            $filename,
            array_filter([
                'Content-Type' => 'application/octet-stream',
                'Content-Length' => $request->get('size'),
            ]));
    }

    function streamFileContent(string $url)
    {
        $ch = curl_init();
        $headers = array(
            
        );
        $options = array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HEADER => false,
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_FOLLOWLOCATION => true,
            CURLINFO_HEADER_OUT => true,
            CURLOPT_USERAGENT => 'okhttp',
            CURLOPT_ENCODING => "",
            CURLOPT_AUTOREFERER => true,
            CURLOPT_REFERER => 'https://www.tiktok.com/',
            CURLOPT_CONNECTTIMEOUT => 600,
            CURLOPT_TIMEOUT => 600,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_MAXREDIRS => 10,
        );
        curl_setopt_array($ch, $options);
        if (defined('CURLOPT_IPRESOLVE') && defined('CURL_IPRESOLVE_V4')) {
            curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
        }

        curl_setopt($ch, CURLOPT_WRITEFUNCTION, function ($curl, $data) {
            echo $data;
            ob_flush();
            flush();
            return strlen($data);
        });

        $downloaded = 0;
        curl_setopt($ch, CURLOPT_WRITEFUNCTION, function ($curl, $data) use (&$downloaded) {
            $downloaded += strlen($data);
            echo $data;
            ob_flush();
            flush();
            return strlen($data);
        });

        curl_exec($ch);
        curl_close($ch);
        return $downloaded;
    }
}
