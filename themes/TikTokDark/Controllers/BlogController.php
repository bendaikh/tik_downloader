<?php

namespace Themes\TikTokDark\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Blog;

class BlogController extends Controller
{
    public function index()
    {
        $blogPosts = Blog::where('is_published', true)->latest()->paginate(10);
        return view('TikTokDark::blog', compact('blogPosts'));
    }

    public function show($slug)
    {
        $post = Blog::where('slug', $slug)->where('is_published', true)->firstOrFail();
        return view('TikTokDark::blog-post', compact('post'));
    }
}
