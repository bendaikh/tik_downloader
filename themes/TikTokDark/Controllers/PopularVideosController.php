<?php

namespace Themes\TikTokDark\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Video;
use Illuminate\Http\Request;

class PopularVideosController extends Controller
{
    public function __invoke(Request $request)
    {
        $videos = Video::query()
            ->orderByDesc('downloads')
            ->orderByDesc('created_at')
            ->take(15)
            ->get();

        return view('TikTokDark::popular-videos', compact('videos'));
    }
}
