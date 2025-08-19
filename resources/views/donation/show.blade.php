<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>Support Our Project - Donation</title>
    <meta name="description" content="Support our TikTok downloader project with a donation">
    
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
        
        .donation-page {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem 0;
        }
        
        .donation-content {
            background: var(--bg-secondary);
            border-radius: 1rem;
            padding: 2rem;
            border: 1px solid var(--border-color);
            max-width: 600px;
            width: 100%;
        }
        
        .donation-hero {
            text-align: center;
            margin-bottom: 2rem;
        }
        
        .donation-title {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
            background: linear-gradient(135deg, var(--accent-color), #0099cc);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .donation-subtitle {
            color: var(--text-secondary);
            font-size: 1.1rem;
        }
        
        .amount-selection {
            margin-bottom: 2rem;
        }
        
        .amount-selection h3 {
            margin-bottom: 1rem;
            color: var(--text-primary);
        }
        
        .amount-buttons {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(100px, 1fr));
            gap: 0.5rem;
            margin-bottom: 1rem;
        }
        
        .amount-btn {
            padding: 0.75rem 1rem;
            border: 2px solid var(--border-color);
            background: var(--bg-primary);
            color: var(--text-primary);
            border-radius: 0.5rem;
            cursor: pointer;
            transition: all 0.3s ease;
            font-weight: 500;
        }
        
        .amount-btn:hover, .amount-btn.active {
            border-color: var(--accent-color);
            background: var(--accent-color);
            color: white;
        }
        
        .custom-amount {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .custom-amount input {
            flex: 1;
            padding: 0.75rem;
            border: 2px solid var(--border-color);
            background: var(--bg-primary);
            color: var(--text-primary);
            border-radius: 0.5rem;
            font-size: 1rem;
        }
        
        .custom-amount input:focus {
            outline: none;
            border-color: var(--accent-color);
        }
        
        .currency {
            color: var(--text-secondary);
            font-weight: 500;
        }
        
        .paypal-container {
            margin-bottom: 2rem;
        }
        
        .donation-info {
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
        }
        
        .info-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: var(--text-secondary);
            font-size: 0.9rem;
        }
        
        .info-item svg {
            color: var(--success-color);
        }
        
        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            color: var(--accent-color);
            text-decoration: none;
            font-weight: 500;
            margin-top: 1rem;
        }
        
        .back-link:hover {
            text-decoration: underline;
        }
        
        @media (max-width: 640px) {
            .donation-content {
                padding: 1.5rem;
            }
            
            .amount-buttons {
                grid-template-columns: repeat(2, 1fr);
            }
        }
    </style>
</head>
<body>
    <div class="donation-page">
        <div class="container">
            <div class="donation-content">
                <div class="donation-hero">
                    <h1 class="donation-title">Support Our Project</h1>
                    <p class="donation-subtitle">Your donation helps us keep this service free and improve it for everyone.</p>
                </div>

                <div class="donation-form">
                    <div class="amount-selection">
                        <h3>Choose Donation Amount</h3>
                        <div class="amount-buttons">
                            @foreach($config['donation_amounts'] as $amount)
                                <button class="amount-btn" data-amount="{{ $amount }}">
                                    ${{ $amount }}
                                </button>
                            @endforeach
                            <div class="custom-amount">
                                <input type="number" id="customAmount" placeholder="Custom amount" min="1" step="0.01">
                                <span class="currency">{{ $config['currency'] }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="paypal-container">
                        <div id="paypal-button-container"></div>
                    </div>

                    <div class="donation-info">
                        <div class="info-item">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M12 22C17.5228 22 22 17.5228 22 12C22 6.47715 17.5228 2 12 2C6.47715 2 2 6.47715 2 12C2 17.5228 6.47715 22 12 22Z" stroke="currentColor" stroke-width="2"/>
                                <path d="M9 12L11 14L15 10" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            <span>Secure payment via PayPal</span>
                        </div>
                        <div class="info-item">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M12 22C17.5228 22 22 17.5228 22 12C22 6.47715 17.5228 2 12 2C6.47715 2 2 6.47715 2 12C2 17.5228 6.47715 22 12 22Z" stroke="currentColor" stroke-width="2"/>
                                <path d="M9 12L11 14L15 10" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            <span>100% of your donation goes to development</span>
                        </div>
                    </div>
                </div>
                
                <a href="{{ route('home') }}" class="back-link">
                    ← Back to Home
                </a>
            </div>
        </div>
    </div>

    <script src="https://www.paypal.com/sdk/js?client-id={{ $config['client_id'] }}&currency={{ $config['currency'] }}"></script>
    <script>
        let selectedAmount = {{ $config['donation_amounts'][0] }};

        // Amount selection
        document.querySelectorAll('.amount-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                document.querySelectorAll('.amount-btn').forEach(b => b.classList.remove('active'));
                this.classList.add('active');
                selectedAmount = parseFloat(this.dataset.amount);
                document.getElementById('customAmount').value = '';
            });
        });

        // Custom amount input
        document.getElementById('customAmount').addEventListener('input', function() {
            if (this.value) {
                document.querySelectorAll('.amount-btn').forEach(b => b.classList.remove('active'));
                selectedAmount = parseFloat(this.value);
            }
        });

        // PayPal integration
        paypal.Buttons({
            createOrder: function(data, actions) {
                return fetch('/donate/create-order', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        amount: selectedAmount,
                        currency: '{{ $config["currency"] }}'
                    })
                })
                .then(response => response.json())
                .then(orderData => {
                    if (orderData.error) {
                        throw new Error(orderData.error);
                    }
                    return orderData.id;
                });
            },
            onApprove: function(data, actions) {
                return actions.order.capture().then(function(details) {
                    window.location.href = '/donate/success?token=' + data.orderID;
                });
            },
            onError: function(err) {
                console.error('PayPal error:', err);
                alert('There was an error processing your donation. Please try again.');
            }
        }).render('#paypal-button-container');
    </script>
</body>
</html>
