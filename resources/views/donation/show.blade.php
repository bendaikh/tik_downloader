<x-theme::layout>
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

    <style>
        .donation-page {
            padding: 4rem 0;
            background: var(--primary-bg);
            min-height: 100vh;
        }

        .donation-content {
            max-width: 600px;
            margin: 0 auto;
        }

        .donation-hero {
            text-align: center;
            margin-bottom: 3rem;
        }

        .donation-title {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
            background: var(--gradient-primary);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .donation-subtitle {
            font-size: 1.125rem;
            color: var(--text-secondary);
            line-height: 1.6;
        }

        .donation-form {
            background: var(--card-bg);
            border-radius: 16px;
            padding: 2rem;
            border: 1px solid var(--border-color);
        }

        .amount-selection {
            margin-bottom: 2rem;
        }

        .amount-selection h3 {
            font-size: 1.25rem;
            font-weight: 600;
            margin-bottom: 1rem;
            color: var(--text-primary);
        }

        .amount-buttons {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(80px, 1fr));
            gap: 0.75rem;
            margin-bottom: 1rem;
        }

        .amount-btn {
            background: var(--secondary-bg);
            border: 1px solid var(--border-color);
            color: var(--text-secondary);
            padding: 0.75rem;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .amount-btn:hover {
            background: var(--accent-pink);
            color: white;
            border-color: var(--accent-pink);
        }

        .amount-btn.active {
            background: var(--accent-pink);
            color: white;
            border-color: var(--accent-pink);
        }

        .custom-amount {
            position: relative;
            grid-column: 1 / -1;
        }

        .custom-amount input {
            width: 100%;
            padding: 0.75rem 2.5rem 0.75rem 1rem;
            background: var(--secondary-bg);
            border: 1px solid var(--border-color);
            border-radius: 8px;
            color: var(--text-primary);
            font-size: 1rem;
        }

        .custom-amount input:focus {
            outline: none;
            border-color: var(--accent-pink);
        }

        .currency {
            position: absolute;
            right: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-muted);
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
            font-size: 0.875rem;
        }

        .info-item svg {
            color: var(--success-color);
            flex-shrink: 0;
        }

        @media (max-width: 768px) {
            .donation-title {
                font-size: 2rem;
            }
            
            .donation-form {
                padding: 1.5rem;
            }
            
            .amount-buttons {
                grid-template-columns: repeat(3, 1fr);
            }
        }
    </style>
</x-theme::layout>
