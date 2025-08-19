@extends('TikTokDark::layout')

@section('title', $post->title)
@section('description', $post->excerpt ?? $post->title)

@section('content')
<section class="hero">
    <div class="hero-container">
        <h1>{{ $post->title }}</h1>
        <p>{{ $post->excerpt ?? 'Read our latest blog post.' }}</p>
    </div>
</section>

<section class="features">
    <div class="container">
        <div class="blog-post-content">
            <div class="blog-meta">
                <span class="blog-date">{{ $post->created_at->format('F j, Y') }}</span>
                @if($post->author)
                    <span class="blog-author">by {{ is_string($post->author) ? $post->author : $post->author->name ?? 'Admin' }}</span>
                @endif
            </div>
            
            <div class="blog-body">
                {!! $post->content !!}
            </div>
            
            <div class="blog-navigation">
                <a href="{{ route('blog') }}" class="btn btn-secondary">← Back to Blog</a>
            </div>
        </div>
    </div>
</section>
@endsection

@push('styles')
<style>
.blog-post-content {
    max-width: 800px;
    margin: 0 auto;
}

.blog-meta {
    display: flex;
    gap: var(--spacing-md);
    margin-bottom: var(--spacing-lg);
    color: var(--text-secondary);
    font-size: 0.9rem;
}

.blog-body {
    color: var(--text-primary);
    line-height: 1.8;
    font-size: 1.1rem;
    margin-bottom: var(--spacing-xl);
}

.blog-body h1, .blog-body h2, .blog-body h3, .blog-body h4, .blog-body h5, .blog-body h6 {
    color: var(--text-primary);
    margin-top: var(--spacing-lg);
    margin-bottom: var(--spacing-md);
}

.blog-body p {
    margin-bottom: var(--spacing-md);
}

.blog-body img {
    max-width: 100%;
    height: auto;
    border-radius: var(--radius-lg);
    margin: var(--spacing-md) 0;
}

.blog-navigation {
    margin-top: var(--spacing-xl);
    padding-top: var(--spacing-lg);
    border-top: 1px solid rgba(255, 255, 255, 0.1);
}
</style>
@endpush
