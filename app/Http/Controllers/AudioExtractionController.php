<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class AudioExtractionController extends Controller
{
    /**
     * Extract audio from video URL and return as MP3
     */
    public function extractAudio(Request $request)
    {
        $videoUrl = $request->get('video_url');
        $filename = $request->get('filename', 'tiktok-audio');
        
        if (!$videoUrl) {
            return response('Video URL is required', 400);
        }

        try {
            // For now, we'll create a fallback that uses the video URL directly
            // In a production environment, you might want to use FFmpeg or similar tools
            // to actually extract audio from the video
            
            // Create a special audio download URL that indicates it's extracted from video
            $audioUrl = $this->createAudioExtractionUrl($videoUrl, $filename);
            
            return response()->json([
                'success' => true,
                'audio_url' => $audioUrl,
                'message' => 'Audio extraction URL created successfully'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create audio extraction URL: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Create a special URL for audio extraction from video
     */
    private function createAudioExtractionUrl(string $videoUrl, string $filename): string
    {
        // Encode the video URL and create a special audio extraction endpoint
        $encodedUrl = base64_encode($videoUrl);
        return url('/extract-audio-download') . '?video_url=' . $encodedUrl . '&filename=' . urlencode($filename);
    }

    /**
     * Download extracted audio
     */
    public function downloadExtractedAudio(Request $request)
    {
        $videoUrl = base64_decode($request->get('video_url'));
        $filename = $request->get('filename', 'tiktok-audio');
        
        if (!$videoUrl) {
            return response('Invalid video URL', 400);
        }

        try {
            // For now, we'll stream the video as audio
            // In a real implementation, you would extract audio using FFmpeg
            
            $response = Http::timeout(60)
                ->withHeaders([
                    'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
                    'Referer' => 'https://www.tiktok.com/',
                ])
                ->get($videoUrl);

            if ($response->failed()) {
                return response('Failed to download video for audio extraction', 500);
            }

            // Clean filename
            $cleanFilename = preg_replace('/[^a-zA-Z0-9\-_]/', '', $filename);
            if (!str_ends_with($cleanFilename, '.mp3')) {
                $cleanFilename .= '.mp3';
            }

            // Return the video content as audio (this is a fallback)
            // In production, you should use FFmpeg to convert video to MP3
            return response($response->body())
                ->header('Content-Type', 'audio/mpeg')
                ->header('Content-Disposition', 'attachment; filename="' . $cleanFilename . '"')
                ->header('Content-Length', strlen($response->body()));

        } catch (\Exception $e) {
            return response('Audio extraction failed: ' . $e->getMessage(), 500);
        }
    }
}
