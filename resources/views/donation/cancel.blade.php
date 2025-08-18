<x-theme::layout>
    <div class="donation-cancel-page">
        <div class="container">
            <div class="cancel-content">
                <div class="cancel-icon">
                    <svg width="80" height="80" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M12 22C17.5228 22 22 17.5228 22 12C22 6.47715 17.5228 2 12 2C6.47715 2 2 6.47715 2 12C2 17.5228 6.47715 22 12 22Z" stroke="currentColor" stroke-width="2"/>
                        <path d="M15 9L9 15M9 9L15 15" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </div>
                
                <h1 class="cancel-title">Donation Cancelled</h1>
                <p class="cancel-message">Your donation was cancelled. No charges were made to your account.</p>
                
                <div class="cancel-actions">
                    <a href="/" class="btn-primary">Return to Home</a>
                    <a href="/donate" class="btn-secondary">Try Again</a>
                </div>
                
                <div class="cancel-info">
                    <p>If you have any questions or need assistance, please don't hesitate to contact us.</p>
                </div>
            </div>
        </div>
    </div>

    <style>
        .donation-cancel-page {
            padding: 4rem 0;
            background: var(--primary-bg);
            min-height: 100vh;
            display: flex;
            align-items: center;
        }

        .cancel-content {
            max-width: 500px;
            margin: 0 auto;
            text-align: center;
        }

        .cancel-icon {
            margin-bottom: 2rem;
            color: var(--warning-color);
        }

        .cancel-title {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
            color: var(--text-primary);
        }

        .cancel-message {
            font-size: 1.125rem;
            color: var(--text-secondary);
            margin-bottom: 2rem;
        }

        .cancel-actions {
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

        .cancel-info {
            color: var(--text-secondary);
            font-style: italic;
        }

        @media (max-width: 768px) {
            .cancel-title {
                font-size: 2rem;
            }
            
            .cancel-actions {
                flex-direction: column;
            }
        }
    </style>
</x-theme::layout>
