<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=yes, initial-scale=1.0, maximum-scale=5.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{$title ?? config('app.name')}}</title>
    <meta name="description"
          content="TikTok Video Downloader Without watermark! Now you can download TikTok Videos without any restriction. Just paste your TikTok Video Url and download the video.">
    <meta name="keywords"
          content="TikTok, TikTok Downloader, TikTok Video Downloader, Download TikTok Videos, Online TikTok Video Downloader, Download TikTok Videos Without Watermark">
    <link rel="shortcut icon" href="{{asset('favicon.png')}}" type="image/png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preload" href="https://fonts.googleapis.com/css2?family=Nunito:wght@300;500;600;700;900&display=swap"
          as="style">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@300;500;600;700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{asset('assets/admin/app.css')}}">
    {{$styles ?? ''}}
    @stack('styles')
    <script defer src="https://unpkg.com/@alpinejs/collapse@3.10.3/dist/cdn.min.js"
            integrity="sha384-gE382HiLf7oZIQO4e8O4ursZqf9JAjQQgNCRsUyUKfWBMXOiEFm89KxNkJjycgEq"
            crossorigin="anonymous"></script>
    <script defer src="https://unpkg.com/alpinejs@3.10.3/dist/cdn.min.js"
            integrity="sha384-WJjkwfwjSA9R8jBkDaVBHc+idGbOa+2W4uq2SOwLCHXyNktpMVINGAD2fCYbUZn6"
            crossorigin="anonymous"></script>
</head>
<body>

<x-admin.header/>
<div class="layout">
    <div class="container">
        <x-admin.navigation />
        <div class="layout-content">
            @if(auth()->user()->is_demo)
                <div class="demo-notice-banner">
                    <div class="demo-notice-content">
                        <span class="demo-notice-icon">🔒</span>
                        <div class="demo-notice-text">
                            <strong>Demo Mode Active</strong>
                            <span>You are logged in as a demo user. Critical settings like AI integration, payment settings, analytics, and general settings are restricted to prevent unauthorized modifications.</span>
                        </div>
                    </div>
                </div>
            @endif
            {{$banner ?? ''}}
            <x-admin.flash />
            {{$slot}}
        </div>
    </div>
</div>

<style>
.demo-notice-banner {
    background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
    border: 1px solid #f59e0b;
    border-radius: 0.75rem;
    padding: 1rem;
    margin-bottom: 1.5rem;
    box-shadow: 0 4px 6px -1px rgba(245, 158, 11, 0.1);
}

.demo-notice-content {
    display: flex;
    align-items: flex-start;
    gap: 0.75rem;
}

.demo-notice-icon {
    font-size: 1.25rem;
    flex-shrink: 0;
    margin-top: 0.125rem;
}

.demo-notice-text {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
}

.demo-notice-text strong {
    color: #92400e;
    font-weight: 600;
    font-size: 0.875rem;
}

.demo-notice-text span {
    color: #78350f;
    font-size: 0.875rem;
    line-height: 1.4;
}
</style>

@stack('scripts')
</body>
</html>
