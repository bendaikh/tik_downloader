<?php

namespace Themes\TikTokDark\Controllers;

use App\Http\Controllers\Controller;

class FaqController extends Controller
{
    public function index()
    {
        return view('TikTokDark::faq');
    }
}
