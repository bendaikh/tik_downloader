<x-admin.layout title="Microsoft Services Integration">
    <div class="content-card card">
        <div class="heading">
            <h2>Microsoft Services Integration</h2>
            <p>Connect your TikTok downloader website with Microsoft services for tracking and analytics</p>
        </div>

        <form action="{{ route('admin.edge-integration.update') }}" method="POST" id="microsoftIntegrationForm">
            @csrf
            
            <!-- Bing Webmaster Tools -->
            <div class="service-section">
                <h3>Bing Webmaster Tools</h3>
                <p>Track your website's performance in Bing search results and get insights about your site's visibility.</p>
                
                <div class="form-element {{ $errors->has('bing_webmaster_enabled') ? 'is-error' : '' }}">
                    <label class="checkbox-label">
                        <input type="checkbox" name="bing_webmaster_enabled" value="1" {{ config('services.microsoft.bing_webmaster.enabled', false) ? 'checked' : '' }}>
                        Enable Bing Webmaster Tools
                    </label>
                    <small>Track your website's performance in Bing search results</small>
                    @error('bing_webmaster_enabled')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-element {{ $errors->has('bing_webmaster_verification') ? 'is-error' : '' }}">
                    <label for="bing_webmaster_verification">Verification Code</label>
                    <input type="text" id="bing_webmaster_verification" name="bing_webmaster_verification" value="{{ config('services.microsoft.bing_webmaster.verification_code') }}" placeholder="Enter your Bing Webmaster verification code">
                    <small>
                        <strong>Important:</strong> Enter only the verification code (e.g., A0554F7B2549B5B8BCC636A82B4008EC), 
                        NOT the full meta tag. If you paste the full meta tag, it will be automatically extracted.
                        <br>Get this code from <a href="https://www.bing.com/webmasters" target="_blank">Bing Webmaster Tools</a>
                    </small>
                    @error('bing_webmaster_verification')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- Microsoft Clarity -->
            <div class="service-section">
                <h3>Microsoft Clarity</h3>
                <p>Get insights into how users interact with your website through heatmaps, session recordings, and analytics.</p>
                
                <div class="form-element {{ $errors->has('microsoft_clarity_enabled') ? 'is-error' : '' }}">
                    <label class="checkbox-label">
                        <input type="checkbox" name="microsoft_clarity_enabled" value="1" {{ config('services.microsoft.clarity.enabled', false) ? 'checked' : '' }}>
                        Enable Microsoft Clarity
                    </label>
                    <small>Track user behavior and get website insights</small>
                    @error('microsoft_clarity_enabled')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-element {{ $errors->has('microsoft_clarity_id') ? 'is-error' : '' }}">
                    <label for="microsoft_clarity_id">Clarity Project ID</label>
                    <input type="text" id="microsoft_clarity_id" name="microsoft_clarity_id" value="{{ config('services.microsoft.clarity.project_id') }}" placeholder="Enter your Microsoft Clarity Project ID">
                    <small>Get this ID from <a href="https://clarity.microsoft.com" target="_blank">Microsoft Clarity</a></small>
                    @error('microsoft_clarity_id')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- Microsoft Advertising -->
            <div class="service-section">
                <h3>Microsoft Advertising</h3>
                <p>Track conversions and performance from your Microsoft Advertising campaigns.</p>
                
                <div class="form-element {{ $errors->has('microsoft_ads_enabled') ? 'is-error' : '' }}">
                    <label class="checkbox-label">
                        <input type="checkbox" name="microsoft_ads_enabled" value="1" {{ config('services.microsoft.ads.enabled', false) ? 'checked' : '' }}>
                        Enable Microsoft Advertising Tracking
                    </label>
                    <small>Track conversions from Microsoft Advertising campaigns</small>
                    @error('microsoft_ads_enabled')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-element {{ $errors->has('microsoft_ads_id') ? 'is-error' : '' }}">
                    <label for="microsoft_ads_id">Microsoft Ads ID</label>
                    <input type="text" id="microsoft_ads_id" name="microsoft_ads_id" value="{{ config('services.microsoft.ads.tracking_id') }}" placeholder="Enter your Microsoft Advertising tracking ID">
                    <small>Get this ID from your Microsoft Advertising account</small>
                    @error('microsoft_ads_id')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- Azure Application Insights -->
            <div class="service-section">
                <h3>Azure Application Insights</h3>
                <p>Monitor your application's performance, errors, and usage patterns in real-time.</p>
                
                <div class="form-element {{ $errors->has('azure_application_insights_enabled') ? 'is-error' : '' }}">
                    <label class="checkbox-label">
                        <input type="checkbox" name="azure_application_insights_enabled" value="1" {{ config('services.microsoft.application_insights.enabled', false) ? 'checked' : '' }}>
                        Enable Azure Application Insights
                    </label>
                    <small>Monitor application performance and errors</small>
                    @error('azure_application_insights_enabled')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-element {{ $errors->has('azure_application_insights_key') ? 'is-error' : '' }}">
                    <label for="azure_application_insights_key">Instrumentation Key</label>
                    <input type="text" id="azure_application_insights_key" name="azure_application_insights_key" value="{{ config('services.microsoft.application_insights.instrumentation_key') }}" placeholder="Enter your Azure Application Insights Instrumentation Key">
                    <small>Get this key from your Azure Application Insights resource</small>
                    @error('azure_application_insights_key')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            @push('header')
                <button class="button is-primary" type="submit" form="microsoftIntegrationForm">Save Settings</button>
            @endpush
        </form>
    </div>

    <!-- Setup Instructions Section -->
    <div class="content-card card">
        <div class="heading">
            <h2>How to Set Up Microsoft Services</h2>
            <p>Follow these steps to connect your TikTok downloader website with Microsoft services</p>
        </div>

        <div class="instructions-container">
            <!-- Bing Webmaster Tools Setup -->
            <div class="instruction-step">
                <div class="step-number">1</div>
                <div class="step-content">
                    <h3>Bing Webmaster Tools Setup</h3>
                    <p>Track your website's performance in Bing search results and get SEO insights.</p>
                    <div class="setup-steps">
                        <strong>Setup Steps:</strong>
                        <ol>
                            <li>Go to <a href="https://www.bing.com/webmasters" target="_blank">Bing Webmaster Tools</a></li>
                            <li>Sign in with your Microsoft account</li>
                            <li>Click "Add a site" and enter your website URL: <code>{{ config('app.url') }}</code></li>
                            <li>Choose verification method (HTML tag, XML file, or DNS)</li>
                            <li>Copy the verification code provided (e.g., A0554F7B2549B5B8BCC636A82B4008EC)</li>
                            <li>Paste the code in the "Verification Code" field above</li>
                            <li>Save settings and verify your site</li>
                        </ol>
                        <div class="note" style="margin-top: 10px; padding: 10px; background: #f0f8ff; border-left: 4px solid #007cba;">
                            <strong>Note:</strong> You can paste either just the verification code or the full meta tag - the system will automatically extract the code for you.
                        </div>
                    </div>
                    <div class="note">
                        <strong>Benefits:</strong> Track search performance, submit sitemaps, monitor crawl errors, and get SEO recommendations.
                    </div>
                </div>
            </div>

            <!-- Microsoft Clarity Setup -->
            <div class="instruction-step">
                <div class="step-number">2</div>
                <div class="step-content">
                    <h3>Microsoft Clarity Setup</h3>
                    <p>Get insights into how users interact with your website through heatmaps and session recordings.</p>
                    <div class="setup-steps">
                        <strong>Setup Steps:</strong>
                        <ol>
                            <li>Go to <a href="https://clarity.microsoft.com" target="_blank">Microsoft Clarity</a></li>
                            <li>Sign in with your Microsoft account</li>
                            <li>Click "Create Project" and enter your website details</li>
                            <li>Copy the Project ID from your dashboard</li>
                            <li>Paste the Project ID in the "Clarity Project ID" field above</li>
                            <li>Save settings to enable tracking</li>
                        </ol>
                    </div>
                    <div class="note">
                        <strong>Benefits:</strong> Heatmaps, session recordings, user behavior insights, and performance analytics.
                    </div>
                </div>
            </div>

            <!-- Microsoft Advertising Setup -->
            <div class="instruction-step">
                <div class="step-number">3</div>
                <div class="step-content">
                    <h3>Microsoft Advertising Setup</h3>
                    <p>Track conversions and performance from your Microsoft Advertising campaigns.</p>
                    <div class="setup-steps">
                        <strong>Setup Steps:</strong>
                        <ol>
                            <li>Go to your <a href="https://ads.microsoft.com" target="_blank">Microsoft Advertising</a> account</li>
                            <li>Navigate to Tools → Conversion Tracking</li>
                            <li>Create a new conversion goal for your TikTok downloads</li>
                            <li>Copy the tracking ID (UET tag ID)</li>
                            <li>Paste the tracking ID in the "Microsoft Ads ID" field above</li>
                            <li>Save settings to enable conversion tracking</li>
                        </ol>
                    </div>
                    <div class="note">
                        <strong>Benefits:</strong> Track ad performance, measure ROI, optimize campaigns, and improve conversion rates.
                    </div>
                </div>
            </div>

            <!-- Azure Application Insights Setup -->
            <div class="instruction-step">
                <div class="step-number">4</div>
                <div class="step-content">
                    <h3>Azure Application Insights Setup</h3>
                    <p>Monitor your application's performance, errors, and usage patterns in real-time.</p>
                    <div class="setup-steps">
                        <strong>Setup Steps:</strong>
                        <ol>
                            <li>Go to <a href="https://portal.azure.com" target="_blank">Azure Portal</a></li>
                            <li>Create a new Application Insights resource</li>
                            <li>Choose your subscription and resource group</li>
                            <li>Enter a name for your Application Insights resource</li>
                            <li>Copy the Instrumentation Key from the Overview page</li>
                            <li>Paste the key in the "Instrumentation Key" field above</li>
                            <li>Save settings to enable monitoring</li>
                        </ol>
                    </div>
                    <div class="note">
                        <strong>Benefits:</strong> Real-time monitoring, error tracking, performance insights, and usage analytics.
                    </div>
                </div>
            </div>

            <!-- Verification Steps -->
            <div class="instruction-step">
                <div class="step-number">5</div>
                <div class="step-content">
                    <h3>Verify Your Setup</h3>
                    <p>After configuring all services, verify that everything is working correctly.</p>
                    <div class="verification-steps">
                        <strong>Verification Steps:</strong>
                        <ol>
                            <li><strong>Bing Webmaster Tools:</strong> Check that your site is verified and indexed</li>
                            <li><strong>Microsoft Clarity:</strong> Visit your website and check for data in Clarity dashboard</li>
                            <li><strong>Microsoft Advertising:</strong> Test a conversion event and verify it appears in your ads account</li>
                            <li><strong>Application Insights:</strong> Check the Azure portal for incoming telemetry data</li>
                        </ol>
                    </div>
                </div>
            </div>

            <!-- Troubleshooting -->
            <div class="instruction-step troubleshooting">
                <div class="step-number">?</div>
                <div class="step-content">
                    <h3>Troubleshooting</h3>
                    <div class="troubleshooting-list">
                        <div class="trouble-item">
                            <strong>Bing Webmaster Tools not working?</strong>
                            <p>Verify the HTML tag is in your website's head section or check DNS/XML file verification.</p>
                        </div>
                        <div class="trouble-item">
                            <strong>Clarity not tracking?</strong>
                            <p>Ensure the Project ID is correct and the tracking code is properly installed on your website.</p>
                        </div>
                        <div class="trouble-item">
                            <strong>Ads conversion not tracking?</strong>
                            <p>Check that the UET tag is firing correctly and the conversion goal is properly configured.</p>
                        </div>
                        <div class="trouble-item">
                            <strong>Application Insights not receiving data?</strong>
                            <p>Verify the instrumentation key is correct and the SDK is properly integrated.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .instructions-container {
            margin-top: 2rem;
        }
        
        .instruction-step {
            display: flex;
            margin-bottom: 2rem;
            padding: 1.5rem;
            border: 1px solid #e5e7eb;
            border-radius: 0.5rem;
            background: #f9fafb;
        }
        
        .step-number {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            background: #3b82f6;
            color: white;
            border-radius: 50%;
            font-weight: bold;
            font-size: 1.2rem;
            margin-right: 1rem;
            flex-shrink: 0;
        }
        
        .instruction-step.troubleshooting .step-number {
            background: #f59e0b;
        }
        
        .step-content {
            flex: 1;
        }
        
        .step-content h3 {
            margin: 0 0 0.5rem 0;
            color: #374151;
            font-size: 1.1rem;
            font-weight: 600;
        }
        
        .step-content p {
            margin: 0 0 0.75rem 0;
            color: #6b7280;
            line-height: 1.5;
        }
        
        .code-block {
            background: #1f2937;
            color: #f9fafb;
            padding: 0.75rem;
            border-radius: 0.375rem;
            margin: 0.75rem 0;
            font-family: 'Courier New', monospace;
            font-size: 0.875rem;
        }
        
        .note {
            background: #eff6ff;
            border: 1px solid #dbeafe;
            border-radius: 0.375rem;
            padding: 0.75rem;
            margin: 0.75rem 0;
        }
        
        .note strong {
            color: #1e40af;
        }
        
        .settings-list {
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
            border-radius: 0.375rem;
            padding: 0.75rem;
            margin: 0.75rem 0;
        }
        
        .settings-list ul {
            margin: 0.5rem 0 0 0;
            padding-left: 1.5rem;
        }
        
        .settings-list li {
            margin-bottom: 0.25rem;
            color: #374151;
        }
        
        .setup-steps, .verification-steps {
            background: #fef3c7;
            border: 1px solid #fde68a;
            border-radius: 0.375rem;
            padding: 0.75rem;
            margin: 0.75rem 0;
        }
        
        .setup-steps ol, .verification-steps ol {
            margin: 0.5rem 0 0 0;
            padding-left: 1.5rem;
        }
        
        .setup-steps li, .verification-steps li {
            margin-bottom: 0.25rem;
            color: #374151;
        }
        
        .service-section {
            margin-bottom: 2rem;
            padding: 1.5rem;
            border: 1px solid #e5e7eb;
            border-radius: 0.5rem;
            background: #f9fafb;
        }
        
        .service-section h3 {
            margin: 0 0 0.5rem 0;
            color: #374151;
            font-size: 1.1rem;
            font-weight: 600;
        }
        
        .service-section p {
            margin: 0 0 1rem 0;
            color: #6b7280;
            line-height: 1.5;
        }
        
        .troubleshooting-list {
            display: grid;
            gap: 1rem;
        }
        
        .trouble-item {
            background: #fef2f2;
            border: 1px solid #fecaca;
            border-radius: 0.375rem;
            padding: 0.75rem;
        }
        
        .trouble-item strong {
            color: #dc2626;
            display: block;
            margin-bottom: 0.25rem;
        }
        
        .trouble-item p {
            margin: 0;
            color: #6b7280;
            font-size: 0.875rem;
        }
    </style>
</x-admin.layout>
