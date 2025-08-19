@extends('TikTokDark::layout')

@section('title', 'TikTok Downloader - Download TikTok Videos Without Watermark')
@section('description', 'Download TikTok videos without watermark. Free, fast, and easy to use TikTok video downloader. Save your favorite TikTok videos in high quality MP4 format.')

@section('content')
    <!-- Hero Section -->
    <section class="hero">
        <div class="hero-container">
            <h1>Download TikTok Video</h1>
            <p>The ultimate tool for downloading TikTok videos without watermarks.</p>
            
            <div class="download-form">
                <form id="downloadForm" method="POST" action="{{ route('download') }}">
                    @csrf
                    <div class="form-group">
                        <input type="url" 
                               name="url" 
                               id="tiktokUrl" 
                               class="form-input" 
                               placeholder="Just insert TikTok URL" 
                               required
                               value="{{ old('url') }}">
                        <button type="button" class="btn btn-secondary" onclick="pasteFromClipboard()">Paste</button>
                        <button type="submit" class="btn btn-primary">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M19 9h-4V3H9v6H5l7 7 7-7zM5 18v2h14v-2H5z"/>
                            </svg>
                            Download
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="features">
        <div class="container">
            <div class="section-title">
                <h2>Download Tiktok Videos with ease</h2>
                <p>Our TikTok downloader provides a seamless experience for downloading your favorite videos without any watermarks. Fast, secure, and completely free to use.</p>
            </div>
            
            <div class="features-grid">
                <div class="feature-card">
                    <div class="feature-icon primary">
                        <svg width="32" height="32" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                        </svg>
                    </div>
                    <h3>Unlimited Downloads</h3>
                    <p>Save as many videos as you want without any daily restrictions.</p>
                </div>
                
                <div class="feature-card">
                    <div class="feature-icon purple">
                        <svg width="32" height="32" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/>
                        </svg>
                    </div>
                    <h3>No TikTok Watermarks</h3>
                    <p>Download Tik Tok videos without the annoying watermark.</p>
                </div>
                
                <div class="feature-card">
                    <div class="feature-icon blue">
                        <svg width="32" height="32" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M20 6h-8l-2-2H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V8c0-1.1-.9-2-2-2zm-2 6h-2v2h2v2h-2v2h-2v-2h2v-2h-2v-2h2v-2h2v2z"/>
                        </svg>
                    </div>
                    <h3>MP4 and MP3 supported</h3>
                    <p>Save videos in high-quality MP4 or convert them to MP3 audio.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- How-to Section -->
    <section class="how-to">
        <div class="container">
            <div class="section-title">
                <h2>How to Download TikTok without watermark</h2>
                <p>Follow these simple steps to download your favorite TikTok videos without watermarks.</p>
            </div>
            
            <div class="steps-grid">
                <div class="step-card">
                    <div class="step-number primary">1</div>
                    <h3>Find a TikTok</h3>
                    <p>Browse TikTok and find the video you want to download.</p>
                </div>
                
                <div class="step-card">
                    <div class="step-number purple">2</div>
                    <h3>Copy the link</h3>
                    <p>Tap the share button and copy the video link to your clipboard.</p>
                </div>
                
                <div class="step-card">
                    <div class="step-number blue">3</div>
                    <h3>Download</h3>
                    <p>Paste the link above and click download to save your video.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Products Section -->
    @if($products && count($products) > 0)
    <section class="products">
        <div class="container">
            <div class="section-title">
                <h2>Our Affiliate Products</h2>
                <p>Discover amazing products that we recommend and trust.</p>
            </div>
            
            <div class="products-grid">
                @foreach($products->take(3) as $product)
                <div class="product-card">
                    <div class="product-header {{ $loop->index === 0 ? 'primary' : ($loop->index === 1 ? 'purple' : 'blue') }}">
                        <svg width="32" height="32" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                        </svg>
                    </div>
                    <div class="product-content">
                        <h3 class="product-title">{{ $product->title }}</h3>
                        <p class="product-description">{{ Str::limit($product->description, 100) }}</p>
                        @if($product->price)
                        <div class="product-price">USD ${{ number_format($product->price, 2) }}</div>
                        @endif
                        <a href="{{ $product->affiliate_url }}" target="_blank" class="btn btn-primary product-btn">
                            Buy Now
                        </a>
                    </div>
                </div>
                @endforeach
            </div>
            
            <div class="text-center mt-5">
                <a href="{{ route('products') }}" class="btn btn-primary">
                    See All Products →
                </a>
            </div>
        </div>
    </section>
    @endif

    <!-- Blog Section -->
    @if($blogPosts && count($blogPosts) > 0)
    <section class="blog">
        <div class="container">
            <div class="section-title">
                <h2>Latest Blog Posts</h2>
                <p>Stay updated with the latest tips and tricks for TikTok.</p>
            </div>
            
            <div class="blog-grid">
                @foreach($blogPosts->take(3) as $post)
                <div class="blog-card">
                    <h3 class="blog-title">{{ $post->title }}</h3>
                    <p class="blog-excerpt">{{ Str::limit($post->excerpt, 150) }}</p>
                    <a href="{{ route('blog-post', $post->slug) }}" class="blog-link">
                        Read More →
                    </a>
                </div>
                @endforeach
            </div>
            
            <div class="text-center mt-5">
                <a href="{{ route('blog') }}" class="btn btn-primary">
                    See All Blog Posts →
                </a>
            </div>
        </div>
    </section>
    @endif
@endsection

@push('scripts')
<script>
    // Paste from clipboard functionality
    async function pasteFromClipboard() {
        try {
            const text = await navigator.clipboard.readText();
            document.getElementById('tiktokUrl').value = text;
        } catch (err) {
            console.error('Failed to read clipboard contents: ', err);
            // Fallback: focus the input and show a message
            document.getElementById('tiktokUrl').focus();
            alert('Please paste the TikTok URL manually (Ctrl+V)');
        }
    }

    // Form submission with loading state
    document.getElementById('downloadForm').addEventListener('submit', function(e) {
        const submitBtn = this.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;
        
        // Add loading state
        submitBtn.innerHTML = '<div class="spinner"></div> Downloading...';
        submitBtn.disabled = true;
        submitBtn.classList.add('loading');
        
        // Remove loading state after 5 seconds (fallback)
        setTimeout(() => {
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
            submitBtn.classList.remove('loading');
        }, 5000);
    });

    // Auto-detect TikTok URLs in clipboard
    document.getElementById('tiktokUrl').addEventListener('paste', function(e) {
        setTimeout(() => {
            const url = this.value;
            if (url && !url.includes('tiktok.com')) {
                this.classList.add('error');
                this.setCustomValidity('Please enter a valid TikTok URL');
            } else {
                this.classList.remove('error');
                this.setCustomValidity('');
            }
        }, 100);
    });

    // Validate URL on input
    document.getElementById('tiktokUrl').addEventListener('input', function() {
        const url = this.value;
        if (url && !url.includes('tiktok.com')) {
            this.classList.add('error');
        } else {
            this.classList.remove('error');
        }
    });
</script>
@endpush
