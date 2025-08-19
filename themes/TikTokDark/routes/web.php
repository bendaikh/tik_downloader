<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DownloadController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\FaqController;
use App\Http\Controllers\TosController;
use App\Http\Controllers\PrivacyController;
use Themes\TikTokDark\Controllers\DonationController;

// Home page
Route::get('/', function () {
    $products = \App\Models\Product::where('is_active', true)->latest()->get();
    $blogPosts = \App\Models\Blog::where('is_published', true)->latest()->take(3)->get();
    
    return view('TikTokDark::home', compact('products', 'blogPosts'));
})->name('home');

// Download functionality
Route::post('/download', [DownloadController::class, 'download'])->name('download');

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
