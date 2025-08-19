<?php

namespace Themes\TikTokDark\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Product;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::where('is_active', true)->latest()->get();
        return view('TikTokDark::products', compact('products'));
    }
}
