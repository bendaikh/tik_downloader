@extends('TikTokDark::layout')

@section('title', 'Donation Cancelled')
@section('description', 'Your donation was cancelled. No worries, you can try again anytime.')

@section('content')
<section class="hero">
    <div class="hero-container">
        <div class="cancel-message">
            <div class="cancel-icon">
                <svg width="64" height="64" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/>
                </svg>
            </div>
            <h1>Donation Cancelled</h1>
            <p>No worries! Your donation was cancelled. You can try again anytime.</p>
            
            <div class="cancel-actions">
                <a href="{{ route('donation.show') }}" class="btn btn-primary">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                    </svg>
                    Try Again
                </a>
                <a href="{{ route('home') }}" class="btn btn-secondary">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M10 20v-6h4v6h5v-8h3L12 3 2 12h3v8z"/>
                    </svg>
                    Back to Home
                </a>
            </div>
        </div>
    </div>
</section>

<section class="features">
    <div class="container">
        <div class="section-title">
            <h2>Other Ways to Support</h2>
            <p>There are many ways you can help us improve our service.</p>
        </div>
        
        <div class="features-grid">
            <div class="feature-card">
                <div class="feature-icon primary">
                    <svg width="32" height="32" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                    </svg>
                </div>
                <h3>Share Our Service</h3>
                <p>Tell your friends and family about our TikTok downloader.</p>
            </div>
            
            <div class="feature-card">
                <div class="feature-icon purple">
                    <svg width="32" height="32" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                    </svg>
                </div>
                <h3>Give Feedback</h3>
                <p>Let us know how we can improve our service.</p>
            </div>
            
            <div class="feature-card">
                <div class="feature-icon blue">
                    <svg width="32" height="32" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                    </svg>
                </div>
                <h3>Use Our Service</h3>
                <p>Keep using our TikTok downloader and spread the word.</p>
            </div>
        </div>
    </div>
</section>
@endsection

@push('styles')
<style>
.cancel-message {
    text-align: center;
    max-width: 600px;
    margin: 0 auto;
}

.cancel-icon {
    width: 120px;
    height: 120px;
    border-radius: 50%;
    background: var(--gradient-purple);
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto var(--spacing-lg);
    color: white;
}

.cancel-actions {
    display: flex;
    gap: var(--spacing-md);
    justify-content: center;
    margin-top: var(--spacing-xl);
    flex-wrap: wrap;
}

.cancel-actions .btn {
    min-width: 160px;
}

@media (max-width: 768px) {
    .cancel-actions {
        flex-direction: column;
        align-items: center;
    }
    
    .cancel-actions .btn {
        width: 100%;
        max-width: 300px;
    }
}
</style>
@endpush
