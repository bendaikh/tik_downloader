@php
    $posts = \App\Models\Blog::published()->latest('published_at')->take(3)->get();
    $totalPosts = \App\Models\Blog::published()->count();
@endphp

@if($posts->count())
<section class="blog">
    <div class="container">
        <h2 class="section-title">Latest Blog Posts</h2>

        <div class="blog-grid">
            @foreach($posts as $post)
                <a href="/blog/{{ $post->slug }}" class="blog-card">
                    @if($post->featured_image)
                        <div class="blog-image" style="background-image:url('{{ $post->featured_image }}')"></div>
                    @endif
                    <div class="blog-content">
                        <h3 class="blog-title">{{ $post->title }}</h3>
                        <p class="blog-excerpt">{{ \Illuminate\Support\Str::limit(strip_tags($post->description ?? $post->excerpt), 120) }}</p>
                        <span class="read-more">Read More →</span>
                    </div>
                </a>
            @endforeach
        </div>
        
        @if($totalPosts > 3)
        <div class="blog-actions">
            <a href="/blog" class="see-more-btn">
                <span>See All Blog Posts</span>
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M5 12H19M19 12L12 5M19 12L12 19" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </a>
        </div>
        @endif
    </div>
</section>
@endif
