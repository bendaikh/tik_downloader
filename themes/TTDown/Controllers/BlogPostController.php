<?php

namespace Themes\TTDown\Controllers;

use App\Models\Blog;
use Illuminate\Http\Request;

class BlogPostController
{
    /**
     * Display a published blog post.
     */
    public function __invoke(string $slug)
    {
        $post = Blog::published()->where('slug', $slug)->firstOrFail();

        return view('theme::blog-post', compact('post'));
    }
}
