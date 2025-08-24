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
            @forelse($videos as $video)
                <div class="feature-card video-card">
                    <div class="video-thumbnail">
                        @if($video->cover && $video->getCoverUrl())
                            <img src="{{ $video->getCoverUrl() }}" alt="Video thumbnail" class="thumbnail-image" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                            <div class="thumbnail-placeholder" style="display: none;">
                                <svg width="48" height="48" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M8 5v14l11-7z"/>
                                </svg>
                            </div>
                        @else
                            <div class="thumbnail-placeholder">
                                <svg width="48" height="48" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M8 5v14l11-7z"/>
                                </svg>
                            </div>
                        @endif
                        <div class="video-overlay">
                            <div class="play-button">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M8 5v14l11-7z"/>
                                </svg>
                            </div>
                        </div>
                    </div>
                    
                    <div class="video-info">
                        <h3 class="video-title">{{ Str::limit($video->username ? '@' . $video->username . ' TikTok Video' : 'TikTok Video', 40) }}</h3>
                        <div class="video-meta">
                            <span class="username">{{ $video->username ?: 'Unknown User' }}</span>
                            <span class="downloads">{{ number_format($video->downloads) }} downloads</span>
                        </div>
                        
                        @if($video->url)
                            <a href="{{ $video->url }}" target="_blank" class="view-original-btn">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6M15 3h6v6M10 14L21 3"/>
                                </svg>
                                View Original
                            </a>
                        @endif
                    </div>
                </div>
            @empty
                <div class="empty-state">
                    <div class="empty-icon">📱</div>
                    <h3>No videos yet</h3>
                    <p>Popular videos will appear here as users download them.</p>
                </div>
            @endforelse
        </div>
    </div>
</section>
@endsection
