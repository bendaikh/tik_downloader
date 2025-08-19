@extends('TikTokDark::layout')

@section('title', 'Popular TikTok Videos')
@section('description', 'Discover and download the most popular TikTok videos. Browse trending content and save your favorite videos.')

@section('content')
<section class="hero">
    <div class="hero-container">
        <h1>Popular TikTok Videos</h1>
        <p>Discover trending content and download the most popular TikTok videos.</p>
    </div>
</section>

<section class="features">
    <div class="container">
        <div class="section-title">
            <h2>Trending Videos</h2>
            <p>Browse the most popular TikTok videos that are currently trending.</p>
        </div>
        
        <div class="features-grid">
            <div class="feature-card">
                <div class="feature-icon primary">
                    <svg width="32" height="32" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                    </svg>
                </div>
                <h3>Trending Content</h3>
                <p>Discover the latest trending videos on TikTok.</p>
            </div>
            
            <div class="feature-card">
                <div class="feature-icon purple">
                    <svg width="32" height="32" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                    </svg>
                </div>
                <h3>Easy Download</h3>
                <p>Download any trending video with just one click.</p>
            </div>
            
            <div class="feature-card">
                <div class="feature-icon blue">
                    <svg width="32" height="32" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                    </svg>
                </div>
                <h3>High Quality</h3>
                <p>Get videos in the highest available quality.</p>
            </div>
        </div>
    </div>
</section>
@endsection
