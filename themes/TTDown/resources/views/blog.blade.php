<x-theme::layout>
    <div class="blog-page">
        <div class="container">
            <!-- Hero Section -->
            <div class="blog-hero">
                <h1 class="blog-hero-title">Blog Posts</h1>
                <p class="blog-hero-subtitle">Discover the latest insights, tips, and updates about TikTok downloading and social media content</p>
            </div>

            <!-- Blog Posts Grid -->
            @php
                $posts = \App\Models\Blog::published()->latest('published_at')->paginate(9);
            @endphp

            @if($posts->count())
                <div class="blog-posts-grid">
                    @foreach($posts as $post)
                        <article class="blog-post-card">
                            <a href="/blog/{{ $post->slug }}" class="blog-post-link">
                                @if($post->featured_image)
                                    <div class="blog-post-image" style="background-image:url('{{ $post->featured_image }}')">
                                        <div class="blog-post-overlay"></div>
                                    </div>
                                @else
                                    <div class="blog-post-image blog-post-placeholder">
                                        <div class="blog-post-overlay"></div>
                                        <svg width="48" height="48" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M14 2H6C4.89543 2 4 2.89543 4 4V20C4 21.1046 4.89543 22 6 22H18C19.1046 22 20 21.1046 20 20V8L14 2Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                            <path d="M14 2V8H20" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                            <path d="M16 13H8" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                            <path d="M16 17H8" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                            <path d="M10 9H8" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                        </svg>
                                    </div>
                                @endif
                                
                                <div class="blog-post-content">
                                    <div class="blog-post-meta">
                                        <span class="blog-post-date">{{ $post->published_at->format('M d, Y') }}</span>
                                        @if($post->author)
                                            <span class="blog-post-author">by {{ $post->author->name }}</span>
                                        @endif
                                    </div>
                                    
                                    <h2 class="blog-post-title">{{ $post->title }}</h2>
                                    <p class="blog-post-excerpt">{{ \Illuminate\Support\Str::limit(strip_tags($post->description ?? $post->excerpt), 150) }}</p>
                                    
                                    <div class="blog-post-footer">
                                        <span class="read-more-text">Read Full Article</span>
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M5 12H19M19 12L12 5M19 12L12 19" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                        </svg>
                                    </div>
                                </div>
                            </a>
                        </article>
                    @endforeach
                </div>

                <!-- Pagination -->
                @if($posts->hasPages())
                    <div class="blog-pagination">
                        {{ $posts->links() }}
                    </div>
                @endif
            @else
                <div class="blog-empty">
                    <div class="blog-empty-icon">
                        <svg width="64" height="64" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M14 2H6C4.89543 2 4 2.89543 4 4V20C4 21.1046 4.89543 22 6 22H18C19.1046 22 20 21.1046 20 20V8L14 2Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M14 2V8H20" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M16 13H8" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M16 17H8" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M10 9H8" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
                    <h3 class="blog-empty-title">No Blog Posts Yet</h3>
                    <p class="blog-empty-text">We're working on creating amazing content for you. Check back soon!</p>
                </div>
            @endif
        </div>
    </div>
</x-theme::layout>
