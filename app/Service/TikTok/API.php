<?php

namespace App\Service\TikTok;

use App\Exceptions\TikTokAPIException;
use App\Service\TikTok\Contracts\TikTokAPI as TikTokAPIContract;
use Illuminate\Support\Facades\Http;

/**
 * Lightweight local fallback implementation of the TikTok API that relies
 * on TikTok's public oEmbed endpoint. It does not require any license key
 * and therefore allows the application to work in development environments
 * where the CodeSpikeX remote API is unavailable.
 */
class API implements TikTokAPIContract
{
    public function getVideo(string $url, string $id): TikTokVideo
    {
        // 1. Try the free Tikwm.com endpoint first (no auth / license key required)
        $apiResponse = Http::timeout(30)
            ->withHeaders([
                'User-Agent' => request()->userAgent() ?? 'Mozilla/5.0',
                'Accept'     => 'application/json',
            ])
            ->get('https://www.tikwm.com/api/', [
                'url' => rawurlencode($url),
                'hd'  => 1,
            ]);

        if ($apiResponse->ok() && data_get($apiResponse->json(), 'data.play')) {
            $data = $apiResponse->json('data');

            $noWatermark = $data['play']; // HD/no-watermark video URL
            $watermark   = $data['wmplay'] ?? null; // With watermark
            $music       = $data['music'] ?? null; // MP3

            return new TikTokVideo([
                'id'          => $id,
                'caption'     => $data['title'] ?? null,
                'url'         => $url,
                'cover'       => [ 'url' => $data['cover'] ?? null ],
                'author'      => [
                    'username' => data_get($data, 'author.unique_id') ?? null,
                    'avatar'   => data_get($data, 'author.avatar') ?? null,
                    'profile'  => null,
                ],
                'downloads'   => [
                    [
                        'bitrate' => null,
                        'size'    => data_get($data, 'size') ?? null,
                        'urls'    => [ $noWatermark ],
                    ]
                ],
                'watermark'   => [
                    'url'  => $watermark,
                    'size' => data_get($data, 'size'),
                ],
                'music'       => [
                    'downloadUrl' => $music,
                ],
                'statistics'  => [],
            ]);
        }

        // 2. Fallback to TikTok oEmbed (thumbnail only) so the UI can at least show something
        $response = Http::timeout(30)
            ->withHeaders([
                'User-Agent' => request()->userAgent() ?? 'Mozilla/5.0',
                'Accept'     => 'application/json',
            ])
            ->get('https://www.tiktok.com/oembed', [
                'url' => $url,
            ]);

        if ($response->failed()) {
            throw new TikTokAPIException(
                $response->json('error_message', 'Unable to fetch video metadata.'),
                $response->json('error_code', 500),
                $response->status()
            );
        }

        $json = $response->json();

        return new TikTokVideo([
            'id'          => $id,
            'caption'     => $json['title'] ?? null,
            'url'         => $url,
            'cover'       => [ 'url' => $json['thumbnail_url'] ?? null ],
            'author'      => [
                'username' => $json['author_name'] ?? null,
                'avatar'   => $json['thumbnail_url'] ?? null,
                'profile'  => $json['author_url'] ?? null,
            ],
            'downloads'   => [],
            'watermark'   => [
                'url'  => $json['thumbnail_url'] ?? null,
                'size' => null,
            ],
            'statistics'  => [],
            'music'       => null,
        ]);
    }
}
