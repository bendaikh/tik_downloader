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
})->middleware(['web', 'track'])->name('home');

// TikTok video processing
Route::post('/fetch', FetchController::class)->middleware(['web', 'auth.session'])->name('fetch');

// Download functionality
Route::get('/download', DownloadFileController::class)->middleware(['web'])->name('download');

// Blog routes
Route::get('/blog', [BlogController::class, 'index'])->middleware(['web', 'track'])->name('blog');
Route::get('/blog/{slug}', [BlogController::class, 'show'])->middleware(['web', 'track'])->name('blog-post');

// Products routes
Route::get('/products', [ProductController::class, 'index'])->middleware(['web', 'track'])->name('products');

// Static pages
Route::get('/faq', [FaqController::class, 'index'])->middleware(['web', 'track'])->name('faq');
Route::get('/tos', [TosController::class, 'index'])->middleware(['web', 'track'])->name('tos');
Route::get('/privacy', [PrivacyController::class, 'index'])->middleware(['web', 'track'])->name('privacy');

// Donation routes
Route::get('/donate', [DonationController::class, 'show'])->middleware(['web', 'track'])->name('donation.show');
Route::post('/donate', [DonationController::class, 'process'])->middleware(['web', 'track'])->name('donation.process');
Route::get('/donate/success', [DonationController::class, 'success'])->middleware(['web', 'track'])->name('donation.success');
Route::get('/donate/cancel', [DonationController::class, 'cancel'])->middleware(['web', 'track'])->name('donation.cancel');

// Popular videos (if exists)
Route::get('/popular-videos', function () {
    return view('TikTokDark::popular-videos');
})->middleware(['web', 'track'])->name('popular-videos');
