<x-theme::layout>
    <div class="donation-success-page">
        <div class="container">
            <div class="success-content">
                <div class="success-icon">
                    <svg width="80" height="80" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M12 22C17.5228 22 22 17.5228 22 12C22 6.47715 17.5228 2 12 2C6.47715 2 2 6.47715 2 12C2 17.5228 6.47715 22 12 22Z" stroke="currentColor" stroke-width="2"/>
                        <path d="M9 12L11 14L15 10" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </div>
                
                <h1 class="success-title">Thank You!</h1>
                <p class="success-message">Your donation has been received successfully.</p>
                
                @if($amount)
                <div class="donation-details">
                    <div class="detail-item">
                        <span class="label">Amount:</span>
                        <span class="value">{{ $amount['currency_code'] }} {{ $amount['value'] }}</span>
                    </div>
                    @if($order_id)
                    <div class="detail-item">
                        <span class="label">Transaction ID:</span>
                        <span class="value">{{ $order_id }}</span>
                    </div>
                    @endif
                </div>
                @endif
                
                <div class="success-actions">
                    <a href="/" class="btn-primary">Return to Home</a>
                    <a href="/donate" class="btn-secondary">Make Another Donation</a>
                </div>
                
                <div class="thank-you-message">
                    <p>Your support means a lot to us and helps us continue providing this service for free.</p>
                </div>
            </div>
        </div>
    </div>

    <style>
        .donation-success-page {
            padding: 4rem 0;
            background: var(--primary-bg);
            min-height: 100vh;
            display: flex;
            align-items: center;
        }

        .success-content {
            max-width: 500px;
            margin: 0 auto;
            text-align: center;
        }

        .success-icon {
            margin-bottom: 2rem;
            color: var(--success-color);
        }

        .success-title {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
            color: var(--text-primary);
        }

        .success-message {
            font-size: 1.125rem;
            color: var(--text-secondary);
            margin-bottom: 2rem;
        }

        .donation-details {
            background: var(--card-bg);
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 2rem;
            border: 1px solid var(--border-color);
        }

        .detail-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.5rem 0;
        }

        .detail-item:not(:last-child) {
            border-bottom: 1px solid var(--border-color);
        }

        .label {
            color: var(--text-secondary);
            font-weight: 500;
        }

        .value {
            color: var(--text-primary);
            font-weight: 600;
        }

        .success-actions {
            display: flex;
            gap: 1rem;
            justify-content: center;
            margin-bottom: 2rem;
        }

        .btn-primary, .btn-secondary {
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-primary {
            background: var(--gradient-accent);
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            color: white;
        }

        .btn-secondary {
            background: var(--secondary-bg);
            color: var(--text-secondary);
            border: 1px solid var(--border-color);
        }

        .btn-secondary:hover {
            background: var(--card-bg);
            color: var(--text-primary);
        }

        .thank-you-message {
            color: var(--text-secondary);
            font-style: italic;
        }

        @media (max-width: 768px) {
            .success-title {
                font-size: 2rem;
            }
            
            .success-actions {
                flex-direction: column;
            }
            
            .donation-details {
                padding: 1rem;
            }
        }
    </style>

    @if(config('analytics.ga_enabled') && config('analytics.ga_track_donations') && $amount)
    <script>
        // Track successful donation
        if (typeof gaTrackDonation === 'function') {
            gaTrackDonation({{ $amount['value'] }}, '{{ $amount['currency_code'] }}');
        }
    </script>
    @endif
</x-theme::layout>
