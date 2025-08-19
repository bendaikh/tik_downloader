@extends('TikTokDark::layout')

@section('title', 'Products - Affiliate Products')
@section('description', 'Discover amazing products that we recommend and trust.')

@section('content')
<section class="hero">
    <div class="hero-container">
        <h1>Our Affiliate Products</h1>
        <p>Discover amazing products that we recommend and trust.</p>
    </div>
</section>

<section class="features">
    <div class="container">
        <div class="section-title">
            <h2>Recommended Products</h2>
            <p>These are products we personally use and recommend.</p>
        </div>
        
        @if($products->count() > 0)
            <div class="products-grid">
                @foreach($products as $product)
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
        @else
            <div class="no-products">
                <p>No products available at the moment.</p>
            </div>
        @endif
    </div>
</section>
@endsection

@push('styles')
<style>
.products-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: var(--spacing-lg);
    margin-bottom: var(--spacing-xl);
}

.product-card {
    background: var(--bg-card);
    border-radius: var(--radius-xl);
    padding: var(--spacing-lg);
    border: 1px solid rgba(255, 255, 255, 0.1);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.product-card:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-lg);
}

.product-header {
    width: 60px;
    height: 60px;
    border-radius: var(--radius-lg);
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: var(--spacing-md);
}

.product-header.primary {
    background: var(--primary-gradient);
}

.product-header.purple {
    background: var(--purple-gradient);
}

.product-header.blue {
    background: var(--blue-gradient);
}

.product-title {
    color: var(--text-primary);
    margin-bottom: var(--spacing-sm);
    font-size: 1.25rem;
}

.product-description {
    color: var(--text-secondary);
    line-height: 1.6;
    margin-bottom: var(--spacing-md);
}

.product-price {
    color: var(--primary-color);
    font-weight: 600;
    font-size: 1.1rem;
    margin-bottom: var(--spacing-md);
}

.product-btn {
    width: 100%;
}

.no-products {
    text-align: center;
    color: var(--text-secondary);
    padding: var(--spacing-xl);
}
</style>
@endpush
