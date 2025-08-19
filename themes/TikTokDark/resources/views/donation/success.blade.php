@extends('TikTokDark::layout')

@section('title', 'Donation Successful - Thank You!')
@section('description', 'Thank you for your donation! Your support helps us maintain and improve our TikTok downloader service.')

@section('content')
<section class="hero">
    <div class="hero-container">
        <div class="success-message">
            <div class="success-icon">
                <svg width="64" height="64" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                </svg>
            </div>
            <h1>Thank You!</h1>
            <p>Your donation has been received successfully. We appreciate your support!</p>
            
            <div class="success-actions">
                <a href="{{ route('home') }}" class="btn btn-primary">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M10 20v-6h4v6h5v-8h3L12 3 2 12h3v8z"/>
                    </svg>
                    Back to Home
                </a>
                <a href="{{ route('donation.show') }}" class="btn btn-secondary">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                    </svg>
                    Donate Again
                </a>
            </div>
        </div>
    </div>
</section>

<section class="features">
    <div class="container">
        <div class="section-title">
            <h2>What Your Support Means</h2>
            <p>Your donation helps us continue providing the best TikTok downloader service.</p>
        </div>
        
        <div class="features-grid">
            <div class="feature-card">
                <div class="feature-icon primary">
                    <svg width="32" height="32" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                    </svg>
                </div>
                <h3>Free Service</h3>
                <p>We'll continue providing our TikTok downloader completely free for everyone.</p>
            </div>
            
            <div class="feature-card">
                <div class="feature-icon purple">
                    <svg width="32" height="32" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                    </svg>
                </div>
                <h3>New Features</h3>
                <p>We'll develop new features and improvements based on user feedback.</p>
            </div>
            
            <div class="feature-card">
                <div class="feature-icon blue">
                    <svg width="32" height="32" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                    </svg>
                </div>
                <h3>Better Performance</h3>
                <p>We'll upgrade our servers to provide faster and more reliable downloads.</p>
            </div>
        </div>
    </div>
</section>
@endsection

@push('styles')
<style>
.success-message {
    text-align: center;
    max-width: 600px;
    margin: 0 auto;
}

.success-icon {
    width: 120px;
    height: 120px;
    border-radius: 50%;
    background: var(--gradient-primary);
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto var(--spacing-lg);
    color: white;
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0% { transform: scale(1); }
    50% { transform: scale(1.05); }
    100% { transform: scale(1); }
}

.success-actions {
    display: flex;
    gap: var(--spacing-md);
    justify-content: center;
    margin-top: var(--spacing-xl);
    flex-wrap: wrap;
}

.success-actions .btn {
    min-width: 160px;
}

@media (max-width: 768px) {
    .success-actions {
        flex-direction: column;
        align-items: center;
    }
    
    .success-actions .btn {
        width: 100%;
        max-width: 300px;
    }
}
</style>
@endpush
