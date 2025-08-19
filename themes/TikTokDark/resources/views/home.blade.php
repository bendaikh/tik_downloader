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
                <form id="downloadForm" action="{{ route('fetch') }}" method="POST">
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
                        <button type="submit" class="btn btn-primary" id="downloadBtn">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M19 9h-4V3H9v6H5l7 7 7-7zM5 18v2h14v-2H5z"/>
                            </svg>
                            Download
                        </button>
                    </div>
                </form>
            </div>
            
            <!-- Download Results -->
            <div id="downloadResults" style="display: none; margin-top: 2rem;">
                <div class="download-card">
                    <div id="videoInfo" style="margin-bottom: 1.5rem;"></div>
                    <div id="downloadLinks"></div>
                </div>
            </div>
            
            <!-- Error Message -->
            <div id="errorMessage" style="display: none; margin-top: 1rem;">
                <div class="error-card">
                    <span id="errorText"></span>
                </div>
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
                    <h3>Paste and download</h3>
                    <p>Paste the link above and click download to get your video.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Products Section -->
    @if($products->count() > 0)
    <section class="products">
        <div class="container">
            <div class="section-title">
                <h2>Recommended Products</h2>
                <p>These are products we personally use and recommend.</p>
            </div>
            
            <div class="products-grid">
                @foreach($products->take(3) as $product)
                    <div class="product-card">
                        <div class="product-header primary">
                            <svg width="32" height="32" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                            </svg>
                        </div>
                        <div class="product-content">
                            <h3 class="product-title">{{ $product->title }}</h3>
                            <p class="product-description">{{ $product->description }}</p>
                            <div class="product-price">{{ $product->price }}</div>
                            <a href="{{ $product->affiliate_link }}" target="_blank" class="btn btn-primary product-btn">Buy Now</a>
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
    @if($blogPosts->count() > 0)
    <section class="blog">
        <div class="container">
            <div class="section-title">
                <h2>Latest Blog Posts</h2>
                <p>Stay updated with the latest tips and tricks for TikTok.</p>
            </div>
            
            <div class="blog-grid">
                @foreach($blogPosts as $post)
                    <div class="blog-card">
                        <h3 class="blog-title">{{ $post->title }}</h3>
                        <p class="blog-excerpt">{{ $post->excerpt ?? Str::limit($post->content, 150) }}</p>
                        <div class="blog-meta">
                            <span class="blog-date">{{ $post->created_at->format('F j, Y') }}</span>
                            @if($post->author)
                                <span class="blog-author">by {{ is_string($post->author) ? $post->author : $post->author->name ?? 'Admin' }}</span>
                            @endif
                        </div>
                        <a href="{{ route('blog-post', $post->slug) }}" class="blog-link">Read More →</a>
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
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('downloadForm');
    const downloadBtn = document.getElementById('downloadBtn');
    const downloadResults = document.getElementById('downloadResults');
    const errorMessage = document.getElementById('errorMessage');
    const videoInfo = document.getElementById('videoInfo');
    const downloadLinks = document.getElementById('downloadLinks');
    const errorText = document.getElementById('errorText');
    
    form.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        // Reset previous results
        downloadResults.style.display = 'none';
        errorMessage.style.display = 'none';
        
        // Show loading state
        downloadBtn.disabled = true;
        downloadBtn.innerHTML = '<svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/></svg> Processing...';
        
        try {
            const formData = new FormData(form);
            const response = await fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                }
            });
            
            const data = await response.json();
            
            if (data.success && data.video) {
                showVideoResults(data.video);
            } else {
                showError(data.error || 'Failed to fetch video. Please check the URL and try again.');
            }
        } catch (error) {
            showError('Network error. Please try again.');
            console.error('Download error:', error);
        } finally {
            // Reset button state
            downloadBtn.disabled = false;
            downloadBtn.innerHTML = '<svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M19 9h-4V3H9v6H5l7 7 7-7zM5 18v2h14v-2H5z"/></svg> Download';
        }
    });
    
    function showVideoResults(video) {
        // Video info
        videoInfo.innerHTML = `
            <div class="video-info">
                <img src="${video.cover?.url || ''}" alt="Video thumbnail" class="video-thumbnail" onerror="this.style.display='none'">
                <div>
                    <h3>${video.caption || 'TikTok Video'}</h3>
                    <p>@${video.author?.username || 'Unknown'}</p>
                </div>
            </div>
        `;
        
        // Download links
        let linksHtml = '<div class="download-links">';
        
        // HD Video without watermark (primary download)
        if (video.downloads && video.downloads.length > 0) {
            const hdDownload = video.downloads[0]; // First download is usually HD
            const size = hdDownload.size ? ` (${hdDownload.size})` : '';
            const encodedUrl = btoa(hdDownload.url);
            const downloadUrl = '/download?url=' + encodedUrl + '&extension=mp4&type=video';
            
            linksHtml += `
                <a href="${downloadUrl}" class="download-link primary">
                    <span>📹 Download HD Video Without Watermark${size}</span>
                    <span>⬇️</span>
                </a>
            `;
        }
        
        // MP3 Audio download
        if (video.music && video.music.downloadUrl) {
            const encodedAudioUrl = btoa(video.music.downloadUrl);
            const audioDownloadUrl = '/download?url=' + encodedAudioUrl + '&extension=mp3&type=audio';
            
            linksHtml += `
                <a href="${audioDownloadUrl}" class="download-link secondary">
                    <span>🎵 Download MP3 Audio</span>
                    <span>⬇️</span>
                </a>
            `;
        } else if (video.downloads && video.downloads.length > 0) {
            // Fallback: if no MP3 URL available, show that MP3 is not available
            linksHtml += `
                <div class="download-link disabled">
                    <span>🎵 MP3 Audio (Not Available)</span>
                    <span>❌</span>
                </div>
            `;
        }
        
        // Add watermark download if available
        if (video.watermark?.url) {
            const encodedWatermarkUrl = btoa(video.watermark.url);
            const watermarkDownloadUrl = '/download?url=' + encodedWatermarkUrl + '&extension=mp4&type=watermark';
            
            linksHtml += `
                <a href="${watermarkDownloadUrl}" class="download-link watermark">
                    <span>💧 Download with Watermark</span>
                    <span>⬇️</span>
                </a>
            `;
        }
        
        linksHtml += '</div>';
        downloadLinks.innerHTML = linksHtml;
        
        downloadResults.style.display = 'block';
    }
    
    function showError(message) {
        errorText.textContent = message;
        errorMessage.style.display = 'block';
    }
});

