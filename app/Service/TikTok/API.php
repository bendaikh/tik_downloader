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

            // If no music URL from tikwm, try alternative API
            if (!$music) {
                $music = $this->tryAlternativeMusicAPI($url);
            }

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

    /**
     * Try alternative APIs to get music URL
     */
    private function tryAlternativeMusicAPI(string $url): ?string
    {
        // Try multiple alternative APIs
        $apis = [
            [
                'url' => 'https://api.tikwm.com/api/',
                'params' => ['url' => $url, 'hd' => 1],
                'field' => 'data.music'
            ],
            [
                'url' => 'https://www.tikwm.com/api/',
                'params' => ['url' => $url, 'hd' => 1],
                'field' => 'data.music'
            ],
            [
                'url' => 'https://api.tiktokdownloader.org/api/video',
                'params' => ['url' => $url],
                'field' => 'audio_url'
            ],
            [
                'url' => 'https://tiktokmate.com/api/video',
                'params' => ['url' => $url],
                'field' => 'music_url'
            ],
            [
                'url' => 'https://api.tiktokv.com/api/video',
                'params' => ['url' => $url],
                'field' => 'music.url'
            ]
        ];

        foreach ($apis as $api) {
            try {
                $response = Http::timeout(10)
                    ->withHeaders([
                        'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36',
                        'Accept'     => 'application/json',
                        'Referer'    => 'https://www.tiktok.com/',
                    ])
                    ->get($api['url'], $api['params']);

                if ($response->ok()) {
                    $data = $response->json();
                    $musicUrl = data_get($data, $api['field']);
                    
                    if ($musicUrl && filter_var($musicUrl, FILTER_VALIDATE_URL)) {
                        return $musicUrl;
                    }
                }
            } catch (\Exception $e) {
                // Silently fail and try next API
                continue;
            }
        }

        return null;
    }
}
