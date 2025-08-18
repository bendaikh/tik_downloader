<?php

namespace Themes\TTDown\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Video;
use Illuminate\Http\Request;

class PopularVideosController extends Controller
{
    public function __invoke(Request $request)
    {
        $videos = Video::query()
            ->orderByDesc('downloads')
            ->take(12)
            ->get();

        return view('theme::popular-videos', compact('videos'));
    }
}
