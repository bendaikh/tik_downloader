<x-theme::layout>
    <div class="products-page">
        <div class="container">
            <!-- Hero Section -->
            <div class="products-hero">
                <h1 class="products-hero-title">Our Products</h1>
                <p class="products-hero-subtitle">Discover amazing products and tools to enhance your digital experience</p>
            </div>

            <!-- Products Grid -->
            @php
                $products = \App\Models\Product::active()->ordered()->paginate(12);
            @endphp

            @if($products->count())
                <div class="products-grid">
                    @foreach($products as $product)
                        <article class="product-item-card">
                            <div class="product-item-image">
                                @if($product->image_url)
                                    <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="product-image">
                                @else
                                    <div class="product-placeholder">
                                        <svg width="48" height="48" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M16 11V7A4 4 0 0 0 4 7V11M5 9H19L20 21H4L5 9Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                        </svg>
                                    </div>
                                @endif
                                <div class="product-overlay">
                                    <a href="{{ $product->affiliate_url }}" target="_blank" class="product-buy-btn">
                                        <span>Buy Now</span>
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M5 12H19M19 12L12 5M19 12L12 19" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                        </svg>
                                    </a>
                                </div>
                            </div>
                            
                            <div class="product-item-content">
                                <h2 class="product-item-title">{{ $product->name }}</h2>
                                <p class="product-item-description">{{ \Illuminate\Support\Str::limit($product->description, 120) }}</p>
                                
                                @if($product->price)
                                    <div class="product-item-price">
                                        <span class="price-currency">{{ $product->currency ?? 'USD' }}</span>
                                        <span class="price-amount">${{ number_format($product->price, 2) }}</span>
                                    </div>
                                @endif
                                
                                <div class="product-item-footer">
                                    <a href="{{ $product->affiliate_url }}" target="_blank" class="product-item-link">
                                        <span>View Product</span>
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M5 12H19M19 12L12 5M19 12L12 19" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>

                <!-- Pagination -->
                @if($products->hasPages())
                    <div class="products-pagination">
                        {{ $products->links() }}
                    </div>
                @endif
            @else
                <div class="products-empty">
                    <div class="products-empty-icon">
                        <svg width="64" height="64" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M16 11V7A4 4 0 0 0 4 7V11M5 9H19L20 21H4L5 9Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
                    <h3 class="products-empty-title">No Products Available</h3>
                    <p class="products-empty-text">We're working on adding amazing products for you. Check back soon!</p>
                </div>
            @endif
        </div>
    </div>
</x-theme::layout>
