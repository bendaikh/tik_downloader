<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>Donation Successful - Thank You!</title>
    <meta name="description" content="Thank you for your donation to our TikTok downloader project">
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.png') }}">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --bg-primary: #0f172a;
            --bg-secondary: #1e293b;
            --text-primary: #f8fafc;
            --text-secondary: #94a3b8;
            --accent-color: #00d4aa;
            --border-color: #334155;
            --success-color: #10b981;
            --error-color: #ef4444;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', sans-serif;
            background: var(--bg-primary);
            color: var(--text-primary);
            line-height: 1.6;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 1rem;
        }
        
        .donation-success-page {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem 0;
        }
        
        .success-content {
            background: var(--bg-secondary);
            border-radius: 1rem;
            padding: 3rem 2rem;
            border: 1px solid var(--border-color);
            max-width: 600px;
            width: 100%;
            text-align: center;
        }
        
        .success-icon {
            width: 80px;
            height: 80px;
            background: var(--success-color);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 2rem;
        }
        
        .success-icon svg {
            width: 40px;
            height: 40px;
            color: white;
        }
        
        .success-title {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 1rem;
            color: var(--success-color);
        }
        
        .success-message {
            font-size: 1.1rem;
            color: var(--text-secondary);
            margin-bottom: 2rem;
            line-height: 1.6;
        }
        
        .donation-details {
            background: var(--bg-primary);
            border-radius: 0.5rem;
            padding: 1.5rem;
            margin-bottom: 2rem;
            border: 1px solid var(--border-color);
        }
        
        .detail-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.5rem 0;
            border-bottom: 1px solid var(--border-color);
        }
        
        .detail-item:last-child {
            border-bottom: none;
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
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.75rem 1.5rem;
            border-radius: 0.5rem;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        
        .btn-primary {
            background: var(--accent-color);
            color: white;
            border: 2px solid var(--accent-color);
        }
        
        .btn-primary:hover {
            background: transparent;
            color: var(--accent-color);
        }
        
        .btn-secondary {
            background: transparent;
            color: var(--text-primary);
            border: 2px solid var(--border-color);
        }
        
        .btn-secondary:hover {
            background: var(--border-color);
        }
        
        .thank-you-message {
            color: var(--text-secondary);
            font-size: 0.9rem;
        }
        
        @media (max-width: 640px) {
            .success-content {
                padding: 2rem 1.5rem;
            }
            
            .success-title {
                font-size: 1.5rem;
            }
            
            .success-actions {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
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

    @if(config('analytics.ga_enabled') && config('analytics.ga_track_donations') && $amount)
    <script>
        // Track successful donation
        if (typeof gaTrackDonation === 'function') {
            gaTrackDonation({{ $amount['value'] }}, '{{ $amount['currency_code'] }}');
        }
    </script>
    @endif
</body>
</html>
