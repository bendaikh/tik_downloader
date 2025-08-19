<x-admin.layout title="Safari Analytics Settings">
    <div class="content-card card">
        <div class="heading">
            <h2>Safari Analytics Settings</h2>
            <p>Configure Safari Analytics integration for your website</p>
        </div>

        <form
            action="{{route('admin.safari.update')}}"
            method="POST"
            id="safariForm"
        >
            @csrf
            
            <!-- Safari Analytics Configuration Section -->
            <div class="settings-section">
                <h3>Safari Analytics Configuration</h3>
                
                <div @class(['form-element', 'is-error'=> $errors->has('safari_website_id')])>
                    <label for="safari_website_id">Website ID</label>
                    <input 
                        type="text" 
                        id="safari_website_id" 
                        name="safari_website_id" 
                        value="{{config('safari.website_id')}}"
                        placeholder="SAF-XXXXXXXXXX"
                        pattern="SAF-[A-Z0-9]{10}"
                    >
                    <small>Enter your Safari Analytics Website ID (starts with SAF-)</small>
                    @error('safari_website_id')
                    <div class="error">{{$message}}</div>
                    @enderror
                </div>

                <div @class(['form-element', 'is-error'=> $errors->has('safari_enabled')])>
                    <label class="checkbox-label">
                        <input 
                            type="checkbox" 
                            name="safari_enabled" 
                            value="1" 
                            {{config('safari.enabled') ? 'checked' : ''}}
                        >
                        <span>Enable Safari Analytics</span>
                    </label>
                    <small>Enable Safari Analytics tracking on your website</small>
                    @error('safari_enabled')
                    <div class="error">{{$message}}</div>
                    @enderror
                </div>
            </div>

            <!-- Tracking Options Section -->
            <div class="settings-section">
                <h3>Tracking Options</h3>
                
                <div @class(['form-element', 'is-error'=> $errors->has('safari_track_pageviews')])>
                    <label class="checkbox-label">
                        <input 
                            type="checkbox" 
                            name="safari_track_pageviews" 
                            value="1" 
                            {{config('safari.track_pageviews', true) ? 'checked' : ''}}
                        >
                        <span>Track Page Views</span>
                    </label>
                    <small>Automatically track page views across your website</small>
                    @error('safari_track_pageviews')
                    <div class="error">{{$message}}</div>
                    @enderror
                </div>

                <div @class(['form-element', 'is-error'=> $errors->has('safari_track_events')])>
                    <label class="checkbox-label">
                        <input 
                            type="checkbox" 
                            name="safari_track_events" 
                            value="1" 
                            {{config('safari.track_events', true) ? 'checked' : ''}}
                        >
                        <span>Track Custom Events</span>
                    </label>
                    <small>Track button clicks, form submissions, and other interactions</small>
                    @error('safari_track_events')
                    <div class="error">{{$message}}</div>
                    @enderror
                </div>

                <div @class(['form-element', 'is-error'=> $errors->has('safari_track_downloads')])>
                    <label class="checkbox-label">
                        <input 
                            type="checkbox" 
                            name="safari_track_downloads" 
                            value="1" 
                            {{config('safari.track_downloads', true) ? 'checked' : ''}}
                        >
                        <span>Track Video Downloads</span>
                    </label>
                    <small>Track when users download TikTok videos</small>
                    @error('safari_track_downloads')
                    <div class="error">{{$message}}</div>
                    @enderror
                </div>

                <div @class(['form-element', 'is-error'=> $errors->has('safari_track_donations')])>
                    <label class="checkbox-label">
                        <input 
                            type="checkbox" 
                            name="safari_track_donations" 
                            value="1" 
                            {{config('safari.track_donations', true) ? 'checked' : ''}}
                        >
                        <span>Track Donations</span>
                    </label>
                    <small>Track donation events and amounts</small>
                    @error('safari_track_donations')
                    <div class="error">{{$message}}</div>
                    @enderror
                </div>

                <div @class(['form-element', 'is-error'=> $errors->has('safari_track_engagement')])>
                    <label class="checkbox-label">
                        <input 
                            type="checkbox" 
                            name="safari_track_engagement" 
                            value="1" 
                            {{config('safari.track_engagement', true) ? 'checked' : ''}}
                        >
                        <span>Track User Engagement</span>
                    </label>
                    <small>Track user engagement metrics like time on page and scroll depth</small>
                    @error('safari_track_engagement')
                    <div class="error">{{$message}}</div>
                    @enderror
                </div>
            </div>

            <!-- Privacy & Advanced Settings Section -->
            <div class="settings-section">
                <h3>Privacy & Advanced Settings</h3>
                
                <div @class(['form-element', 'is-error'=> $errors->has('safari_debug_mode')])>
                    <label class="checkbox-label">
                        <input 
                            type="checkbox" 
                            name="safari_debug_mode" 
                            value="1" 
                            {{config('safari.debug_mode') ? 'checked' : ''}}
                        >
                        <span>Debug Mode</span>
                    </label>
                    <small>Enable debug mode to see analytics data in browser console (for testing)</small>
                    @error('safari_debug_mode')
                    <div class="error">{{$message}}</div>
                    @enderror
                </div>
            </div>

            <!-- Setup Instructions Section -->
            <div class="settings-section">
                <h3>Setup Instructions</h3>
                <div class="instructions">
                    <ol>
                        <li>Go to <a href="https://analytics.safari.com" target="_blank">Safari Analytics</a></li>
                        <li>Create a new website or use an existing one</li>
                        <li>Set up tracking for your website</li>
                        <li>Copy the Website ID (starts with SAF-)</li>
                        <li>Paste it in the "Website ID" field above</li>
                        <li>Enable the tracking options you want</li>
                        <li>Save the settings</li>
                        <li>Wait 24-48 hours to see data in Safari Analytics</li>
                    </ol>
                </div>
            </div>

            <!-- Testing Section -->
            <div class="settings-section">
                <h3>Testing Your Setup</h3>
                <div class="testing-info">
                    <p><strong>To test if Safari Analytics is working:</strong></p>
                    <ul>
                        <li>Enable "Debug Mode" above</li>
                        <li>Open your website in a browser</li>
                        <li>Open Developer Tools (F12)</li>
                        <li>Go to the Console tab</li>
                        <li>You should see "Safari Debug" messages</li>
                        <li>Check the Network tab for requests to analytics.safari.com</li>
                    </ul>
                </div>
            </div>

            @push('header')
                <button class="button is-primary" type="submit" form="safariForm">Save Settings</button>
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
