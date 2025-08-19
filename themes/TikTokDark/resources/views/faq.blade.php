@extends('TikTokDark::layout')

@section('title', 'FAQ - Frequently Asked Questions')
@section('description', 'Find answers to frequently asked questions about our TikTok downloader service.')

@section('content')
<section class="hero">
    <div class="hero-container">
        <h1>Frequently Asked Questions</h1>
        <p>Find answers to common questions about our TikTok downloader service.</p>
    </div>
</section>

<section class="features">
    <div class="container">
        <div class="faq-list">
            <div class="faq-item">
                <h3>How do I download TikTok videos?</h3>
                <p>Simply copy the TikTok video URL and paste it into our downloader. Click the download button and choose your preferred format.</p>
            </div>
            
            <div class="faq-item">
                <h3>Is this service free to use?</h3>
                <p>Yes, our TikTok downloader is completely free to use with no hidden fees or restrictions.</p>
            </div>
            
            <div class="faq-item">
                <h3>What video formats are supported?</h3>
                <p>We support MP4 video format and MP3 audio format for your downloads.</p>
            </div>
            
            <div class="faq-item">
                <h3>Will the downloaded videos have watermarks?</h3>
                <p>No, our service removes TikTok watermarks from downloaded videos.</p>
            </div>
            
            <div class="faq-item">
                <h3>Is it legal to download TikTok videos?</h3>
                <p>It's legal to download videos for personal use. Please respect copyright and only download content you have permission to use.</p>
            </div>
            
            <div class="faq-item">
                <h3>How fast is the download process?</h3>
                <p>Download speed depends on your internet connection and video size, but our service is optimized for fast downloads.</p>
            </div>
        </div>
    </div>
</section>
@endsection

@push('styles')
<style>
.faq-list {
    max-width: 800px;
    margin: 0 auto;
}

.faq-item {
    background: var(--bg-card);
    border-radius: var(--radius-xl);
    padding: var(--spacing-lg);
    margin-bottom: var(--spacing-lg);
    border: 1px solid rgba(255, 255, 255, 0.1);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.faq-item:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-lg);
}

.faq-item h3 {
    color: var(--text-primary);
    margin-bottom: var(--spacing-sm);
    font-size: 1.25rem;
}

.faq-item p {
    color: var(--text-secondary);
    line-height: 1.6;
    margin: 0;
}
</style>
@endpush
