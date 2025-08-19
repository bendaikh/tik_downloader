<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>@yield('title', 'TikTok Downloader - Download TikTok Videos Without Watermark')</title>
    <meta name="description" content="@yield('description', 'Download TikTok videos without watermark. Free, fast, and easy to use TikTok video downloader.')">
    
    <!-- Open Graph Meta Tags -->
    <meta property="og:title" content="@yield('og_title', 'TikTok Downloader - Download TikTok Videos Without Watermark')">
    <meta property="og:description" content="@yield('og_description', 'Download TikTok videos without watermark. Free, fast, and easy to use TikTok video downloader.')">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:image" content="@yield('og_image', asset('cover.jpg'))">
    
    <!-- Twitter Card Meta Tags -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="@yield('twitter_title', 'TikTok Downloader - Download TikTok Videos Without Watermark')">
    <meta name="twitter:description" content="@yield('twitter_description', 'Download TikTok videos without watermark. Free, fast, and easy to use TikTok video downloader.')">
    <meta name="twitter:image" content="@yield('twitter_image', asset('cover.jpg'))">
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.png') }}">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Styles -->
    <link rel="stylesheet" href="{{ asset('theme-assets/css/tiktok-dark.css') }}">
    @stack('styles')
    
    <!-- Google Analytics -->
    @if(config('analytics.enabled') && config('analytics.google_analytics_id'))
    <script async src="https://www.googletagmanager.com/gtag/js?id={{ config('analytics.google_analytics_id') }}"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', '{{ config('analytics.google_analytics_id') }}');
    </script>
    @endif
</head>
<body>
    <!-- Header -->
    <header class="header">
        <div class="header-container">
            <a href="{{ route('home') }}" class="logo">tiktok downloader</a>
            
            <nav class="nav">
                <a href="{{ route('popular-videos') }}" class="nav-link">Popular Videos</a>
                <a href="{{ route('faq') }}" class="nav-link">FAQ</a>
                <a href="{{ route('blog') }}" class="nav-link">Blog</a>
                
                <!-- Language Selector -->
                <div class="language-selector">
                    <a href="#" class="nav-link">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 17.93c-3.94-.49-7-3.85-7-7.93 0-.62.08-1.21.21-1.79L9 15v1c0 1.1.9 2 2 2v1.93zm6.9-2.54c-.26-.81-1-1.39-1.9-1.39h-1v-3c0-.55-.45-1-1-1H8v-2h2c.55 0 1-.45 1-1V7h2c1.1 0 2-.9 2-2v-.41c2.93 1.19 5 4.06 5 7.41 0 2.08-.8 3.97-2.1 5.39z"/>
                        </svg>
                        English
                    </a>
                </div>
                
                <a href="{{ route('donation.show') }}" class="donate-btn">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                    </svg>
                    Donate
                </a>
            </nav>
        </div>
    </header>

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="footer">
        <div class="footer-content">
            <p class="footer-description">
                TikTok Downloader is a free online tool that allows you to download TikTok videos without watermarks. 
                Our service is fast, secure, and easy to use. Download your favorite TikTok videos in high quality MP4 format.
            </p>
            
            <div class="footer-links">
                <a href="{{ route('tos') }}" class="footer-link">Terms of Service</a>
                <a href="{{ route('privacy') }}" class="footer-link">Privacy Policy</a>
                <a href="{{ route('faq') }}" class="footer-link">FAQ</a>
            </div>
            
            <p class="footer-copyright">©{{ date('Y') }} tiktok downloader. All rights reserved.</p>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script>
        // CSRF Token for AJAX requests
        axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        
        // Global error handler
        axios.interceptors.response.use(
            response => response,
            error => {
                console.error('Request failed:', error);
                return Promise.reject(error);
            }
        );
    </script>
    
    @stack('scripts')
</body>
</html>
