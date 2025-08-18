<?php

namespace Themes\TTDown\Controllers;

use App\Events\TikTokVideoFetched;
use App\Exceptions\TikTokException;
use App\Exceptions\TikTokVideoNotFoundException;
use App\Http\Controllers\Controller;
use App\Http\Requests\FetchRequest;
use App\Models\User;
use App\Models\Video;
use App\Service\TikTok\TikTok;
use Illuminate\Http\JsonResponse;

class FetchController extends Controller
{
    public function __invoke(FetchRequest $request): JsonResponse
    {
        try {
            $video = (new TikTok)->getVideo($request->url);
            event(new TikTokVideoFetched($video));

            /** @var Video $videoModel */
            if ($videoModel = Video::query()->find($video->id)) {
                $cover = $video->cover;
                $cover['url'] = $videoModel->getCoverUrl();
                $video->offsetSet('cover', $cover);
            }

            $downloadUrls = collect($video->downloads)
                ->sortByDesc('bitrate')
                ->map(function ($data, $key) {
                    $isHD = $key == 0;
                    return collect(data_get($data, 'urls', []))
                        ->take(config('app.link_per_bitrate', 2))
                        ->map(fn($url) => [
                            'url' => $url,
                            'isHD' => $isHD,
                            'size' => data_get($data, 'size')
                        ])->all();
                })
                ->flatten(1)
                ->reject(fn($item) => empty(data_get($item, 'url')))
                ->map(fn($data, $idx) => array_merge($data, compact('idx')))
                ->values();

            $data = [
                'success' => true,
                'video' => array_merge($video->toArray(), [
                    'downloads' => $downloadUrls
                ])
            ];

            return response()->json($data);
        } catch (TikTokVideoNotFoundException $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
                'code' => $e->getCode()
            ], 404);
        } catch (TikTokException $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
                'code' => $e->getCode()
            ], 422);
        }
    }
}
