<?php

namespace Themes\TikTokDark\Controllers;

use App\Http\Controllers\Controller;

class PrivacyController extends Controller
{
    public function index()
    {
        return view('TikTokDark::privacy');
    }
}
