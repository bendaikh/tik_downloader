@php
    $relatedPosts = \App\Models\Blog::published()
        ->where('id', '!=', $post->id)
        ->latest('published_at')
        ->take(2)
        ->get();
    
    $affiliateProducts = \App\Models\Product::active()->ordered()->take(2)->get();
@endphp

<x-theme::layout>
    <div class="blog-post-layout">
        <div class="blog-container">
            <!-- Main Content -->
            <main class="blog-main">
                <article class="blog-article">
                    <h1 class="blog-title">{{ $post->title }}</h1>
                    
                    <div class="blog-meta">
                        <span class="author">👤 {{ $post->author->name ?? 'John Doe' }}</span>
                        <span class="date">📅 {{ $post->published_at?->format('F j, Y') ?? 'October 26, 2023' }}</span>
                    </div>

                    @if($post->featured_image)
                        <div class="blog-featured-image">
                            <img src="{{ $post->featured_image }}" alt="{{ $post->title }}">
                        </div>
                    @endif

                    <div class="blog-content">
                        {!! $post->content !!}
                    </div>
                </article>

                <!-- Related Posts Section -->
                @if($relatedPosts->count())
                    <section class="related-posts">
                        <h2>Related Posts</h2>
                        <div class="related-posts-grid">
                            @foreach($relatedPosts as $relatedPost)
                                <a href="/blog/{{ $relatedPost->slug }}" class="related-post-card">
                                    @if($relatedPost->featured_image)
                                        <div class="related-post-image" style="background-image: url('{{ $relatedPost->featured_image }}')"></div>
                                    @endif
                                    <div class="related-post-content">
                                        <h3>{{ $relatedPost->title }}</h3>
                                        <p>{{ Str::limit(strip_tags($relatedPost->description ?? $relatedPost->excerpt), 100) }}</p>
                                        <span class="read-more">Read More →</span>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </section>
                @endif
            </main>

            <!-- Sidebar -->
            <aside class="blog-sidebar">
                <!-- Advertisement Block -->
                <div class="sidebar-widget ad-widget">
                    <h3>Advertisement</h3>
                    <div class="ad-placeholder">
                        <span>Ad Space</span>
                    </div>
                </div>

                <!-- Affiliate Products -->
                @if($affiliateProducts->count())
                    <div class="sidebar-widget affiliate-widget">
                        <h3>Affiliate Products</h3>
                        @foreach($affiliateProducts as $product)
                            <div class="affiliate-product">
                                @if($product->image)
                                    <div class="product-image">
                                        <img src="{{ $product->image_url }}" alt="{{ $product->name }}">
                                    </div>
                                @endif
                                <div class="product-content">
                                    <h4>{{ $product->name }}</h4>
                                    <p>{{ Str::limit($product->description, 80) }}</p>
                                    <a href="{{ $product->affiliate_url }}" target="_blank" class="buy-now-btn">Buy Now</a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif

                <!-- Second Advertisement -->
                <div class="sidebar-widget ad-widget">
                    <h3>Advertisement</h3>
                    <div class="ad-placeholder">
                        <span>Ad Space</span>
                    </div>
                </div>
            </aside>
        </div>
    </div>

    <style>
        .blog-post-layout {
            background-color: #1e293b;
            color: #e2e8f0;
            min-height: 100vh;
            padding: 2rem 0;
        }

        .blog-container {
            max-width: 1200px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: 1fr 300px;
            gap: 3rem;
            padding: 0 1rem;
        }

        .blog-main {
            background-color: #334155;
            border-radius: 12px;
            padding: 2rem;
        }

        .blog-article {
            margin-bottom: 3rem;
        }

        .blog-title {
            font-size: 2.5rem;
            font-weight: 800;
            color: #ffffff;
            margin-bottom: 1rem;
            line-height: 1.2;
        }

        .blog-meta {
            display: flex;
            gap: 1.5rem;
            margin-bottom: 2rem;
            color: #94a3b8;
            font-size: 0.9rem;
        }

        .blog-featured-image {
            margin-bottom: 2rem;
        }

        .blog-featured-image img {
            width: 100%;
            height: 400px;
            object-fit: cover;
            border-radius: 8px;
        }

        .blog-content {
            line-height: 1.7;
            font-size: 1.1rem;
        }

        .blog-content h1, .blog-content h2, .blog-content h3 {
            color: #ffffff;
            margin: 2rem 0 1rem 0;
        }

        .blog-content p {
            margin-bottom: 1.5rem;
        }

        .blog-content ul, .blog-content ol {
            margin-bottom: 1.5rem;
            padding-left: 2rem;
        }

        .blog-content img {
            max-width: 100%;
            height: auto;
            border-radius: 8px;
            margin: 1rem 0;
        }

        .related-posts {
            border-top: 1px solid #475569;
            padding-top: 2rem;
        }

        .related-posts h2 {
            color: #ffffff;
            margin-bottom: 1.5rem;
            font-size: 1.8rem;
        }

        .related-posts-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
        }

        .related-post-card {
            background-color: #475569;
            border-radius: 8px;
            overflow: hidden;
            text-decoration: none;
            color: inherit;
            transition: transform 0.2s;
        }

        .related-post-card:hover {
            transform: translateY(-2px);
        }

        .related-post-image {
            height: 150px;
            background-size: cover;
            background-position: center;
        }

        .related-post-content {
            padding: 1rem;
        }

        .related-post-content h3 {
            color: #ffffff;
            margin-bottom: 0.5rem;
            font-size: 1.1rem;
        }

        .related-post-content p {
            color: #94a3b8;
            font-size: 0.9rem;
            margin-bottom: 1rem;
        }

        .read-more {
            color: #3b82f6;
            font-weight: 600;
        }

        .blog-sidebar {
            display: flex;
            flex-direction: column;
            gap: 2rem;
        }

        .sidebar-widget {
            background-color: #334155;
            border-radius: 12px;
            padding: 1.5rem;
        }

        .sidebar-widget h3 {
            color: #ffffff;
            margin-bottom: 1rem;
            font-size: 1.2rem;
            font-weight: 600;
        }

        .ad-widget .ad-placeholder {
            background-color: #475569;
            height: 200px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #94a3b8;
            font-weight: 600;
        }

        .affiliate-product {
            display: flex;
            gap: 1rem;
            margin-bottom: 1.5rem;
            padding-bottom: 1.5rem;
            border-bottom: 1px solid #475569;
        }

        .affiliate-product:last-child {
            margin-bottom: 0;
            padding-bottom: 0;
            border-bottom: none;
        }

        .product-image img {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 6px;
        }

        .product-content h4 {
            color: #ffffff;
            margin-bottom: 0.5rem;
            font-size: 1rem;
        }

        .product-content p {
            color: #94a3b8;
            font-size: 0.85rem;
            margin-bottom: 0.75rem;
        }

        .buy-now-btn {
            background-color: #ef4444;
            color: #ffffff;
            padding: 0.5rem 1rem;
            border-radius: 6px;
            text-decoration: none;
            font-size: 0.85rem;
            font-weight: 600;
            display: inline-block;
            transition: background-color 0.2s;
        }

        .buy-now-btn:hover {
            background-color: #dc2626;
        }

        @media (max-width: 768px) {
            .blog-container {
                grid-template-columns: 1fr;
                gap: 2rem;
            }

            .blog-title {
                font-size: 2rem;
            }

            .blog-meta {
                flex-direction: column;
                gap: 0.5rem;
            }
        }
    </style>
</x-theme::layout>
