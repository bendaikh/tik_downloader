@extends('TikTokDark::layout')

@section('title', 'Donate - Support TikTok Downloader')
@section('description', 'Support our TikTok downloader service. Your donations help us maintain and improve our free service.')

@section('content')
<section class="hero">
    <div class="hero-container">
        <h1>Support Our Service</h1>
        <p>Help us keep TikTok Downloader free and improve our service.</p>
        
        <div class="download-form">
            <div class="donation-options">
                <div class="donation-option">
                    <div class="donation-amount">$5</div>
                    <p>Buy us a coffee</p>
                </div>
                <div class="donation-option">
                    <div class="donation-amount">$10</div>
                    <p>Lunch break</p>
                </div>
                <div class="donation-option">
                    <div class="donation-amount">$25</div>
                    <p>Dinner out</p>
                </div>
                <div class="donation-option">
                    <div class="donation-amount">$50</div>
                    <p>Weekend getaway</p>
                </div>
            </div>
            
            <form id="donationForm" method="POST" action="{{ route('donation.process') }}">
                @csrf
                <div class="form-group">
                    <input type="number" 
                           name="amount" 
                           id="amount" 
                           class="form-input" 
                           placeholder="Enter amount" 
                           min="1" 
                           step="0.01" 
                           required
                           value="{{ old('amount', 5) }}">
                    <button type="submit" class="btn btn-primary">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                        </svg>
                        Donate Now
                    </button>
                </div>
            </form>
        </div>
    </div>
</section>

<section class="features">
    <div class="container">
        <div class="section-title">
            <h2>Why Support Us?</h2>
            <p>Your donations help us maintain and improve our free TikTok downloader service.</p>
        </div>
        
        <div class="features-grid">
            <div class="feature-card">
                <div class="feature-icon primary">
                    <svg width="32" height="32" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                    </svg>
                </div>
                <h3>Keep It Free</h3>
                <p>Help us maintain our free service for everyone to use.</p>
            </div>
            
            <div class="feature-card">
                <div class="feature-icon purple">
                    <svg width="32" height="32" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                    </svg>
                </div>
                <h3>Improve Features</h3>
                <p>Support development of new features and improvements.</p>
            </div>
            
            <div class="feature-card">
                <div class="feature-icon blue">
                    <svg width="32" height="32" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                    </svg>
                </div>
                <h3>Better Performance</h3>
                <p>Help us upgrade servers and improve download speeds.</p>
            </div>
        </div>
    </div>
</section>
@endsection

@push('styles')
<style>
.donation-options {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
    gap: var(--spacing-md);
    margin-bottom: var(--spacing-lg);
}

.donation-option {
    background: var(--bg-input);
    border: 2px solid transparent;
    border-radius: var(--radius-lg);
    padding: var(--spacing-md);
    text-align: center;
    cursor: pointer;
    transition: all 0.3s ease;
}

.donation-option:hover {
    border-color: var(--accent-primary);
    transform: translateY(-2px);
}

.donation-option.selected {
    border-color: var(--accent-primary);
    background: rgba(0, 212, 170, 0.1);
}

.donation-amount {
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--accent-primary);
    margin-bottom: var(--spacing-xs);
}

.donation-option p {
    font-size: 0.875rem;
    color: var(--text-secondary);
    margin: 0;
}
</style>
@endpush

@push('scripts')
<script>
// Donation amount selection
document.querySelectorAll('.donation-option').forEach(option => {
    option.addEventListener('click', function() {
        // Remove selected class from all options
        document.querySelectorAll('.donation-option').forEach(opt => {
            opt.classList.remove('selected');
        });
        
        // Add selected class to clicked option
        this.classList.add('selected');
        
        // Update amount input
        const amount = this.querySelector('.donation-amount').textContent.replace('$', '');
        document.getElementById('amount').value = amount;
    });
});

// Form submission with loading state
document.getElementById('donationForm').addEventListener('submit', function(e) {
    const submitBtn = this.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    
    // Add loading state
    submitBtn.innerHTML = '<div class="spinner"></div> Processing...';
    submitBtn.disabled = true;
    submitBtn.classList.add('loading');
    
    // Remove loading state after 5 seconds (fallback)
    setTimeout(() => {
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
        submitBtn.classList.remove('loading');
    }, 5000);
});
</script>
@endpush
