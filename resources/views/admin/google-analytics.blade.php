<x-admin.layout title="Google Analytics Settings">
    <div class="content-card card">
        <div class="heading">
            <h2>Google Analytics Settings</h2>
            <p>Configure Google Analytics 4 (GA4) integration for your website</p>
        </div>

        <form
            action="{{route('admin.google-analytics.update')}}"
            method="POST"
            id="googleAnalyticsForm"
        >
            @csrf
            
            <!-- Google Analytics Configuration Section -->
            <div class="settings-section">
                <h3>Google Analytics Configuration</h3>
                
                <div @class(['form-element', 'is-error'=> $errors->has('ga_measurement_id')])>
                    <label for="ga_measurement_id">Measurement ID (G-XXXXXXXXXX)</label>
                    <input 
                        type="text" 
                        id="ga_measurement_id" 
                        name="ga_measurement_id" 
                        value="{{config('analytics.ga_measurement_id')}}"
                        placeholder="G-XXXXXXXXXX"
                        pattern="G-[A-Z0-9]{10}"
                    >
                    <small>Enter your Google Analytics 4 Measurement ID (starts with G-)</small>
                    @error('ga_measurement_id')
                    <div class="error">{{$message}}</div>
                    @enderror
                </div>

                <div @class(['form-element', 'is-error'=> $errors->has('ga_enabled')])>
                    <label class="checkbox-label">
                        <input 
                            type="checkbox" 
                            name="ga_enabled" 
                            value="1" 
                            {{config('analytics.ga_enabled') ? 'checked' : ''}}
                        >
                        <span>Enable Google Analytics</span>
                    </label>
                    <small>Enable Google Analytics tracking on your website</small>
                    @error('ga_enabled')
                    <div class="error">{{$message}}</div>
                    @enderror
                </div>
            </div>

            <!-- Tracking Options Section -->
            <div class="settings-section">
                <h3>Tracking Options</h3>
                
                <div @class(['form-element', 'is-error'=> $errors->has('ga_track_pageviews')])>
                    <label class="checkbox-label">
                        <input 
                            type="checkbox" 
                            name="ga_track_pageviews" 
                            value="1" 
                            {{config('analytics.ga_track_pageviews', true) ? 'checked' : ''}}
                        >
                        <span>Track Page Views</span>
                    </label>
                    <small>Automatically track page views across your website</small>
                    @error('ga_track_pageviews')
                    <div class="error">{{$message}}</div>
                    @enderror
                </div>

                <div @class(['form-element', 'is-error'=> $errors->has('ga_track_events')])>
                    <label class="checkbox-label">
                        <input 
                            type="checkbox" 
                            name="ga_track_events" 
                            value="1" 
                            {{config('analytics.ga_track_events', true) ? 'checked' : ''}}
                        >
                        <span>Track Custom Events</span>
                    </label>
                    <small>Track button clicks, form submissions, and other interactions</small>
                    @error('ga_track_events')
                    <div class="error">{{$message}}</div>
                    @enderror
                </div>

                <div @class(['form-element', 'is-error'=> $errors->has('ga_track_downloads')])>
                    <label class="checkbox-label">
                        <input 
                            type="checkbox" 
                            name="ga_track_downloads" 
                            value="1" 
                            {{config('analytics.ga_track_downloads', true) ? 'checked' : ''}}
                        >
                        <span>Track Video Downloads</span>
                    </label>
                    <small>Track when users download TikTok videos</small>
                    @error('ga_track_downloads')
                    <div class="error">{{$message}}</div>
                    @enderror
                </div>

                <div @class(['form-element', 'is-error'=> $errors->has('ga_track_donations')])>
                    <label class="checkbox-label">
                        <input 
                            type="checkbox" 
                            name="ga_track_donations" 
                            value="1" 
                            {{config('analytics.ga_track_donations', true) ? 'checked' : ''}}
                        >
                        <span>Track Donations</span>
                    </label>
                    <small>Track donation events and amounts</small>
                    @error('ga_track_donations')
                    <div class="error">{{$message}}</div>
                    @enderror
                </div>
            </div>

            <!-- Privacy & Advanced Settings Section -->
            <div class="settings-section">
                <h3>Privacy & Advanced Settings</h3>
                
                <div @class(['form-element', 'is-error'=> $errors->has('ga_anonymize_ip')])>
                    <label class="checkbox-label">
                        <input 
                            type="checkbox" 
                            name="ga_anonymize_ip" 
                            value="1" 
                            {{config('analytics.ga_anonymize_ip', true) ? 'checked' : ''}}
                        >
                        <span>Anonymize IP Addresses</span>
                    </label>
                    <small>Anonymize visitor IP addresses for privacy compliance (GDPR)</small>
                    @error('ga_anonymize_ip')
                    <div class="error">{{$message}}</div>
                    @enderror
                </div>

                <div @class(['form-element', 'is-error'=> $errors->has('ga_debug_mode')])>
                    <label class="checkbox-label">
                        <input 
                            type="checkbox" 
                            name="ga_debug_mode" 
                            value="1" 
                            {{config('analytics.ga_debug_mode') ? 'checked' : ''}}
                        >
                        <span>Debug Mode</span>
                    </label>
                    <small>Enable debug mode to see analytics data in browser console (for testing)</small>
                    @error('ga_debug_mode')
                    <div class="error">{{$message}}</div>
                    @enderror
                </div>
            </div>

            <!-- Setup Instructions Section -->
            <div class="settings-section">
                <h3>Setup Instructions</h3>
                <div class="instructions">
                    <ol>
                        <li>Go to <a href="https://analytics.google.com" target="_blank">Google Analytics</a></li>
                        <li>Create a new property or use an existing one</li>
                        <li>Set up a data stream for your website</li>
                        <li>Copy the Measurement ID (starts with G-)</li>
                        <li>Paste it in the "Measurement ID" field above</li>
                        <li>Enable the tracking options you want</li>
                        <li>Save the settings</li>
                        <li>Wait 24-48 hours to see data in Google Analytics</li>
                    </ol>
                </div>
            </div>

            <!-- Testing Section -->
            <div class="settings-section">
                <h3>Testing Your Setup</h3>
                <div class="testing-info">
                    <p><strong>To test if Google Analytics is working:</strong></p>
                    <ul>
                        <li>Enable "Debug Mode" above</li>
                        <li>Open your website in a browser</li>
                        <li>Open Developer Tools (F12)</li>
                        <li>Go to the Console tab</li>
                        <li>You should see "GA4 Debug" messages</li>
                        <li>Check the Network tab for requests to google-analytics.com</li>
                    </ul>
                </div>
            </div>

            @push('header')
                <button class="button is-primary" type="submit" form="googleAnalyticsForm">Save Settings</button>
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

        .testing-info {
            background: #fff3cd;
            padding: 1rem;
            border-radius: 6px;
            border-left: 4px solid #ffc107;
        }

        .testing-info ul {
            margin: 0.5rem 0 0 0;
            padding-left: 1.5rem;
        }

        .testing-info li {
            margin-bottom: 0.25rem;
            color: #856404;
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

        input[pattern] {
            font-family: monospace;
        }
    </style>
</x-admin.layout>
