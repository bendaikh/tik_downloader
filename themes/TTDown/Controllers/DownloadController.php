<?php

namespace Themes\TTDown\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Download;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Http;

class DownloadController extends Controller
{
    public function download(Request $request)
    {
        $url = $request->query('url');
        $filename = $request->query('filename', 'download');
        $type = $request->query('type', 'video'); // video, audio, watermark
        
        if (!$url) {
            return response('Invalid URL', 400);
        }
        
        try {
            // Fetch the file from the remote URL
            $response = Http::timeout(60)
                ->withHeaders([
                    'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36',
                    'Referer' => 'https://www.tiktok.com/',
                ])
                ->get($url);
            
            if ($response->failed()) {
                return response('Failed to download file', 500);
            }
            
            // Determine content type and filename based on type
            $contentType = 'application/octet-stream';
            $extension = '.mp4';
            
            switch ($type) {
                case 'audio':
                    $contentType = 'audio/mpeg';
                    $extension = '.mp3';
                    break;
                case 'video':
                case 'watermark':
                    $contentType = 'video/mp4';
                    $extension = '.mp4';
                    break;
            }
            
            // Clean filename and add extension if not present
            $cleanFilename = preg_replace('/[^a-zA-Z0-9\-_]/', '', $filename);
            if (!str_ends_with($cleanFilename, $extension)) {
                $cleanFilename .= $extension;
            }
            
            $body = $response->body();
            $bytes = strlen($body);

            try {
                Download::create([
                    'visitor_id' => $request->cookies->get('visitor_id'),
                    'session_id' => $request->session()->getId(),
                    'ip_address' => $request->ip(),
                    'user_agent' => (string) $request->userAgent(),
                    'video_id' => $request->query('video_id'),
                    'type' => $type,
                    'bytes' => $bytes,
                ]);
            } catch (\Throwable $e) {}

            return response($body)
                ->header('Content-Type', $contentType)
                ->header('Content-Disposition', 'attachment; filename="' . $cleanFilename . '"')
                ->header('Content-Length', $bytes)
                ->header('Cache-Control', 'no-cache, no-store, must-revalidate')
                ->header('Pragma', 'no-cache')
                ->header('Expires', '0');
                
        } catch (\Exception $e) {
            return response('Download failed: ' . $e->getMessage(), 500);
        }
    }
}
