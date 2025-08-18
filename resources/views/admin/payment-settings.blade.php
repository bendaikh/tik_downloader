<x-admin.layout title="Payment Settings">
    <div class="content-card card">
        <div class="heading">
            <h2>Payment Settings</h2>
            <p>Configure PayPal integration for donations</p>
        </div>

        <form
            action="{{route('admin.payment-settings.update')}}"
            method="POST"
            id="paymentSettingsForm"
        >
            @csrf
            
            <!-- PayPal Configuration Section -->
            <div class="settings-section">
                <h3>PayPal Configuration</h3>
                
                <div @class(['form-element', 'is-error'=> $errors->has('paypal_client_id')])>
                    <label for="paypal_client_id">PayPal Client ID</label>
                    <input 
                        type="text" 
                        id="paypal_client_id" 
                        name="paypal_client_id" 
                        value="{{config('payments.paypal_client_id')}}"
                        placeholder="Enter your PayPal Client ID"
                    >
                    <small>Get this from your PayPal Developer Dashboard</small>
                    @error('paypal_client_id')
                    <div class="error">{{$message}}</div>
                    @enderror
                </div>

                <div @class(['form-element', 'is-error'=> $errors->has('paypal_client_secret')])>
                    <label for="paypal_client_secret">PayPal Client Secret</label>
                    <input 
                        type="password" 
                        id="paypal_client_secret" 
                        name="paypal_client_secret" 
                        value="{{config('payments.paypal_client_secret')}}"
                        placeholder="Enter your PayPal Client Secret"
                    >
                    <small>Get this from your PayPal Developer Dashboard</small>
                    @error('paypal_client_secret')
                    <div class="error">{{$message}}</div>
                    @enderror
                </div>

                <div @class(['form-element', 'is-required', 'is-error'=> $errors->has('paypal_mode')])>
                    <label for="paypal_mode">PayPal Mode</label>
                    <select id="paypal_mode" name="paypal_mode" required>
                        <option value="sandbox" {{config('payments.paypal_mode') == 'sandbox' ? 'selected' : ''}}>
                            Sandbox (Testing)
                        </option>
                        <option value="live" {{config('payments.paypal_mode') == 'live' ? 'selected' : ''}}>
                            Live (Production)
                        </option>
                    </select>
                    <small>Use Sandbox for testing, Live for real payments</small>
                    @error('paypal_mode')
                    <div class="error">{{$message}}</div>
                    @enderror
                </div>

                <div @class(['form-element', 'is-required', 'is-error'=> $errors->has('paypal_currency')])>
                    <label for="paypal_currency">Currency</label>
                    <select id="paypal_currency" name="paypal_currency" required>
                        <option value="USD" {{config('payments.paypal_currency') == 'USD' ? 'selected' : ''}}>USD - US Dollar</option>
                        <option value="EUR" {{config('payments.paypal_currency') == 'EUR' ? 'selected' : ''}}>EUR - Euro</option>
                        <option value="GBP" {{config('payments.paypal_currency') == 'GBP' ? 'selected' : ''}}>GBP - British Pound</option>
                        <option value="CAD" {{config('payments.paypal_currency') == 'CAD' ? 'selected' : ''}}>CAD - Canadian Dollar</option>
                        <option value="AUD" {{config('payments.paypal_currency') == 'AUD' ? 'selected' : ''}}>AUD - Australian Dollar</option>
                    </select>
                    @error('paypal_currency')
                    <div class="error">{{$message}}</div>
                    @enderror
                </div>
            </div>

            <!-- Donation Settings Section -->
            <div class="settings-section">
                <h3>Donation Settings</h3>
                
                <div @class(['form-element', 'is-error'=> $errors->has('donation_enabled')])>
                    <label class="checkbox-label">
                        <input 
                            type="checkbox" 
                            name="donation_enabled" 
                            value="1" 
                            {{config('payments.donation_enabled') ? 'checked' : ''}}
                        >
                        <span>Enable Donations</span>
                    </label>
                    <small>Show the donate button on the website</small>
                    @error('donation_enabled')
                    <div class="error">{{$message}}</div>
                    @enderror
                </div>

                <div @class(['form-element', 'is-error'=> $errors->has('donation_amounts')])>
                    <label for="donation_amounts">Donation Amounts (comma-separated)</label>
                    <input 
                        type="text" 
                        id="donation_amounts" 
                        name="donation_amounts" 
                        value="{{config('payments.donation_amounts', '5,10,25,50,100')}}"
                        placeholder="5,10,25,50,100"
                    >
                    <small>Enter amounts separated by commas (e.g., 5,10,25,50,100)</small>
                    @error('donation_amounts')
                    <div class="error">{{$message}}</div>
                    @enderror
                </div>
            </div>

            <!-- Instructions Section -->
            <div class="settings-section">
                <h3>Setup Instructions</h3>
                <div class="instructions">
                    <ol>
                        <li>Go to <a href="https://developer.paypal.com" target="_blank">PayPal Developer Dashboard</a></li>
                        <li>Create a new app or use an existing one</li>
                        <li>Copy the Client ID and Client Secret</li>
                        <li>Paste them in the fields above</li>
                        <li>Choose Sandbox for testing or Live for real payments</li>
                        <li>Save the settings</li>
                    </ol>
                </div>
            </div>

            @push('header')
                <button class="button is-primary" type="submit" form="paymentSettingsForm">Save Settings</button>
            @endpush
        </form>
    </div>

    <style>
        .settings-section {
            margin-bottom: 2rem;
            padding: 1.5rem;
            background: #f8f9fa;
            border-radius: 8px;
            border: 1px solid #e9ecef;
        }

        .settings-section h3 {
            margin-bottom: 1rem;
            color: #333;
            font-size: 1.1rem;
            font-weight: 600;
        }

        .instructions {
            background: #e3f2fd;
            padding: 1rem;
            border-radius: 6px;
            border-left: 4px solid #2196f3;
        }

        .instructions ol {
            margin: 0;
            padding-left: 1.5rem;
        }

        .instructions li {
            margin-bottom: 0.5rem;
            color: #1976d2;
        }

        .instructions a {
            color: #2196f3;
            text-decoration: underline;
        }

        .checkbox-label {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            cursor: pointer;
        }

        .checkbox-label input[type="checkbox"] {
            margin: 0;
        }
    </style>
</x-admin.layout>