// Paste from clipboard function
function pasteFromClipboard() {
    navigator.clipboard.readText().then(function(text) {
        document.getElementById('tiktokUrl').value = text;
    }).catch(function(err) {
        console.error('Failed to read clipboard contents: ', err);
    });
}
</script>
@endpush

@push('styles')
<style>
.download-card {
    background: linear-gradient(135deg, rgba(255, 255, 255, 0.05) 0%, rgba(255, 255, 255, 0.02) 100%);
    border-radius: var(--radius-xl);
    padding: var(--spacing-lg);
    border: 1px solid rgba(255, 255, 255, 0.1);
    max-width: 600px;
    margin: 0 auto;
    backdrop-filter: blur(10px);
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
}

.error-card {
    background: #dc2626;
    color: white;
    padding: var(--spacing-md);
    border-radius: var(--radius-lg);
    text-align: center;
    max-width: 600px;
    margin: 0 auto;
}

.video-info {
    display: flex;
    gap: var(--spacing-md);
    align-items: center;
}

.video-thumbnail {
    width: 80px;
    height: 80px;
    border-radius: var(--radius-lg);
    object-fit: cover;
}

.video-info h3 {
    color: var(--text-primary);
    margin-bottom: var(--spacing-sm);
    font-size: 1.125rem;
}

.video-info p {
    color: var(--text-secondary);
    font-size: 0.875rem;
}

.download-links {
    display: flex;
    flex-direction: column;
    gap: var(--spacing-sm);
}

.download-link {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: var(--spacing-md);
    border-radius: var(--radius-lg);
    text-decoration: none;
    font-weight: 600;
    transition: all 0.3s ease;
    cursor: pointer;
    position: relative;
    overflow: hidden;
}

.download-link::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
    transition: left 0.5s ease;
}

.download-link:hover::before {
    left: 100%;
}

.download-link:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.3);
}

.download-link.primary {
    background: linear-gradient(135deg, #00d4aa 0%, #0099cc 100%);
    color: white;
    box-shadow: 0 4px 15px rgba(0, 212, 170, 0.3);
    border: 1px solid rgba(0, 212, 170, 0.2);
}

.download-link.secondary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
    border: 1px solid rgba(102, 126, 234, 0.2);
}

.download-link.watermark {
    background: linear-gradient(135deg, rgba(255, 255, 255, 0.1) 0%, rgba(255, 255, 255, 0.05) 100%);
    color: var(--text-primary);
    border: 1px solid rgba(255, 255, 255, 0.2);
    backdrop-filter: blur(10px);
}

.download-link.disabled {
    background: rgba(255, 255, 255, 0.05);
    color: var(--text-secondary);
    opacity: 0.5;
    cursor: not-allowed;
}

.download-link.disabled:hover {
    transform: none;
}

.text-center {
    text-align: center;
}

.mt-5 {
    margin-top: 3rem;
}

.text-center .btn {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 1rem 2rem;
    font-weight: 600;
    text-decoration: none;
    border-radius: var(--radius-lg);
    transition: all 0.3s ease;
    background: var(--primary-gradient);
    color: white;
    border: none;
    cursor: pointer;
}

.text-center .btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0, 212, 170, 0.3);
}
</style>
@endpush
