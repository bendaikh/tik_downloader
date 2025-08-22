<?php

use Illuminate\Support\Facades\Route;
use Themes\TTDown\Controllers\FetchController;
use Themes\TTDown\Controllers\PopularVideosController;
use Themes\TTDown\Controllers\SitemapController;
use Themes\TTDown\Controllers\DownloadController;

Route::post("fetch", FetchController::class)
    ->middleware(['web', 'auth.session'])
    ->name("fetch");

Route::get("download", [DownloadController::class, 'download'])
    ->middleware(['web'])
    ->name("download");

Route::get("/sitemap.xml", SitemapController::class)->name('sitemap');

Route::middleware(['web', 'theme', 'track', 'installed'])->group(function () {
    Route::view('/tos', "theme::tos")->name('tos');
    Route::view('/privacy', "theme::privacy")->name('privacy');
    
    // Blog routes - outside localization group
    Route::get('/blog', fn() => view("theme::blog"))->name('blog.index');
    Route::get('/blog/{slug}', \Themes\TTDown\Controllers\BlogPostController::class)->name('blog.show');
    
    // Products routes - outside localization group
    Route::get('/products', fn() => view("theme::products"))->name('products.index');
});

Route::localization()->middleware(['web', 'theme', 'track', 'installed'])->group(function () {
    Route::match(
        ['GET', 'POST', 'PUT', 'PATCH', 'DELETE'],
        '/',
        fn() => view("theme::home")
    )->name('home');
    Route::view('/faq', "theme::faq")->name('faq');
    Route::view('/how-to-save', "theme::how-to-save")->name('how-to-save');
    Route::get('/popular-videos', PopularVideosController::class)->name('popular-videos');
});
