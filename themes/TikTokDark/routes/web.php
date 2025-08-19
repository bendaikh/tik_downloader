<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DownloadFileController;
use Themes\TikTokDark\Controllers\BlogController;
use Themes\TikTokDark\Controllers\ProductController;
use Themes\TikTokDark\Controllers\FaqController;
use Themes\TikTokDark\Controllers\TosController;
use Themes\TikTokDark\Controllers\PrivacyController;
use Themes\TikTokDark\Controllers\DonationController;
use Themes\TikTokDark\Controllers\FetchController;

// Home page
Route::get('/', function () {
    $products = \App\Models\Product::where('is_active', true)->latest()->get();
    $blogPosts = \App\Models\Blog::where('is_published', true)->latest()->take(3)->get();
    
    return view('TikTokDark::home', compact('products', 'blogPosts'));
})->name('home');

// TikTok video processing
Route::post('/fetch', FetchController::class)->name('fetch');

// Download functionality
Route::get('/download', DownloadFileController::class)->name('download');

// Blog routes
Route::get('/blog', [BlogController::class, 'index'])->name('blog');
Route::get('/blog/{slug}', [BlogController::class, 'show'])->name('blog-post');

// Products routes
Route::get('/products', [ProductController::class, 'index'])->name('products');

// Static pages
Route::get('/faq', [FaqController::class, 'index'])->name('faq');
Route::get('/tos', [TosController::class, 'index'])->name('tos');
Route::get('/privacy', [PrivacyController::class, 'index'])->name('privacy');

// Donation routes
Route::get('/donate', [DonationController::class, 'show'])->name('donation.show');
Route::post('/donate', [DonationController::class, 'process'])->name('donation.process');
Route::get('/donate/success', [DonationController::class, 'success'])->name('donation.success');
Route::get('/donate/cancel', [DonationController::class, 'cancel'])->name('donation.cancel');

// Popular videos (if exists)
Route::get('/popular-videos', function () {
    return view('TikTokDark::popular-videos');
})->name('popular-videos');
