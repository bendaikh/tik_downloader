<?php

namespace Themes\TikTokDark\Controllers;

use App\Http\Controllers\Controller;

class TosController extends Controller
{
    public function index()
    {
        return view('TikTokDark::tos');
    }
}
