@extends('TikTokDark::layout')

@section('title', 'Blog - TikTok Downloader')
@section('description', 'Read our latest blog posts about TikTok, video downloading, and tips.')

@section('content')
<section class="hero">
    <div class="hero-container">
        <h1>Blog</h1>
        <p>Latest articles and tips about TikTok video downloading.</p>
    </div>
</section>

<section class="features">
    <div class="container">
        <div class="section-title">
            <h2>Latest Articles</h2>
            <p>Stay updated with the latest tips and tricks for TikTok.</p>
        </div>
        
        @if($blogPosts->count() > 0)
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
            
            @if($blogPosts->hasPages())
                <div class="pagination-wrapper">
                    {{ $blogPosts->links() }}
                </div>
            @endif
        @else
            <div class="no-posts">
                <p>No blog posts available at the moment.</p>
            </div>
        @endif
    </div>
</section>
@endsection

@push('styles')
<style>
.blog-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: var(--spacing-lg);
    margin-bottom: var(--spacing-xl);
}

.blog-card {
    background: var(--bg-card);
    border-radius: var(--radius-xl);
    padding: var(--spacing-lg);
    border: 1px solid rgba(255, 255, 255, 0.1);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.blog-card:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-lg);
}

.blog-title {
    color: var(--text-primary);
    margin-bottom: var(--spacing-sm);
    font-size: 1.25rem;
    line-height: 1.4;
}

.blog-excerpt {
    color: var(--text-secondary);
    line-height: 1.6;
    margin-bottom: var(--spacing-md);
}

.blog-meta {
    display: flex;
    gap: var(--spacing-md);
    margin-bottom: var(--spacing-md);
    color: var(--text-secondary);
    font-size: 0.9rem;
}

.blog-link {
    color: var(--primary-color);
    text-decoration: none;
    font-weight: 500;
    transition: color 0.3s ease;
}

.blog-link:hover {
    color: var(--primary-hover);
}

.pagination-wrapper {
    display: flex;
    justify-content: center;
    margin-top: var(--spacing-xl);
}

.no-posts {
    text-align: center;
    color: var(--text-secondary);
    padding: var(--spacing-xl);
}
</style>
@endpush
