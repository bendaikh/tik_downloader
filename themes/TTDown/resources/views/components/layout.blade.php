<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app('theme.locales')['rtl'] && in_array(app()->getLocale(), app('theme.locales')['rtl']) ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>{{ $title ?? 'TT Down - Download TikTok Videos Without Watermark' }}</title>
    <meta name="description" content="Download TikTok videos without watermark. Free, fast and easy TikTok video downloader. Save TikTok videos in HD quality.">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Styles -->
    <link href="{{ app('theme')->asset('css/app.css') }}" rel="stylesheet">
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    
    @stack('head')
    
    @php
        $gaService = app(\App\Service\GoogleAnalytics\GoogleAnalyticsService::class);
    @endphp
    {!! $gaService->getTrackingCode() !!}
    
    @if(config('search_console.gsc_enabled') && config('search_console.gsc_verification_method') === 'meta_tag' && config('search_console.gsc_meta_tag'))
        {!! config('search_console.gsc_meta_tag') !!}
    @endif
    
    @php
        $microsoftService = app(\App\Service\MicrosoftServicesService::class);
    @endphp
    {!! $microsoftService->getAllScripts() !!}
</head>
<body>
    <!-- Header -->
    <header class="header">
        <div class="container">
            <div class="header-content">
                <a href="{{ route('home') }}" class="logo">
                    TTDown
                </a>
                
                <nav class="nav">
                    <a href="{{ route('popular-videos') }}" class="nav-link">Popular Videos</a>
                    <a href="{{ route('faq') }}" class="nav-link">FAQ</a>
                    <a href="#" class="nav-link">Blog</a>
                    <a href="#" class="nav-link">🌐 English</a>
                    @if(config('payments.donation_enabled', false))
                        <a href="{{ route('donation.show') }}" class="donate-btn">💖 Donate</a>
                    @endif
                </nav>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main>
        {{ $slot }}
    </main>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-text">
                    Our TikTok downloader is one of the most popular tools to save no-watermark TikTok videos. Do not need to install any apps to use our service; we open only a browser and watch to posts. A fully perfect solution for post-editing and publishing videos.
                </div>
            </div>
            
            <div style="margin-top: 2rem;">
                <div class="footer-content">
                    <div class="footer-text">
                        © {{ date('Y') }} TTDown. All rights reserved.
                    </div>
                    
                    <div class="footer-links">
                        <a href="{{ route('tos') }}" class="footer-link">Terms of Service</a>
                        <a href="{{ route('privacy') }}" class="footer-link">Privacy Policy</a>
                        <a href="{{ route('faq') }}" class="footer-link">FAQ</a>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script>
        // CSRF Token for AJAX requests
        window.csrf_token = '{{ csrf_token() }}';
        
        // Paste functionality
        document.addEventListener('DOMContentLoaded', function() {
            const pasteBtn = document.querySelector('.paste-btn');
            const urlInput = document.querySelector('.url-input');
            
            if (pasteBtn && urlInput) {
                pasteBtn.addEventListener('click', async function() {
                    try {
                        const text = await navigator.clipboard.readText();
                        urlInput.value = text;
                        urlInput.focus();
                    } catch (err) {
                        console.log('Failed to read clipboard contents: ', err);
                    }
                });
            }
        });
    </script>
    
    @stack('scripts')
</body>
</html>
